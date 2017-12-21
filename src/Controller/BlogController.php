<?php

namespace App\Controller;

use App\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_homepage")
     */
    public function index()
    {
//        $em = $this->getDoctrine()->getManager();
//
//        $p = new Post();
//        $p->setDescription('Keyboard')
//            ->setText('text')
//            ->setTitle('Ergonomic and stylish!')
//            ->setDescription('sdfsfsdfsdf');
//
//        // tell Doctrine you want to (eventually) save the Product (no queries yet)
//        $em->persist($p);
//
//        // actually executes the queries (i.e. the INSERT query)
//        $em->flush();

        // replace this line with your own code!
        return $this->render('@Maker/demoPage.html.twig', [ 'path' => str_replace($this->getParameter('kernel.project_dir').'/', '', __FILE__) ]);
    }
}
