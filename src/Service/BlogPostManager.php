<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BlogPostManager
{
    const ERROR_NO_POSTS = 'There aren\'t any posts';

    private $sc;
    private $em;
    private $postPerPage;
    private $postRepository;

    public function __construct(ContainerInterface $sc, EntityManagerInterface $em)
    {
        $this->sc = $sc;
        $this->em = $em;
        $this->postPerPage = $sc->getParameter('app.blog_posts_per_page');
        $this->postRepository = $em->getRepository('App:Post');
    }

    public function getBlogPostsRenderData($page): array
    {
        $requestedPage = $page;

        $posts = $this->postRepository->getPaginatedLatestPosts($requestedPage, $this->postPerPage);
        $postsCount = $posts->count();

        if (empty($postsCount)) {
            $requestedPage = 1;
            $posts = $this->postRepository->getPaginatedLatestPosts($requestedPage, $this->postPerPage);
            $postsCount = $posts->count();
        }

        $renderData = [
            'status' => false,
            'data'   => [],
            'error' => '',
        ];

        if ($postsCount > 0) {
            $renderData['status'] = true;
            $renderData['data'] = [
                'posts'      => $posts,
                'count'      => $postsCount,
                'page'       => $requestedPage,
                'isNextPage' => $postsCount > ($this->postPerPage * $requestedPage),
                'isPrevPage' => $requestedPage != 1,
            ];

        } else {
            $renderData['error'] = self::ERROR_NO_POSTS;
        }

        return $renderData;
    }
}
