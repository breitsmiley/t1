<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\JsonResponseHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/%app.admin_root_url%")
 */
class AdminController extends Controller
{

    /**
     * @Route("", name="admin_create_blog_post")
     * @Method("GET")
     */
    public function createBlogPostAction()
    {
        return $this->render('backend/post/post_create.html.twig');
    }

    /**
     * @Route("/ajax/blog/post/create", name="admin_ajax_create_blog_post",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function ajaxCreateBlogPostAction(Request $request, JsonResponseHelper $jsonResponseHelper)
    {
        $response = $jsonResponseHelper->prepareJsonResponse();
        $requestData = $request->request->all();

        $responseData = [
            'status'  => false,
            'error' => 'Error',
        ];

        if (!isset($requestData['form'])) {
            $responseData['error'] = "No key 'form'";
            return  $response->setData($responseData);
        }
        $form = $requestData['form'];

        if (!isset($form['title'], $form['description'], $form['text'])) {
            $responseData['error'] = "No Title, Text or Description";
            return  $response->setData($responseData);
        }

        try {
            $post = new Post();
            $post->setTitle($form['title'])
                ->setDescription($form['description'])
                ->setText($form['text']);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

        } catch (\Exception $e) {
            $responseData['error'] = 'Exception - contact support';
            return  $response->setData($responseData);
        }


        $response->setData([
            'status'  => true,
        ]);

        return $response;
    }
}
