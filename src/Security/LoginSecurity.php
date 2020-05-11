<?php

namespace App\Security;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LoginSecurity 
{

    private $request;
    private $service;

    public function __construct(RequestStack $request, UserService $service)
    {
        $this->request = $request;
        $this->service = $service;
    }

    public function getErrorsAccount()
    {
        $user = $this->service->verifyPseudInDatabase($this->request->getCurrentRequest());
        $statusAccount = $user->getTokenUser()->getStatus(); 
        if ($statusAccount === "pending") {
            $errorStatus = "Avant de vous connecter, vous devez valider votre inscription grâce au lien envoyé par e-mail";
        } else if ($statusAccount === "password_lost") {
            $errorStatus = "Vous avez demandé a réinitialiser votre mot de passe, merci de suivre les indications que nous vous avons envoyé par e-mail.";
        }
        return $errorStatus;
    }
}