<?php
namespace MainApp\Controller;

use MainApp\Service\CRUDService;

class AppController
{
    
    protected $crudService;

    public function __construct(CRUDService $crudService) {
        $this->messageService = $crudService;
    }

    public function homeAction() {
        
        $messages = $this->messageService->fetchAll();
        return $messages;
        
    }
}