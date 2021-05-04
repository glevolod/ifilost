<?php


namespace App\Service;


use App\Entity\Confirmation;
use App\Entity\ConfirmationQueue;
use App\Repository\ConfirmationQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ConfirmationSender
{
    private ConfirmationQueueRepository $confirmationQueueRepository;
    private EntityManagerInterface $entityManager;
    private AppMailer $appMailer;
    private LoggerInterface $logger;

    /**
     * ConfirmationSender constructor.
     * @param  ConfirmationQueueRepository  $confirmationQueueRepository
     * @param  EntityManagerInterface  $entityManager
     * @param  AppMailer  $appMailer
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        ConfirmationQueueRepository $confirmationQueueRepository,
        EntityManagerInterface $entityManager,
        AppMailer $appMailer,
        LoggerInterface $logger
    ) {
        $this->confirmationQueueRepository = $confirmationQueueRepository;
        $this->entityManager = $entityManager;
        $this->appMailer = $appMailer;
        $this->logger = $logger;
    }


    public function sendConfirmations(\DateTime $startDate, ?int $amount = null): int
    {
        $confirmationQueues = $this->confirmationQueueRepository->getPreparedForSend($startDate, $amount);

        $sentAmount = 0;
        foreach ($confirmationQueues as $confirmationQueue) {
            try {
                $this->sendConfirmation($confirmationQueue);
                $sentAmount++;
            } catch (\Throwable $e) {
                $this->logger->error("Failed Confirmation sent: ".$confirmationQueue->getGuid());
            }
        }

        return $sentAmount;
    }

    public function sendConfirmation(ConfirmationQueue $confirmationQueue): void
    {
        $confirmation = new Confirmation();
        $schedule = $confirmationQueue->getSchedule();
        //todo: confirmation sendDateTime should be immutable
        $maxDateTime = $confirmationQueue->getSendDateTime()->modify("+ {$schedule->getTimeout()} minutes");
        $confirmation
            ->setQueue($confirmationQueue)
            ->setType(Confirmation::TYPE_FIRST_CONFIRMATION)
            ->setStatus(Confirmation::STATUS_WAITING)
            ->setMaxDateTime($maxDateTime)
            ->setUser($schedule->getUser());
        $confirmationQueue->setStatus(ConfirmationQueue::STATUS_SENT);
        $this->entityManager->persist($confirmation);
        $this->appMailer->sendConfirmation($confirmation);
        $this->entityManager->flush();
    }
}