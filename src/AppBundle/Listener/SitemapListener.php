<?php

namespace Acme\AppBundle\Listener;

use Acme\AppBundle\Entity\BlogPost;
use Acme\AppBundle\Entity\Page;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class SitemapListener implements EventSubscriberInterface
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
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'populateSitemap',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
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
            $url = new UrlConcrete(
                $this->router->generate(
                    'blog',
                    ['slug' => $post->getSlug()],
                    RouterInterface::ABSOLUTE_URL
                )
            );

            if (count($post->getImages()) > 0) {
                $url = new GoogleImageUrlDecorator($url);
                foreach ($post->getImages() as $idx => $image) {
                    $url->addImage(
                        new GoogleImage($image, sprintf('%s - %d', $post->getTitle(), $idx + 1))
                    );
                }
            }

            $urlContainer->addUrl($url, 'blog');
        }
    }
}
