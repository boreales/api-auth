<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class LogoutListener implements LogoutHandlerInterface {
    protected $userManager;

    public function __construct(UserManagerInterface $userManager){
        $this->userManager = $userManager;
    }

    public function logout(Request $request, Response $response, TokenInterface $token) {
      var_dump($token);
      //die();
    }
}
