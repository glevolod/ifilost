<?php

namespace App\Repository;

use App\Entity\Tick;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Tick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tick[]    findAll()
 * @method Tick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tick::class);
    }

    // /**
    //  * @return Tick[] Returns an array of Tick objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Tick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Tick|null
     */
    public function findOneByUser(User $user): ?Tick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult()
            ;
    }
}
