<?php


namespace App\Service;


use App\Entity\Confirmation;
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


    public function checkConfirmations(\DateTimeInterface $date, ?int $amount = null): int
    {
        $confirmations = $this->confirmationRepository->getMissed($date, $amount);

        $missedAmount = count($confirmations);
        foreach ($confirmations as $confirmation) {
            $confirmation->setStatus(Confirmation::STATUS_MISSED);
        }
        $this->entityManager->flush();

        return $missedAmount;
    }
}