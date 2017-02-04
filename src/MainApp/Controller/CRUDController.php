<?php
namespace MainApp\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class CRUDController
{

    public function __construct() {
        
    }

    public function newAction() {
       
    }

    public function addAction(Request $request) {
        
        return new RedirectResponse('/');
    }

}