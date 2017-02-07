<?php

namespace MainApp\Controller;

use MainApp\Service\CRUDService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AppController
{
    
    protected $crudService;
    
    private $per_page = 3;
    
    private $mlog;
    
    public function __construct( CRUDService $CRUDService ) {
        $this->crudService = $CRUDService;
        $this->toJSON = $JsonResponse;
        // $this->mlog = $monolog;
    }
    
    public function getAll() {

        $result = $this->crudService->fetchAll();
        return new JsonResponse(array('all'=>$result));
        
    }
    
    public function getPage( $num ) {
        
        $begin  = $this->getStartPage( $num );
        $result = $this->crudService->getPage( $begin, $this->per_page);
        
        return new JsonResponse($result);
        
    }
    
    public function addPerson(Request $request) {
        
        $f_n = $request->get('f');
        $l_n = $request->get('l');
        $m_n = $request->get('m');
        $response_err = 'Please provide all required fields';
        if (is_null($f_n)||is_null($l_n)||is_null($m_n)) return new JsonResponse($response_err ,201);
        
        $result = $this->crudService->addPerson($f_n, $l_n, $m_n);
        
        return new JsonResponse($result, 200);
        
    }
    
    protected function getStartPage ($cur_page){
        if ( $cur_page < 1 ) $cur_page = 1;
        return ($cur_page - 1) * $this->per_page;
    }
    
}