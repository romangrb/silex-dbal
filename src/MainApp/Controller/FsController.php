<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use MainApp\Service\FsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FsController
{
    
    const MAX_FILES = 10;
    
    const MAX_FILE_SIZE = 3;
    
    private $rq_fields = array('f', 'l', 'm', 'files_num');
    
    private $FileHelper;
    
    public function __construct( CRUDService $CRUDService, FsService $FsService) {
        $this->crudService = $CRUDService;
        $this->FileHelper = $FsService;
        $this->toJSON = $JsonResponse;
    }
    
    private function checkRqAttrFields($request) {
        
        $rq_data = array();
        
        foreach ($this->rq_fields as $key) {
            if (is_null($request->get($key))) return new JsonResponse('Please provide all required fields', 201);
            $rq_data[$key] = $request->get($key);
        }
        
        return $rq_data;
    }
    
    private function filterFiles($files) {
        $rq_data = array();
        
        foreach ($files as $key => $val) {
           if (is_numeric($key)) $rq_data[$key] = $val;
        }
        
        return $rq_data;
       
    }
    
    private function validateFiles($request) {
        
       
    }
    
    public function addPicture(Request $request) {
        
        $persAttr = $this->checkRqAttrFields($request);
        
        $files = $request->files->all();
        
        $photos_ln = count($files);
        
        if ( $photos_ln > self::MAX_FILES ) {
        	return json_encode('Limit of uploading files is ' . self::MAX_FILES . ' please try separate to several requests');
        }
        
        $photos = $this->filterFiles($files);
        
        // $this->validateFiles();
        // 	upload the file and return the json with the data
    	return json_encode($this->FileHelper->saveFiles($photos));
    }
    
    
    
}