<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use MainApp\Service\FsService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FsController
{
    
    private $crudService;
    
    private $FileHelper;
    
    const PER_PAGE = 3;
    
    public function __construct( CRUDService $CRUDService, FsService $FsService) {
        $this->crudService = $CRUDService;
        $this->FileHelper = $FsService;
        $this->toJSON = $JsonResponse;
    }
    
    public function addPicture(Request $request) {
        
        $file = $request->files->get('image');
        // 	upload the file and return the json with the data
    	return json_encode($this->FileHelper->writeFile($file));
    }
    
}