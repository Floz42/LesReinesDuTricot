<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserService {

    private $request;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

            
    /**
     * __construct
     *
     * @param RequestStack $request
     * @param EntityManagerInterface $manager
     * @param UserRepository $userRepo
     * 
     * @return void
     */
    public function __construct(UserRepository $userRepo, EntityManagerInterface $manager, RequestStack $request)
    {
        $this->userRepo = $userRepo;
        $this->manager = $manager;
        $this->request = $request->getCurrentRequest();
    }

    /**
     * randomString to assign a token at user
     *
     * @return string
     */
    public function randomString($total): string
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $string = '';
        for($i=0; $i< $total; $i++){
            $string .= $chars[rand(0, strlen($chars)-1)];
        }
        return $string;
    }
    
    /**
     * verifyEmail -> verify if token in url correspond to the token user and if he hasn't validate his account
     *
     * @return string
     */
    public function verifyEmail()
    {
        $token = $this->request->query->get("token");
        $email = $this->request->query->get("user");

        $user = $this->userRepo->findOneBy(["email" => $email]);
        if($user->getTokenUser()->getStatus() === "verified") {
            return $status = "already_verified";
        } elseif(!empty($user) && $user->getTokenUser()->getToken() === $token && $user->getTokenUser()->getStatus() === "pending") {
            $user->getTokenUser()->setStatus("verified");
            $user->getTokenUser()->setToken("");
            $this->manager->flush();
            return $status = "verified";

        } else {
            return $status = "error";
        }
    }
    
    /**
     * extractUsersNewsLetter -> get all the users who submitted to the newsletter
     *
     * @return array|void
     */
    public function extractUsersNewsLetter()
    {
        $newsUsers = [];
        $users = $this->userRepo->findBy(['receiveNewsLetter' => true]);

        foreach($users as $user) {
            $newsUsers[] = $user->getEmail();
        }
        return $newsUsers;
    }
    
    /**
     * verifyMailInDatabase
     *
     * @param  string $email
     * @return array|void
     */
    public function verifyMailInDatabase(string $email)
    {
        $user = $this->userRepo->findOneBy(['email' => $email]);

        return $user;
    }

    /**
     * verifyPseudoInDatabase
     *
     * @param  string $username
     * @return array|void
     */
    public function verifyPseudInDatabase(string $username)
    {
        $user = $this->userRepo->findOneBy(['username' => $username]);

        return $user;
    }
    
    /**
     * toggleStatusAccount -> change the status account
     *
     * @param  mixed $email
     * @return string
     */
    public function setLost(string $email): string
    {
        $user = $this->userRepo->findOneBy(['email' => $email]);
        $userStatus = $user->getTokenUser()->getStatus();

        $status = ($userStatus === "verified") ? "lost_password" : "verified"; 

        return $status;
    }
    
    /**
     * verificationsBeforeUpdatePassword
     *
     * @return array
     */
    public function verificationsBeforeUpdatePassword(): array 
    {
        $token = $this->request->query->get("rescueToken");
        $email = $this->request->query->get("user");

        $user = $this->userRepo->findOneBy(["email" => $email]);
        $password = $user->getTokenUser()->getRescueToken();
        
        $verify = password_verify($token, $password);

        return compact("user", "verify");
    }
    
}