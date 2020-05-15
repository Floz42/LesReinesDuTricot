<?php
namespace App\Security;

use App\Entity\User as AppUser;
use App\Exception\LoginException;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface 
{
    private $request;
    private $session;
    private $repo;

    public function __construct(RequestStack $request, UserRepository $repo, SessionInterface $session)
    {
        $this->request = $request;
        $this->repo = $repo;
        $this->session = $session;

    }

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        $username = $this->request->getCurrentRequest()->get("_username");
        $userObject = $this->repo->findOneBy(["username" => $username]);
        $userStatus = $userObject->getTokenUser()->getStatus();
        
        if ($userStatus !== "verified") {
            if ($userStatus === "pending") {
                throw new LoginException('Vous devez valider votre compte avec le lien fournit par e-mail avant de vous connecter.');
            }
            if ($userStatus === "password_lost") {
                throw new LoginException('Vous devez réinitialiser votre mot de passe en suivant les instructions présentes dans le mail.');
            }
        } 
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

    }
}