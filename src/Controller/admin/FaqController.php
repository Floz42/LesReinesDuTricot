<?php

namespace App\Controller\admin;

use App\Entity\Faq;
use App\Form\FaqType;
use App\Repository\FaqRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FaqController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var FaqRepository
     */
    private $faqRepo;

    public function __construct(EntityManagerInterface $manager, FaqRepository $faqRepo)
    {
        $this->faqRepo = $faqRepo;
        $this->manager = $manager;
    }

    /**
     * @Route("/admin/faq/show", name="admin_faq_show")
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('/admin/faq/show.html.twig', [
            'faq' => $this->faqRepo->findAll()
        ]);
    }

    /**
     * @Route("/admin/faq/add", name="admin_faq_add")
     * @Route("/admin/faq/update/{id}", name="admin_faq_update")
     * @return Response
     */
    public function add_update(Faq $faq = null, Request $request): Response
    {
        $exist = true;
        $message = "Votre question/réponse a bien été modifiée";
        if (!$faq) {
            $exist = false;
            $message = "Votre question/réponse a bien été ajoutée";
            $faq = new Faq();
        }

        $form = $this->createForm(FaqType::class, $faq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->persist($faq);
            $this->manager->flush();
            $this->addFlash('success', $message);
            return $this->redirectToRoute('admin_faq_show');
        }

        return $this->render('/admin/faq/add.html.twig', [
            'form' => $form->createView(),
            'exist' => $exist
        ]);
    }

    /**
     * @Route("/admin/faq/delete/{id}", name="admin_faq_delete")
     * @return Response
     */
    public function delete(Faq $faq)
    {
        $this->manager->remove($faq);
        $this->manager->flush();
        $this->addFlash('success', 'Votre question/réponse a bien été supprimée.');

        return $this->redirectToRoute('admin_faq_show');
    }

}