<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
{
    
    private $crudService;
    
    private $rq_fields = array('f', 'l', 'm');
    
    const PER_PAGE = 3;
    
    public function __construct( CRUDService $CRUDService ) {
        $this->crudService = $CRUDService;
        $this->toJSON = $JsonResponse;
    }
    
    public function getAll() {
        $result = $this->crudService->fetchAll();
        return new JsonResponse($result, 200);
    }
    
    public function getPage( $num ) {
        
        $begin  = $this->getStartPage( $num );
        $result = $this->crudService->getPersonPage( $begin, self::PER_PAGE);
        
        return new JsonResponse($result);
        
    }
    
    public function addPerson(Request $request) {
        $rq_data = array();
        foreach ($this->rq_fields as $key) {
            if (is_null($request->get($key))) return new JsonResponse('Please provide all required fields' ,201);
            $rq_data[$key] = $request->get($key);
        }
        
        $result = $this->crudService->addPerson($rq_data);
        return new JsonResponse($result, 200);
        
    }
    
    protected function getStartPage ($cur_page){
        if ( $cur_page < 1 ) $cur_page = 1;
        return ($cur_page - 1) * self::PER_PAGE;
    }
    
}