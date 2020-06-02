<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserUpdateType;
use App\Entity\UpdatePassword;
use App\Form\ChangePasswordType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/user/profile/", name="user_profile")
     * @IsGranted("ROLE_USER")
     *       
     * @return Response
     */
    public function profile(): Response
    {
        return $this->render('partials/users/profile.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/user/update/", name="update_profile")
     * @isGranted("ROLE_USER")
     * 
     * @param User $user
     * @param Request $request
     * 
     * @return Response
     */
    public function update_profile(Request $request): Response 
    {
        $user = new User;
        $user = $this->getUser();
        
        $form = $this->createForm(UserUpdateType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();
            $this->addFlash("success", "Votre profil a été modifié avec succès.");
            return $this->redirectToRoute('user_profile');
        }

        return $this->render('partials/users/updateProfile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/update/password/new", name="change_password")
     * @isGranted("ROLE_USER")
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * 
     * @return Response
     */
    public function change_password(UserPasswordEncoderInterface $encoder, Request $request): Response
    {
        $user = $this->getUser();

        $newPassword = new UpdatePassword;

        $form = $this->createForm(ChangePasswordType::class, $newPassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($newPassword->getOldPassword(), $user->getPassword())) {
                $form->get("oldPassword")->addError(new FormError("Le mot de passe acutel n'est pas le bon ..."));
            } else {
            $password = $newPassword->getNewPassword();
                $hash = $encoder->encodePassword($user, $password);
                $user->setPassword($hash);

                $this->manager->persist($user);
                $this->manager->flush();
                $this->addFlash("success", "Votre mot de passe a bien été mis à jour.");
                return $this->redirectToRoute('user_profile');
            }
        }

        return $this->render('partials/users/changePassword.html.twig', [
            'form' => $form->createView()
        ]);
    }
}