<?php
namespace MainApp\Service;

use Doctrine\DBAL\connection;
use MainApp\Entity\Message;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class FsService  {
    
    protected $wr;
    
    protected function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/fs.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('crud_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    public function __construct() {
       $this->init_logger();
    }
     
    public function writeFile($file){
    
        // if post file data is null responce error
    	if ($file == null) {
    		$responce = $this->statusHandler(false, "No image was definded");
    	 	return json_encode($responce);
    	}
        // get the file extension & make it lowercase
        $extension=strtolower($file->getClientOriginalExtension());
        // check if it is valid
        if(! in_array($extension,  $this->supported_image )){
            $responce = $this->statusHandler(false, "is not an valid image type of 'gif', 'jpg', 'jpeg', 'png' !");
            return json_encode($responce);
        }
        // add extension to name
        $name = $this->generateRandomString(10); $name .= '.'.$extension;
        // create the file
        $file->move(__DIR__.'/upload', $name);
        // return an success message
        $responce = $this->statusHandler(true, "Image successfully uploaded !  " . $name);
       
        // $this->wr->addInfo(json_encode($obj));
        return $responce;
    }
    
    private function statusHandler($is_success, $txt){
        $obj=new \stdClass();
        ($is_success) ? $obj->success = $txt : $obj->error = $txt;
        return $obj; 
    }
    
    private $supported_image = array(
            'gif',
            'jpg',
            'jpeg',
            'png'
    );

    private function generateRandomString($length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
?>
