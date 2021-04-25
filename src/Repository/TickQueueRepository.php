<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\TickQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TickQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TickQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TickQueue[]    findAll()
 * @method TickQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TickQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TickQueue::class);
    }

    // /**
    //  * @return TickQueue[] Returns an array of TickQueue objects
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
    public function findOneBySomeField($value): ?TickQueue
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
     * @param  \DateTimeInterface  $startDate
     * @param  int|null  $amount
     * @return array|TickQueue[]
     */
    public function getPreparedForRun(\DateTimeInterface $startDate, ?int $amount = null)
    {
        $queryBuilder = $this->createQueryBuilder('tq')
            ->select('tq, t')
            ->join('tq.tick', 't')
            ->where('tq.startDateTime <= :dateTime')
            ->andWhere('tq.status = :status')
            ->setParameter('dateTime', $startDate)
            ->setParameter('status', TickQueue::STATUS_NEW)
            ->orderBy('tq.startDateTime', 'DESC');
        if ($amount) {
            $queryBuilder->setMaxResults($amount);
        }
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }
}
