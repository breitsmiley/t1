<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 10; $i++) {
            $post= new Post();
            $post->setTitle('Title # ' . $i)
                ->setDescription('Description #' . $i)
                ->setText('TEXT TEXT TEXT TEXT #' . $i);

            $manager->persist($post);
        }

        $manager->flush();
    }
}