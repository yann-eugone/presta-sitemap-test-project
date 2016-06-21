<?php

namespace Acme\AppBundle\Listener;

use Acme\AppBundle\Entity\BlogPost;
use Acme\AppBundle\Entity\Page;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\SitemapListenerInterface;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class SitemapListener implements SitemapListenerInterface
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RegistryInterface $doctrine
     * @param RouterInterface   $router
     */
    public function __construct(RegistryInterface $doctrine, RouterInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
    }

    /**
     * @inheritdoc
     */
    public function populateSitemap(SitemapPopulateEvent $event)
    {
        $this->registerPages($event->getUrlContainer());
        $this->registerBlogPosts($event->getUrlContainer());
    }

    /**
     * @param UrlContainerInterface $urlContainer
     */
    private function registerPages(UrlContainerInterface $urlContainer)
    {
        $pages = $this->doctrine->getManager()->getRepository(Page::class)->findAll();

        foreach ($pages as $page) {
            $urlContainer->addUrl(
                new UrlConcrete(
                    $this->router->generate(
                        'page',
                        ['slug' => $page->getSlug()],
                        RouterInterface::ABSOLUTE_URL
                    )
                ),
                'default'
            );
        }
    }

    /**
     * @param UrlContainerInterface $urlContainer
     */
    private function registerBlogPosts(UrlContainerInterface $urlContainer)
    {
        $posts = $this->doctrine->getManager()->getRepository(BlogPost::class)->findAll();

        foreach ($posts as $post) {
            $urlContainer->addUrl(
                new UrlConcrete(
                    $this->router->generate(
                        'blog',
                        ['slug' => $post->getSlug()],
                        RouterInterface::ABSOLUTE_URL
                    )
                ),
                'blog'
            );
        }
    }
}
