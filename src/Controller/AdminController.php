<?php

namespace App\Controller;

use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/%app.admin_root_url%")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/blog/post/create", name="admin_blog_post_create")
     * @Method("GET")
     */
    public function createBlogPost()
    {
        return $this->render('backend/post/post_create.html.twig');
    }

    /**
     * @Route("/ajax/blog/post/create", name="admin_ajax_blog_post_create",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function createBlogPostAjax(Request $request, ValidatorInterface $validator)
    {
        $requestData = $request->request->all();

        $responseData = [
            'status' => false,
            'msg'    => 'Error',
        ];

        if (!isset($requestData['form'])) {
            $responseData['msg'] = "No key 'form'";
            return  $this->json($responseData);
        }
        $form = $requestData['form'];

        if (!isset($form['title'], $form['description'], $form['text'])) {
            $responseData['msg'] = "No Title, Text or Description";
            return  $this->json($responseData);
        }

        try {
            $post = new Post();
            $post->setTitle($form['title'])
                ->setDescription($form['description'])
                ->setText($form['text']);

            // Validation
            $errors = $validator->validate($post);

            if (count($errors) > 0) {
                $responseData['msg'] = (string) $errors;
                return  $this->json($responseData);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            $responseData['status'] = true;
            $responseData['msg'] = 'Successfully Saved #id ' . $post->getId();

        } catch (\Exception $e) {
            $responseData['msg'] = 'Exception - contact support';
        }

        return $this->json($responseData);
    }

    /**
     * @Route("/blog/post/delete", name="admin_blog_post_delete")
     * @Method("GET")
     */
    public function deleteBlogPost()
    {
        return $this->render('backend/post/post_delete.html.twig');
    }

    /**
     * @Route("/ajax/blog/post/delete/all", name="admin_ajax_blog_post_delete_all",
     *          condition="request.isXmlHttpRequest()"
     * )
     * @Method({"POST"})
     */
    public function deleteAllBlogPostsAjax()
    {
        $responseData = [
            'status' => false,
            'msg'    => 'Error',
        ];

        $postRepository = $this->getDoctrine()->getRepository(Post::class);

        try {
            $responseData['status']  = $postRepository->deleteAll();
            if ($responseData['status'] ) {
                $responseData['msg'] = 'Successfully Deleted';
            }
        } catch (\Exception $e) {
            $responseData['msg'] = 'Exception - contact support';
        }

        return  $this->json($responseData);
    }
}
