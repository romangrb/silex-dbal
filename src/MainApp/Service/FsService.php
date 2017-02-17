<?php
namespace MainApp\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Finder\Finder;
use Chumper\Zipper\Zipper;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class FsService  {
    
    private $wr;
    
    private $fs;
    
    private $status;
    
    private $path;
    
    private $zipper;
    
    private $finder;
    
    private $supported_image_type = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    const UPLOAD_ROOT_NAME = '/upload/'; 
    
    private function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/fileSystem.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('crud_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    public function __construct() {
       $this->init_logger();
       $this->fs = new Filesystem();
       $this->status = $this->statusHandler();
       $this->zipper = new Zipper;
       $this->finder = new Finder();
    }
     
    public function saveFiles($files){
        
        $dir_name = mt_rand();
        $this->path = __DIR__ . self::UPLOAD_ROOT_NAME;
        $full_file_path = $this->path . $dir_name;
        
        $this->status->file_path = $this->path;
        $this->status->dir = $dir_name;
        
        try {
          $this->fs->mkdir($full_file_path);
        } catch (IOExceptionInterface $e) {
          $this->wr->addError('An error occurred while creating your directory at : ',  $e->getPath());
          $this->status->txt = 'Saving file error occured :' . $e->getMessage();
          $this->status->dir = null;
          $this->status->is_failed = true;
          return $this->status;
        }
       
        // create the directory
        foreach ($files as $key => $val) {
           // get ext or gues if is not exist of MIME type
           $ext = ($val->getClientOriginalExtension()) ? $val->getClientOriginalExtension() : $val->guessExtension;
           if(!in_array($ext, $this->supported_image_type )){
                $err = "File $name is not an image type supported, please change it and upload again. Supported types : $this->supported_image_type ";
                $this->status->txt = ( !$this->status->is_success )? $this->status->txt . $err : $err; 
                if ($this->status->is_success) $this->status->is_success = false;  
                continue;
           }
           
           $name = $key . '.' . $ext;
           
           try {
                $val->move($full_file_path, $name);
            } catch (IOExceptionInterface  $e) {
                $this->wr->addError('Saving file error : ',  $e->getMessage(), "\n");
                $err = "While saving $name file error occured :"  . $e->getMessage();
                $this->status->txt = ( !$this->status->is_success )? $this->status->txt . $err : $err; 
                if ($this->status->is_success) $this->status->is_success = false;
            }
        }
         
        if ($this->status->is_failed){
            try {
              if ($this->fs->exists($full_file_path)) $this->fs->remove($full_file_path);
            } catch (IOExceptionInterface $e) {
              $err = "An error occurred while removing directory at : ".  $e->getPath();
              $this->status->txt .= $err; 
              $this->wr->addError($err);
            }
        }
       
        return $this->status;
      
    }
    
    private function findFirstMachFile($prepare){
        $obj = new \stdClass();
        
        foreach ($prepare as $file) {
            if ($file) {
                $obj->file_name = $file->getRelativePathname();
                $obj->file_path = $file;
            break;    
            }
        }
        return $obj;
    }
    
    public function getFiles($path, $dir){
        
        $file_root = $path . $dir;
        
        try {
            if (!$this->fs->exists($file_root)) throw new Exception('Directory does not exist');
            
        } catch (\Exception $e) {
          $err = $e->getMessage() .  $e->getPath();
          $this->status->txt .= $err; 
          $this->wr->addError($err);
          return $this->status; 
        }
        
        $this->finder->files()->in($file_root);
        $this->finder->files()->name('*.zip');
        $file_prop = $this->findFirstMachFile($this->finder);
  
        if (is_null($file_prop->file_name)) {
            
            $file_prop->file_name = $dir . '.zip';
            $file_prop->file_path = $file_root . '/' . $file_prop->file_name;
                
            try {
              $this->zipper->make($file_prop->file_path)->add($file_root)->close();
            } catch (\Exception $e) {
              $err = $e->getMessage();
              $this->status->txt .= $err; 
              $this->wr->addError($err);
              return $this->status;
            }
        }
        
        $response = $this->getResponce($file_prop->file_path, $file_prop->file_name);
       
        return $response;
        
    }
    
    
    private function getResponce($filePath, $filename){
        
        try {
            $response = new BinaryFileResponse($filePath);
        } catch (\Exception $e) {
            $this->wr->addError($e);
            return $e->getMessage();
        }
        
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $filename,
            iconv('UTF-8', 'ASCII//TRANSLIT', $filename)
        );
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', filesize($filePath));
        $response->headers->set('Content-Type', 'application/zip');
         
        return $response;
    }
    

    private function statusHandler($is_success = true, $txt = ''){
        
        $obj = new \stdClass();
        $obj->is_success = $is_success;
        $obj->txt = $txt;
        $obj->file_path = null;
        $obj->dir = null;
        $obj->is_failed = false;
        
        return $obj; 
    }

}
?>
