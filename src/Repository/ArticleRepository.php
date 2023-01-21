<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllByDesc()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.published_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLatest()
    {
        return $this->createQueryBuilder('a')
            ->setMaxResults(3)
            ->orderBy('a.published_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findBySearch(?string $query)
    {
        return $this->createQueryBuilder('a')
            ->orWhere('a.title LIKE :val')
            ->orWhere('a.summary LIKE :val')
            ->orWhere('a.content LIKE :val')
            ->setParameter('val', "%{$query}%")
            ->orderBy('a.published_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
