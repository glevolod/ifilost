<?php

namespace App\Repository;

use App\Entity\Notifiable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notifiable|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notifiable|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notifiable[]    findAll()
 * @method Notifiable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotifiableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notifiable::class);
    }

    // /**
    //  * @return Notifiable[] Returns an array of Notifiable objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Notifiable
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
