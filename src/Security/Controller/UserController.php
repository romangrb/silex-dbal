<?php
namespace Security\Controller;

use Security\Service\UserService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Security\Entity\User;

class UserController
{
    protected $twig;

    protected $userService;

    public function __construct(\Twig_Environment $twig, UserService $userService) {
        $this->twig = $twig;
        $this->userService = $userService;
    }

    public function addAction(Request $request) {
        
        $user = new User();
        
        $firstname = $request->request->get("user")["firstname"];
        $lastname = $request->request->get("user")["lastname"];
        $email = $request->request->get("user")["email"];
        $password = $request->request->get("user")["password"];
        
        $user->setFirstname($firstname)->setLastname($lastname)->setEmail($email)->setPassword($password); 
        
        $this->userService->add($user);
        // TODO : Ajout du user en BDD via le service. Fixer la date à la date courante.
        return new RedirectResponse('/');
    }
}