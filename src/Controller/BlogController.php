<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\BlogPostManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     */
    public function showPosts()
    {
        return $this->render('frontend/post/index.html.twig');
    }

    /**
     * @Route("/ajax/blog/posts/get/page", name="ajax_blog_posts_get_page",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function getPostsPageAjax(Request $request, BlogPostManager $blogPostManager)
    {
        $requestData = $request->request->all();

        $page = $requestData['page'] ?? 1;

        $renderData = $blogPostManager->getBlogPostsRenderData($page);

        $html = $this->renderView('frontend/post/post_list.html.twig', $renderData);

        $responseData = [
            'html' => $html,
            'page' => $renderData['data']['page'] ?? 1,
        ];

        return $this->json($responseData);
    }

    /**
     * @Route("/blog/post/{id}", requirements={"page": "[1-9]\d*"}, name="show_post")
     * @Method("GET")
     */
    public function showPost($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('frontend/post/post.html.twig', [
                'post' => $post
            ]
        );
    }
}
