<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Notification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notification[]    findAll()
 * @method Notification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    // /**
    //  * @return Notification[] Returns an array of Notification objects
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
    public function findOneBySomeField($value): ?Notification
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param  \DateTime  $dateTime
     * @param  int|null  $amount
     * @return array|Notification[]
     */
    public function getPreparedForSend(?int $amount = null)
    {
        $queryBuilder = $this->createQueryBuilder('n')
            ->select('n,c, nf')
            ->join('n.confirmation', 'c')
            ->join('n.notifiables', 'nf')
            ->andWhere('n.status = :status')
            ->setParameter('status', Notification::STATUS_NEED_SEND)
            ->orderBy('n.createdAt', 'ASC');
        if ($amount) {
            $queryBuilder->setMaxResults($amount);
        }
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

}
