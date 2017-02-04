<?php
namespace Blog\Controller;

use Blog\Service\MessageService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Blog\Entity\Message;

class MessageController
{
    protected $twig;

    protected $messageService;

    public function __construct(\Twig_Environment $twig, MessageService $messageService) {
        $this->twig = $twig;
        $this->messageService = $messageService;
    }

    public function newAction() {
        return $this->twig->render('Blog/new-message.twig.html');
    }

    public function addAction(Request $request) {
        
        $message = new Message();
        
        $txt = $request->request->get("message")["text"];
        $date = new \DateTime();
        $date = $date ->format('Y-m-d H:i:s');
        
        $message->setText($txt)->setDate($date); 
        
        $this->messageService->add($message);
        
        return new RedirectResponse('/');
    }

    public function editAction($id) {
        $message = $this->messageService->fetch($id);
       
        // TODO : Récupération du message à éditer.
        return $this->twig->render('Blog/edit-message.twig.html', array('message' => $message));
    }

    public function updateAction(Request $request) {
        $message = new Message();
        
        $id = $request->request->get("message")["id"];
        $txt = $request->request->get("message")["text"];
        $date = new \DateTime();
        $date = $date ->format('Y-m-d H:i:s');
        
        $message->setId($id)->setText($txt)->setDate($date); 
        
        $this->messageService->update($message);
        
        // TODO : Modification du message en BDD via le service. Fixer la date à la date courante.
        return new RedirectResponse('/');
    }
    public function deleteAction($id) {
        
        $this->messageService->delete($id);
        // TODO : Suppression du message en BDD via le service.
        return new RedirectResponse('/');
    }
}