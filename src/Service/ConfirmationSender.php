<?php


namespace App\Service;


use App\Entity\Confirmation;
use App\Entity\ConfirmationQueue;
use App\Repository\ConfirmationQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ConfirmationSender
{
    private ConfirmationQueueRepository $confirmationQueueRepository;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    /**
     * ConfirmationSender constructor.
     * @param  ConfirmationQueueRepository  $confirmationQueueRepository
     * @param  EntityManagerInterface  $entityManager
     * @param  MailerInterface  $mailer
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        ConfirmationQueueRepository $confirmationQueueRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger
    ) {
        $this->confirmationQueueRepository = $confirmationQueueRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->logger = $logger;
    }


    public function sendConfirmations(\DateTimeInterface $startDate, ?int $amount = null): int
    {
        $confirmationQueues = $this->confirmationQueueRepository->getPreparedForSend($startDate, $amount);

        $sentAmount = count($confirmationQueues);
        foreach ($confirmationQueues as $confirmationQueue) {
            $this->sendConfirmation($confirmationQueue);
        }

        return $sentAmount;
    }

    public function sendConfirmation(ConfirmationQueue $confirmationQueue): bool
    {
        $confirmation = new Confirmation();
        $schedule = $confirmationQueue->getSchedule();
        //todo: confirmation sendDateTime should be immutable
        $maxDateTime = $confirmationQueue->getSendDateTime()->modify("+ {$schedule->getTimeout()} minutes");
        $confirmation
            ->setQueue($confirmationQueue)
            ->setType(Confirmation::TYPE_FIRST_CONFIRMATION)
            ->setStatus(Confirmation::STATUS_WAITING)
            ->setMaxDateTime($maxDateTime);
        $confirmationQueue->setStatus(ConfirmationQueue::STATUS_SENT);
        $this->entityManager->persist($confirmationQueue);
        $this->entityManager->persist($confirmation);
        $this->entityManager->flush();
        echo $confirmation->getId();
        $email = (new Email())
            ->to($confirmationQueue->getTick()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Confirmation: '.$confirmation->getGuid())
            ->html('<p>Confirmation: '.$confirmation->getGuid().'</p>');
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage()."\n".$e->getTraceAsString());
        }
        echo "\n";

        return true;
    }
}