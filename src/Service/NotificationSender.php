<?php


namespace App\Service;


use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class NotificationSender
{
    private NotificationRepository $notificationRepository;
    private EntityManagerInterface $entityManager;
    private AppMailer $appMailer;
    private LoggerInterface $logger;

    /**
     * NotificationSender constructor.
     * @param  NotificationRepository  $notificationRepository
     * @param  EntityManagerInterface  $entityManager
     * @param  AppMailer  $appMailer
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        NotificationRepository $notificationRepository,
        EntityManagerInterface $entityManager,
        AppMailer $appMailer,
        LoggerInterface $logger
    ) {
        $this->notificationRepository = $notificationRepository;
        $this->entityManager = $entityManager;
        $this->appMailer = $appMailer;
        $this->logger = $logger;
    }


    public function sendNotifications(?int $amount = null): int
    {
        $notifications = $this->notificationRepository->getPreparedForSend($amount);

        $sentAmount = 0;
        foreach ($notifications as $notification) {
            try {
                $this->sendNotification($notification);
                $notification->setStatusSent();
                $sentAmount++;
            } catch (\Throwable $e) {
                $this->logger->error("Failed Notification sent: ".$notification->getGuid());
            }
        }
        $this->entityManager->flush();

        return $sentAmount;
    }

    public function sendNotification(Notification $notification): void
    {
        $this->appMailer->sendNotificationFailedConfirmation($notification);
    }
}