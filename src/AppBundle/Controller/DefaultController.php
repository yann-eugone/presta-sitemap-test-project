<?php

namespace Acme\AppBundle\Controller;

use Acme\AppBundle\Entity\BlogPost;
use Acme\AppBundle\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
