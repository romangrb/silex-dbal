<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
{
    
    protected $crudService;
    
    private $per_page = 3;
    
    public function __construct( CRUDService $CRUDService ) {
        $this->crudService = $CRUDService;
        $this->toJSON = $JsonResponse;
    }
    
    public function getAll() {

        $result = $this->crudService->fetchAll();
        return new JsonResponse(array('all'=>$result));
        
    }
    
    protected function getStartPage ($cur_page){
        if ( $cur_page < 1 ) $cur_page = 1;
        return ($cur_page - 1) * $this->per_page;
    }
    
    public function getTop() {
        
        $result = $this->crudService->getLimitRows();
        return new JsonResponse($result);
        
    }
    
    public function getPage( $num ) {
        
        $begin  = $this->getStartPage( $num );
        $result = $this->crudService->getPage( $begin, $this->per_page );
        
        return new JsonResponse($result);
        
    }
    
}