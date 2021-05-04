<?php


namespace App\Service;


use App\Entity\Confirmation;
use App\Entity\Notification;
use App\Entity\Schedule;
use App\Repository\ConfirmationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ConfirmationChecker
{
    private ConfirmationRepository $confirmationRepository;
    private EntityManagerInterface $entityManager;
    private AppMailer $appMailer;
    private LoggerInterface $logger;

    /**
     * ConfirmationChecker constructor.
     * @param  ConfirmationRepository  $confirmationRepository
     * @param  EntityManagerInterface  $entityManager
     * @param  AppMailer  $appMailer
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        ConfirmationRepository $confirmationRepository,
        EntityManagerInterface $entityManager,
        AppMailer $appMailer,
        LoggerInterface $logger
    ) {
        $this->confirmationRepository = $confirmationRepository;
        $this->entityManager = $entityManager;
        $this->appMailer = $appMailer;
        $this->logger = $logger;
    }


    public function markMissedConfirmations(\DateTime $date, ?int $amount = null): int
    {
        $confirmations = $this->confirmationRepository->getMissedByTime($date, $amount);

        $missedAmount = count($confirmations);
        foreach ($confirmations as $confirmation) {
            $confirmation->setStatus(Confirmation::STATUS_MISSED);
        }
        $this->entityManager->flush();

        return $missedAmount;
    }

    public function manageFailedConfirmation(?int $amount = null)
    {
        $confirmations = $this->confirmationRepository->getFailedNotNotified($amount);
        $failedAmount = count($confirmations);
        foreach ($confirmations as $confirmation) {
            $notification = (new Notification())
                ->setConfirmation($confirmation)
                ->setTypeConfirmationFailed()
                ->addNotifiables($confirmation->getUser()->getNotifiables());
            $confirmation->getQueue()->getSchedule()->setStatus(Schedule::STATUS_STOPPED_BY_SYSTEM);
            $this->entityManager->persist($notification);
        }
        $this->entityManager->flush();

        return $failedAmount;
    }
}