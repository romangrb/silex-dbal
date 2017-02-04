<?php
namespace Blog\Controller;

use Blog\Service\MessageService;

class BlogController
{
    
    protected $twig;

    protected $messageService;

    public function __construct(\Twig_Environment $twig, MessageService $messageService) {

        $this->twig = $twig;
        $this->messageService = $messageService;
    }

    public function homeAction() {
        $messages = $this->messageService->fetchAll();

        return $this->twig->render('Blog/home.twig.html', array(
            'messages' => $messages,
        ));
    }
}