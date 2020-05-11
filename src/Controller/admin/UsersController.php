<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Service\UserService;
use App\Repository\UserRepository;
use App\Service\PaginationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserRepository
     */
    private $userRepo;
    
    /**
     * __construct
     *
     * @param  EntityManagerInterface $manager
     * @param  UserRepository $userRepo
     * @return void
     */
    public function __construct(EntityManagerInterface $manager, UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
        $this->manager = $manager;
    }
    
    /**
     * Show all users in databse
     * 
     * @Route("/admin/users/show/{page}", name="admin_users_show")
     *
     * @return Response
     */
    public function show(PaginationService $pagination, $page = 1): Response
    {
        $pagination->setEntityClass(User::class)
                   ->setCurrentPage($page);

        return $this->render('admin/users/show.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/admin/users/delete/{id}", name="admin_users_delete")
     * 
     * @param User $user
     * 
     * @return Response
     */
    public function delete(User $user): Response
    {
        $this->manager->remove($user);
        $this->manager->flush();
        $this->addFlash('success', 'L\'utilisateur a bien été supprimé.');

        return $this->redirectToRoute('admin_users_show');
    }

    /**
     * @Route("/admin/users/newsletter", name="admin_users_newsletter")
     * 
     * @param Request $request
     * @param UserService $userService
     * @param Newsletter $newsLetter
     * 
     * @return Response
     */
    public function sendNewsLetter(UserService $userService, Request $request, \Swift_Mailer $mailer): Response
    {
        $newsLetter = new Newsletter;
        $users = $userService->extractUsersNewsLetter();

        $form = $this->createForm(NewsletterType::class, $newsLetter);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            foreach($users as $key => $value) {
                $message = (new \Swift_Message())
                ->setSubject($form->get("title")->getData())
                ->setFrom(['flo.carreclub@gmail.com' => 'Flo Thiébaud'])
                ->setTo($value)
                ->setContentType('text/html')
                ->setBody($this->renderView('partials/mails/newsletter.html.twig', [
                    'message' => $form->get("message")->getData()
                    ]));
                $mailer->send($message);
            }
            return $this->redirectToRoute('home');
        }

        return $this->render('/admin/newsletter/index.html.twig', [
            'users' => $users,
            'form' => $form->createView()
        ]);
    }
}

