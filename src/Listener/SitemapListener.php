<?php

namespace App\Listener;

use App\Entity\BlogPost;
use App\Entity\Page;
use Doctrine\ORM\EntityManagerInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\GoogleImage;
use Presta\SitemapBundle\Sitemap\Url\GoogleImageUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\GoogleVideo;
use Presta\SitemapBundle\Sitemap\Url\GoogleVideoUrlDecorator;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Yann Eugoné <yeugone@prestaconcept.net>
 */
class SitemapListener
{
    private EntityManagerInterface $doctrine;

    private UrlGeneratorInterface $router;

    public function __construct(EntityManagerInterface $doctrine, UrlGeneratorInterface $router)
    {
        $this->doctrine = $doctrine;
        $this->router = $router;
    }

    #[AsEventListener(event: SitemapPopulateEvent::class)]
    public function populateSitemap(SitemapPopulateEvent $event): void
    {
        if (\in_array($event->getSection(), ['default', null], true)) {
            $this->registerPages($event->getUrlContainer());
        }
        if (\in_array($event->getSection(), ['blog', null], true)) {
            $this->registerBlogPosts($event->getUrlContainer());
        }
    }

    private function registerPages(UrlContainerInterface $urlContainer): void
    {
        $pages = $this->doctrine->getRepository(Page::class)->findAll();

        /** @var Page $page */
        foreach ($pages as $page) {
            $urlContainer->addUrl(
                $this->url(
                    'page',
                    ['slug' => $page->getSlug()],
                ),
                'default'
            );
        }
    }

    private function registerBlogPosts(UrlContainerInterface $urlContainer): void
    {
        $posts = $this->doctrine->getRepository(BlogPost::class)->findAll();

        /** @var BlogPost $post */
        foreach ($posts as $post) {
            $url = $this->url(
                'blog',
                ['slug' => $post->getSlug()]
            );

            if (count($post->getImages()) > 0) {
                $url = new GoogleImageUrlDecorator($url);
                foreach ($post->getImages() as $idx => $image) {
                    $url->addImage(
                        new GoogleImage($image, sprintf('%s - %d', $post->getTitle(), $idx + 1))
                    );
                }
            }

            if ($post->getVideo() !== null) {
                parse_str(parse_url($post->getVideo(), PHP_URL_QUERY), $parameters);
                $url = new GoogleVideoUrlDecorator($url);
                $url->addVideo(
                    $video = new GoogleVideo(
                        sprintf('https://img.youtube.com/vi/%s/0.jpg', $parameters['v']),
                        $post->getTitle(),
                        $post->getTitle(),
                        ['content_location' => $post->getVideo()]
                    )
                );
            }

            $urlContainer->addUrl($url, 'blog');
        }
    }

    private function url(string $route, array $parameters = []): UrlConcrete
    {
        return new UrlConcrete(
            $this->router->generate($route, $parameters, RouterInterface::ABSOLUTE_URL)
        );
    }
}
