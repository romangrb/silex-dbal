<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class FsController
{
    
    private $crudService;
    
    private $wr;
    
    protected function init_logger(){
        
        $stream = new StreamHandler(__DIR__.'/fs.log', Logger::DEBUG);
        $firephp = new FirePHPHandler();
        $this->wr = new Logger('upload_logger');
        $this->wr->pushHandler($stream);
        $this->wr->pushHandler($firephp);
        
    }
    
    const PER_PAGE = 3;
    
    public function __construct( CRUDService $CRUDService ) {
        $this->crudService = $CRUDService;
        $this->toJSON = $JsonResponse;
        $this->init_logger();
    }
    
    public function addPicture(Request $request) {
        
        $file = $request->files->get('image');
    	// if null return error
    // 	if ($file==null) {
    // 		$obj=new \stdClass();
    // 		$obj->success=false;
    // 		$obj->error="No image provided";
    // // 	 	return json_encode($obj);
    // 	}
    	// upload the file and return the json with the data
    // 	return json_encode(FileHelper::writeFile($file));
        $this->wr->addInfo($request->files->get('image'));
    // 	$this->wr->addInfo(json_encode(array('file'=>$file)));
    	return json_encode($file);
        
    }
    
}