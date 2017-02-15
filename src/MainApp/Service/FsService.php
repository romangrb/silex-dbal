<?php
namespace MainApp\Service;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class FsService  {
    
    private $wr;
    
    private $supported_image_type = array(
        'gif',
        'jpg',
        'jpeg',
        'png'
    );
    
    private function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/fs.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('crud_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    public function __construct() {
       $this->init_logger();
    }
     
    public function writeFile($file, $request){
    	$this->wr->addInfo(json_encode($request->files->all(), true));
        // if post file data is null  than responce an error
    	if ($file == null) {
    		$responce = $this->statusHandler(false, "No image was definded");
    	 	return json_encode($responce);
    	}
    	
    
    	return;
        // get the file extension & make it lowercase
        $extension = strtolower($file->getClientOriginalExtension());
        // check if it is valid
        if(! in_array($extension,  $this->supported_image_type )){
            $responce = $this->statusHandler(false, "it is not a valid image type of : 'gif', 'jpg', 'jpeg', 'png' !");
            return json_encode($responce);
        }
        // add extension to name
        $name = $this->generateRandomString(10); $name .= '.'.$extension;
        // create the file
        $file->move(__DIR__.'/upload', $name);
        // return success message
        $responce = $this->statusHandler(true, "Image successfully uploaded !  " . $name);
        
        return $responce;
    }
    
    private function statusHandler($is_success, $txt){
        $obj=new \stdClass();
        ($is_success) ? $obj->success = $txt : $obj->error = $txt;
        return $obj; 
    }
    
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
