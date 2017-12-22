<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\AbstractQuery;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param int $page
     * @param int $limit
     * @return Paginator|Post[]
     */
    public function getPaginatedLatestPosts(int $page, int $limit = 0): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->addOrderBy('p.createdAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit);

        if ($limit != -1) {
            $qb->setMaxResults($limit);
        }

        $query = $qb->getQuery();
        $query->setHydrationMode(AbstractQuery::HYDRATE_OBJECT);

        $pagination = new Paginator($query, false);
        $pagination->setUseOutputWalkers(false);

        return $pagination;
    }
}
