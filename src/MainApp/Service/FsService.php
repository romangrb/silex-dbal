<?php
namespace MainApp\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class FsService  {
    
    private $wr;
    
    private $fs;
    
    private $supported_image_type = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    const UPLOAD_ROOT_NAME = '/upload/'; 
    private function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/fs.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('crud_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    public function __construct() {
       $this->init_logger();
       $this->fs = new Filesystem();
    }
     
    public function saveFiles($files){
        $status;
        
        $dir_name = mt_rand();
        $file_path = __DIR__. self::UPLOAD_ROOT_NAME . $dir_name;
        
        try {
          $this->fs->mkdir($file_path);
        } catch (IOExceptionInterface $e) {
          $this->wr->addError('An error occurred while creating your directory at : ',  $e->getPath());
        }
        // create the directory
        foreach ($files as $key => $val) {
           // get ext or gues if is not exist of MIME type
           $ext = ($val->getClientOriginalExtension()) ? $val->getClientOriginalExtension() : $val->guessExtension;
           $name = $key . '.'.$ext;
           
           try {
                $val->move($file_path, $name);
            } catch (IOExceptionInterface  $e) {
                $this->wr->addError('Saving file error : ',  $e->getMessage(), "\n");
                $status = $this->statusHandler(false, 'Saving file error occured :' . $e->getMessage());
                
            }
            
        }
         
        try {
          if ($this->fs->exists($file_path)) $this->fs->remove($file_path);
        } catch (IOExceptionInterface $e) {
          $this->wr->addError('An error occurred while removing your directory at : ',  $e->getPath());
        }
         
        
        
        // $this->wr->addInfo(print_r($rq_data,true)); 
        return $dir_name;
      
    }
    
    private function writeFiles($is_success, $txt){
        $obj=new \stdClass();
        ($is_success) ? $obj->success = $txt : $obj->error = $txt;
        return $obj; 
    }
    
    private function statusHandler($is_success, $txt){
        $obj=new \stdClass();
        ($is_success) ? $obj->success = $txt : $obj->error = $txt;
        return $obj; 
    }

}
?>
