<?php


namespace App\Service;


use App\Entity\TickQueue;
use App\Repository\TickQueueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class TickManager
{
    private TickQueueRepository $tickQueueRepository;
    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;
    private LoggerInterface $logger;

    /**
     * TickManager constructor.
     * @param  TickQueueRepository  $tickQueueRepository
     * @param  EntityManagerInterface  $entityManager
     * @param  MailerInterface  $mailer
     * @param  LoggerInterface  $logger
     */
    public function __construct(
        TickQueueRepository $tickQueueRepository,
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        LoggerInterface $logger
    ) {
        $this->tickQueueRepository = $tickQueueRepository;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
        $this->logger = $logger;
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
        $email = (new Email())
            ->to($tickQueue->getTick()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage()."\n".$e->getTraceAsString());
        }
        echo "\n";

        return true;
    }
}