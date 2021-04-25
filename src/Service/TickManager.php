<?php


namespace App\Service;


use App\Entity\TickQueue;
use App\Repository\TickQueueRepository;
use Doctrine\ORM\EntityManagerInterface;

class TickManager
{
    private TickQueueRepository $tickQueueRepository;
    private EntityManagerInterface $entityManager;

    /**
     * TickManager constructor.
     * @param  TickQueueRepository  $tickQueueRepository
     * @param  EntityManagerInterface  $entityManager
     */
    public function __construct(TickQueueRepository $tickQueueRepository, EntityManagerInterface $entityManager)
    {
        $this->tickQueueRepository = $tickQueueRepository;
        $this->entityManager = $entityManager;
    }


    public function runTicks(\DateTimeInterface $startDate, ?int $amount = null): int
    {
        $tickQueues = $this->tickQueueRepository->getPreparedForRun($startDate, $amount);

        $runAmount = count($tickQueues);
        foreach ($tickQueues as $tickQueue) {
            $this->runTick($tickQueue);
        }

        return $runAmount;
    }

    public function runTick(TickQueue $tickQueue): bool
    {
        echo $tickQueue->getId();
        echo "\n";

        return true;
    }
}