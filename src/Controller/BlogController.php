<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\BlogPostManager;
use App\Service\JsonResponseHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class BlogController extends Controller
{
    /**
     * @Route("/ajax/blog/posts/show", name="ajax_blog_posts_show",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function showBlogPostsAction(Request $request, JsonResponseHelper $jsonResponseHelper, BlogPostManager $blogPostManager)
    {
        $requestData = $request->request->all();

        $page = $requestData['page'] ?? 1;
        $page = intval($page);

        $response = $jsonResponseHelper->prepareJsonResponse();
        $renderData = $blogPostManager->getBlogPostsRenderData($page);

        $html = $this->renderView('frontend/post/post_list.html.twig', $renderData);

        return $response->setData([
            'html' => $html,
            'page' => $renderData['data']['page'] ?? 1,
        ]);

    }

    /**
     * @Route("/", name="show_all_posts")
     * @Method("GET")
     */
    public function showAllPostsAction()
    {
        return $this->render('frontend/post/index.html.twig');
    }

    /**
     * @Route("/post/{id}", requirements={"page": "[1-9]\d*"}, name="show_post")
     * @Method("GET")
     */
    public function showPostAction($id)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        return $this->render('frontend/post/single_post.html.twig', [
            'post' => $post
            ]
        );
    }
}
