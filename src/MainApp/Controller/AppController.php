<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
{
    
    protected $crudService;
    
    public function __construct( CRUDService $CRUDService ) {
        $this->crudService = $CRUDService;
        $this->toJSON = $JsonResponse;
    }

    public function getAll() {

        $result = $this->crudService->fetchAll();
        return new JsonResponse(array('all'=>$result));
        
    }
    
    public function getTop() {
        
        $result = $this->crudService->getLimitRows();
        return new JsonResponse($result);
        
    }
    
    public function getPage( $num ) {
        
        $result = $this->crudService->getPage($num);
        return new JsonResponse($result);
        
    }
    
}