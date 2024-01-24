<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class DefaultController extends AbstractController
{
    #[Route(path: '/', name: 'homepage', options: ['sitemap' => ['priority' => 1]])]
    public function indexAction(): Response
    {
        return $this->render('default/index.html.twig', []);
    }

    public function xmlAction(): Response
    {
        return BinaryFileResponse::create(
            $this->getParameter('kernel.project_dir') . '/config/routes/application/xml.xml'
        );
    }

    public function yamlAction(): Response
    {
        return BinaryFileResponse::create(
            $this->getParameter('kernel.project_dir') . '/config/routes/application/yaml.yaml'
        );
    }

    #[Route(path: '/{slug}', name: 'page')]
    public function pageAction(Page $page): Response
    {
        return $this->render('default/page.html.twig', ['page' => $page]);
    }

    #[Route(path: '/blog/{slug}', name: 'blog')]
    public function blogAction(BlogPost $post): Response
    {
        return $this->render('default/blog.html.twig', ['post' => $post]);
    }
}
