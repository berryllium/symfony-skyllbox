<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
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

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getUserArticles($user_id){
        return $this->createQueryBuilder('a')
            ->andWhere('a.author = :user')
            ->setParameter('user', $user_id)
        ;
    }

    /**
     * Последняя статья
     */
    public function getLastUserArticles($user_id){
        return $this->createQueryBuilder('a')
            ->andWhere('a.author = :user')
            ->setParameter('user', $user_id)
            ->setMaxResults(1)
            ->orderBy('a.id', 'desc')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Сколько статей создано пользователем
     */
    public function getCountUserArticles($user_id){
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.author)')
            ->andWhere('a.author = :user_id')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * Сколько статей создано пользователем начиная с даты
     */
    public function getCountUserArticlesFromDate(int $user_id, \DateTime $date){
        return $this->createQueryBuilder('a')
            ->select('COUNT(a.author)')
            ->andWhere('a.author = :user_id')
            ->andWhere('a.createdAt > :date')
            ->setParameter('user_id', $user_id)
            ->setParameter('date', $date)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }


}
