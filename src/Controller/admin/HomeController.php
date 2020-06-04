<?php

namespace App\Controller\admin;

use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_home")
     */
    public function index(StatsService $statsService)
    {
        return $this->render('admin/home/index.html.twig', [
            'stats' => $statsService->getStats()
        ]);
    }
}