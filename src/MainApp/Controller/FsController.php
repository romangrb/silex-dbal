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
    
    private $rq_fields = array('f', 'l', 'm');
    
    private $FileHelper;
    
    public function __construct( CRUDService $CRUDService, FsService $FsService) {
        $this->crudService = $CRUDService;
        $this->FileHelper = $FsService;
        $this->toJSON = $JsonResponse;
    }
    
    private function checkRqAttrFields($request) {
        
        $rq_data = array();
        
        foreach ($this->rq_fields as $key) {
            if (is_null($request->get($key))) return;
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
    
    public function getPictures(Request $request){
        
        $persAttr = $this->checkRqAttrFields($request);
        
        if ( !$persAttr ) return new JsonResponse('Please provide all required fields', 500); 
        
        $persData = $this->crudService->getPerson($persAttr);
               
        if ( empty($persData) ) return new JsonResponse('Required person does not exist', 100); 
        if ( !$persData['dir'] || !$persData['profile_path'] ) return new JsonResponse('Required person data does not exist', 100);

        $photos_info = $this->FileHelper->getFiles($persData['profile_path'], $persData['dir']);
        return  new JsonResponse(array($photos_info), 200);
        // return  new JsonResponse(array('fs_info'=>$photos_info), 200);
        
    }
    
    public function addPictures(Request $request) {
        
        $persAttr = $this->checkRqAttrFields($request);
        
        if ( !$persAttr ) return new JsonResponse('Please provide all required fields', 500); 
        
        $result = $this->crudService->addPerson($persAttr);
        // check if db query executed without an error
        if ($result->is_failed) return new JsonResponse($result->txt, 500); 
        
        $files = $request->files->all();
        
        $photos_ln = count($files);
        
        if ( $photos_ln > self::MAX_FILES ) {
        	return json_encode('Limit of uploading files is ' . self::MAX_FILES . ' please try separate to several requests');
        }
        
        $photos = $this->filterFiles($files);
        
        $photos_info = $this->FileHelper->saveFiles($photos);
        
        if ( $photos_info->failed ) return new JsonResponse($photos_info, 400);
        
        // photos saved let's save name of directory where files is located to db
        $stat_upd_path = $this->crudService->addProfileFiles($result->id, $photos_info->file_path, $photos_info->dir);
        
        return ($stat_upd_path->failed)?  new JsonResponse(array('fs_info'=>$photos_info, 'db_info'=>$stat_upd_path), 400) : new JsonResponse(array('fs_info'=>$photos_info, 'db_info'=>$stat_upd_path), 200);

    }
    
    
    
}