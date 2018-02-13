<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage", options={"sitemap" = {"priority":1}})
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig', []);
    }

    /**
     * @return Response
     */
    public function xmlAction()
    {
        return BinaryFileResponse::create(
            $this->getParameter('kernel.project_dir').'/config/routes/application/xml.xml'
        );
    }

    /**
     * @return Response
     */
    public function yamlAction()
    {
        return BinaryFileResponse::create(
            $this->getParameter('kernel.project_dir').'/config/routes/application/yaml.yaml'
        );
    }

    /**
     * @Route("/{slug}", name="page")
     *
     * @param Page $page
     *
     * @return Response
     */
    public function pageAction(Page $page)
    {
        return $this->render('default/page.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/blog/{slug}", name="blog")
     *
     * @param BlogPost $post
     *
     * @return Response
     */
    public function blogAction(BlogPost $post)
    {
        return $this->render('default/blog.html.twig', ['post' => $post]);
    }
}
