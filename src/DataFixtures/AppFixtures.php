<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 11; $i++) {
            $datetime = new \DateTime();
            $datetime->sub(new \DateInterval("P{$i}D"));

            $post= new Post();
            $post->setTitle('Title # ' . $i)
                ->setDescription('Description #' . $i)
                ->setText('TEXT TEXT TEXT TEXT #' . $i)
                ->setCreatedAt($datetime);

            $manager->persist($post);
        }

        $manager->flush();
    }
}