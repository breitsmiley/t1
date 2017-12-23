<?php

namespace App\Controller;

use App\Entity\Post;
use App\Service\JsonResponseHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/%app.admin_root_url%")
 */
class AdminController extends Controller
{

    /**
     * @Route("/blog/post/create", name="admin_create_blog_post")
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
            'status' => false,
            'msg'    => 'Error',
        ];

        if (!isset($requestData['form'])) {
            $responseData['msg'] = "No key 'form'";
            return  $response->setData($responseData);
        }
        $form = $requestData['form'];

        if (!isset($form['title'], $form['description'], $form['text'])) {
            $responseData['msg'] = "No Title, Text or Description";
            return  $response->setData($responseData);
        }

        try {
            $post = new Post();
            $post->setTitle($form['title'])
                ->setDescription($form['description'])
                ->setText($form['text']);

            // Validation
            $validator = $this->get('validator');
            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                $responseData['msg'] = (string) $errors;
                return  $response->setData($responseData);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $responseData['status'] = true;
            $responseData['msg'] = 'Successfully Saved';

        } catch (\Exception $e) {
            $responseData['msg'] = 'Exception - contact support';
        }

        return $response->setData($responseData);
    }

    /**
     * @Route("/blog/post/delete", name="admin_delete_blog_post")
     * @Method("GET")
     */
    public function deleteBlogPostAction()
    {
        return $this->render('backend/post/post_delete.html.twig');
    }

    /**
     * @Route("/ajax/blog/post/delete-all", name="admin_ajax_delete_all_blog_post",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function ajaxDeleteAllBlogPostsAction(JsonResponseHelper $jsonResponseHelper)
    {
        $response = $jsonResponseHelper->prepareJsonResponse();

        $responseData = [
            'status' => false,
            'msg'    => 'Error',
        ];

        $postRepository = $this->getDoctrine()->getRepository('App:Post');

        try {
            $responseData['status']  = $postRepository->deleteAll();
            if ($responseData['status'] ) {
                $responseData['msg'] = 'Successfully Deleted';
            }
        } catch (\Exception $e) {
            $responseData['msg'] = 'Exception - contact support';
        }

        return  $response->setData($responseData);
    }
}
