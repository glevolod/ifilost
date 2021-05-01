<?php


namespace App\Service;


use App\Entity\Confirmation;
use App\Entity\Notification;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class AppMailer
{
    private MailerInterface $mailer;
    private LoggerInterface $logger;
    private ContainerBagInterface $params;
    private string $appServiceName;

    /**
     * AppMailer constructor.
     * @param  MailerInterface  $mailer
     * @param  LoggerInterface  $logger
     * @param  ContainerBagInterface  $params
     * @param  string  $appServiceName
     */
    public function __construct(
        MailerInterface $mailer,
        LoggerInterface $logger,
        ContainerBagInterface $params,
        string $appServiceName
    ) {
        $this->mailer = $mailer;
        $this->logger = $logger;
        $this->params = $params;
        $this->appServiceName = $appServiceName;
    }


    public function sendConfirmation(Confirmation $confirmation)
    {
        $email = (new TemplatedEmail())
            ->to($confirmation->getQueue()->getTick()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Отметка от '.$this->appServiceName)
            ->htmlTemplate('emails/confirmation.html.twig')
            ->context(
                [
                    'userName' => $confirmation->getUser()->getUsername(),
                    'guid' => $confirmation->getGuid(),
                    'maxTime' => $confirmation->getMaxDateTime(),
                ]
            );
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage()."\n".$e->getTraceAsString());
            throw $e;
        }
    }

    public function sendNotificationMissedConfirmation(Notification $notification)
    {
        $user = $notification->getConfirmation()->getUser();
        $notifiables = $user->getNotifiables();
        $emailsAddr = [];
        foreach ($notifiables as $notifiable) {
            $emailsAddr[] = $notifiable->getEmail();
        }
        $email = (new TemplatedEmail())
            ->to(...$emailsAddr)
//            ->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($user->getUsername(). ' через сервис '.$this->appServiceName)
            ->htmlTemplate('emails/notification_missed_confirmation.html.twig')
            ->context(
                [
                    'userName' => $user->getUsername(),
                    'maxTime' => $notification->getConfirmation()->getMaxDateTime(),
                ]
            );
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            $this->logger->error($e->getMessage()."\n".$e->getTraceAsString());
            throw $e;
        }
    }
}