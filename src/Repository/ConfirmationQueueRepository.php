<?php

namespace App\Repository;

use App\Entity\ConfirmationQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConfirmationQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConfirmationQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConfirmationQueue[]    findAll()
 * @method ConfirmationQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfirmationQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConfirmationQueue::class);
    }

    // /**
    //  * @return ConfirmationQueue[] Returns an array of ConfirmationQueue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConfirmationQueue
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @param  \DateTime  $dateTime
     * @param  int|null  $amount
     * @return array|ConfirmationQueue[]
     */
    public function getPreparedForSend(\DateTime $dateTime, ?int $amount = null)
    {
        $queryBuilder = $this->createQueryBuilder('cq')
            ->select('cq, t')
            ->join('cq.tick', 't')
            ->where('cq.sendDateTime <= :dateTime')
            ->andWhere('cq.status = :status')
            ->setParameter('dateTime', $dateTime)
            ->setParameter('status', ConfirmationQueue::STATUS_NEW)
            ->orderBy('cq.sendDateTime', 'ASC');
        if ($amount) {
            $queryBuilder->setMaxResults($amount);
        }
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }

    public function getByScheduleId(int $scheduleId, ?int $status = null)
    {
        $queryBuilder = $this->createQueryBuilder('cq')
            ->select('cq')
            ->where('cq.schedule = :schedule')
            ->setParameter('schedule', $scheduleId);
        if (null !== $status) {
            $queryBuilder->andWhere('cq.status = :status')
                ->setParameter('status', $status);
        }
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();

        return $result;
    }
}
