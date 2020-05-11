<?php

namespace App\Service;

use Twig;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService 
{
    /**
     * @var String
     */
    private $entityClass;

    /**
     * @var Int
     */
    private $limit = 10;

    /**
     * @var Int
     */
    private $currentPage = 1;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var Twig\Environment
     */
    private $twig;

    /**
     * @var String
     */
    private $route;

    /**
     * @var String
     */
    private $templatePath;
    
    /**
     * __construct
     *
     * @param  EntityManagerInterface $manager
     * @param  Twig\Environment $twig
     * @param  RequestStack $request
     * @param  string $templatePath
     */
    public function __construct(EntityManagerInterface $manager, Twig\Environment $twig, RequestStack $request, string $templatePath)
    {
        $this->manager      = $manager;
        $this->route        = $request->getCurrentRequest()->attributes->get('_route');
        $this->twig         = $twig;
        $this->templatePath = $templatePath;
    }
    
    /**
     * Show the render of navigation in the template twig
     *
     * @return void
     */
    public function display() 
    {
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }
    
    /**
     * getPages
     * @throws Exception -> si entityClass n'est pas définit. 
     * @return int
     */
    public function getPages(): int
    {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer. Utiliser setEntityClass()");
        }

        $total = count($this->manager
                        ->getRepository($this->entityClass)
                        ->findAll());
        return ceil($total / $this->limit);
    }
    
    /**
     * getData
     * @throws Exception -> si entityClass n'est pas définit. 
     * @return array
     */
    public function getData() {
        if (empty($this->entityClass)) {
            throw new \Exception("Vous n'avez pas spécifié l'entité sur laquelle nous devons paginer. Utiliser setEntityClass()");
        }
        $offset = $this->currentPage * $this->limit - $this->limit;
        return $this->manager
                ->getRepository($this->entityClass)
                ->findBy([], [], $this->limit, $offset);
    }

    public function setCurrentPage(int $page): self
    {
        $this->currentPage = $page;
        return $this;
    }

    public function getCurrentPage(): int 
    {
        return $this->currentPage;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function setEntityClass(string $entityClass): self 
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass(): string 
    {
        return $this->entityClass;
    }

    public function setTemplatePath(string $templatePath): self 
    {
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath(): string 
    {
        return $this->templatePath;
    }

    public function setRoute(string $route): self 
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute(): string 
    {
        return $this->route;
    }
    
}