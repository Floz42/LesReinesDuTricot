<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\TokenUser;
use App\Entity\ImageProfile;
use App\Service\UserService;
use App\Security\UserChecker;
use App\Entity\UpdatePassword;
use App\Service\MailerService;
use App\Service\SessionService;
use App\Form\UpdatePasswordType;
use App\Exception\LoginException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserRepository $userRepo
     */
    private $userRepo;

    private $sessionI;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepo, SessionService $session, SessionInterface $sessionI)
    {
        $this->manager = $manager;
        $this->userRepo = $userRepo;
        $this->session = $session;
        $session->setSession();
        $this->sessionI = $sessionI;
    }

    /**
     * @Route("/user/subscribe", name="user_subscribe")
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param UserService $userService
     * @param MailerService $mailer
     * 
     * @return Response
     */
    public function subscribe(Request $request, UserPasswordEncoderInterface $encoder, UserService $userService, MailerService $mailer): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = new TokenUser;
            $token->setUserId($user)
                  ->setStatus("pending")
                  ->setToken($userService->randomString(35));
            $this->manager->persist($token);
            $imageProfile = new ImageProfile();
            $imageProfile->setUser($user);
            $this->manager->persist($imageProfile);
            $passwordCrypt = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordCrypt);
            $this->manager->persist($user);
            $this->manager->flush();

            $mailer->sendMail(
                "LesReinesDuTricot - Confirmation Inscription",
                $user->getEmail(),
                $this->renderView('partials/mails/verifSubscribe.html.twig', [
                    'token' => $token->getToken(),
                    'userEmail' => $user->getEmail(),
                    'host' => TokenUser::HOST
                    ])
            );

            $this->addFlash('success', "Votre inscription a bien été prise en compte, il vous suffit maintenant de la valider grâce au lien présent dans votre boîte mail.");
            return $this->redirectToRoute('login');
        }

        return $this->render('partials/users/subscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/confirm", name="user_valid_account")
     * 
     * @param UserService $userService
     */
    public function validAccount(UserService $userService):Response
    {

        $confirm = $userService->verifyEmail();
        if ($confirm === "already_verified") {
            $this->addFlash("success", "Votre compte a déjà été validé, vous pouvez vous connecter.");
        } elseif ($confirm === "verified") {
            $this->addFlash("success", "Votre compte a été activé avec succès, vous pouvez maintenant vous connecter.");        
        } elseif ($confirm ==="error") {
            $this->addFlash("error", "Il y a eut une erreur lors de la confirmation, merci de contacter le support"); 
        }

        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/login", name="login")
     * 
     * @param AuthenticationUtils $utils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils): Response
    {   
        $errors = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render('partials/users/login.html.twig', [
            'errors' => $errors !== null,
            'lastUsername' => $lastUsername,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     * 
     * @return void
     */
    public function logout(): void
    {

    }

    /**
     * @Route("/password_lost", name="password_lost")
     * 
     * @param UserService $userService
     * @param Request $request
     * @param MailerService $mailer
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return Response
     */
    public function lostPassword(UserService $userService, Request $request, MailerService $mailer, UserPasswordEncoderInterface $encoder): Response
    {
        if ($request->isMethod("POST")) {
            $email = $request->get("email");
            if ($email !== null) {
                $user = $this->userRepo->findOneBy(["email" => $email]);
            }
            if (empty($user)) {
                $this->addFlash("error", "Cet e-mail n'existe pas.");
            } else {
                $rescueToken = $userService->randomString(8);
                $encodeRescueToken = $encoder->encodePassword($user, $rescueToken);
                $user->getTokenUser()->setStatus("password_lost");
                $user->getTokenUser()->setRescueToken($encodeRescueToken);
                $this->manager->flush();
                $this->addFlash('success', "Un e-mail vous a été envoyé afin de réinitialiser votre mot de passe");

                $mailer->sendMail(
                    "LesReinesDuTricot - Mot de passe perdu",
                    $user->getEmail(),
                    $this->renderView('partials/mails/lostPassword.html.twig', [
                        'token' => $rescueToken,
                        'userEmail' => $user->getEmail(),
                        'host' => TokenUser::HOST
                        ])
                    );
                }
            }

        return $this->render('partials/users/passwordLost.html.twig');
    }

    /**
     * @Route("/user/updatePassword/confirm", name="user_update_password")
     * 
     * @param Request $request
     * @param UserService $userService
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return Response
     */
    public function updatePassword(Request $request, UserService $userService, UserPasswordEncoderInterface $encoder): Response
    {
        $updatePassword = new UpdatePassword(); 

         $arrayVerify = $userService->verificationsBeforeUpdatePassword();
         extract($arrayVerify);
         if ($verify) {
            $form = $this->createForm(UpdatePasswordType::class, $updatePassword);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if (!(password_verify($updatePassword->getVerificationCode(), $user->getTokenUser()->getRescueToken()))) {
                    $this->addFlash("error", "Le code de validation n'est pas le bon ...");
                    return $this->redirect($request->getUri());
                }
                $passwordCrypt = $encoder->encodePassword($user, $updatePassword->getNewPassword());
                $user->setPassword($passwordCrypt);
                $user->getTokenUser()->setRescueToken(NULL);
                $user->getTokenUser()->setStatus("verified");
                $this->manager->persist($user);
                $this->manager->flush();
                $this->addFlash("success", "Votre mot de passe a bien été mis à jour, vous pouvez maintenant vous connecter");
                return $this->redirectToRoute('home');
            }

            return $this->render('partials/users/updatePassword.html.twig', [
                'form' => $form->createView()
            ]);
         }
         $this->addFlash("error", "ERREUR : Vous avez déjà réinitialisé votre mot de passe.");
         return $this->redirectToRoute('home');

    }

    /**
     * @Route("/user/username_lost", name="username_lost")
     * 
     * @param Request $request
     * @param UserService $userService
     * @param \Swift_Mailer $mailer
     * 
     * @return Response
     */
    public function lostUsername(Request $request, UserService $userService, MailerService $mailer): Response
    {
        if ($request->isMethod("POST")) {
            $email = $request->get('email');

            $user = $userService->verifyMailInDatabase($email);

            if (!$user) {
                $this->addFlash("error", "Ce nom d'utilisateur n'existe pas.");
                return $this->redirect($request->getUri());
            } else {
                $mailer->sendMail(
                    "LesReinesDuTricot - Pseudo perdu",
                    $email,
                    $this->renderView('partials/mails/lostUsername.html.twig', [
                        'username' => $user->getUsername(),
                        'host' => TokenUser::HOST
                        ])
                    );
                $this->addFlash("success", "Votre pseudo vient de vous être envoyé par e-mail :)");
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('/partials/users/usernameLost.html.twig');
    }

    
}