<?php

namespace App\Command;

use App\Service\NotificationSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class NotificationSendCommand extends Command
{
    protected static $defaultName = 'app:notification:send';
    protected static $defaultDescription = 'Worker for sending emails for notifiables';

    private NotificationSender $notificationSender;

    /**
     * NotificationSendCommand constructor.
     * @param  NotificationSender  $notificationSender
     */
    public function __construct(NotificationSender $notificationSender)
    {
        $this->notificationSender = $notificationSender;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        do {
            $date = new \DateTime();
            $amount = $this->notificationSender->sendNotifications();

            $io->success("amount of sent notification on {$date->format('Y-m-d H:i:s')} : $amount");
            sleep(40);
        } while (true);//todo: ability to stop gracefully

        return Command::SUCCESS;
    }
}
