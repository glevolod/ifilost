<?php

namespace App\Command;

use App\Service\ConfirmationSender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfirmationSendCommand extends Command
{
    protected static $defaultName = 'app:confirmation:send';
    protected static $defaultDescription = 'Command for sending emails for confirmation';

    private ConfirmationSender $confirmationSender;

    /**
     * ConfirmationSendCommand constructor.
     * @param  ConfirmationSender  $tickManager
     */
    public function __construct(ConfirmationSender $confirmationSender)
    {
        $this->confirmationSender = $confirmationSender;
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
            $startDate = new \DateTime();
            $amount = $this->confirmationSender->sendConfirmations($startDate);

            $io->success("amount of sent confirmation emails on {$startDate->format('Y-m-d H:i:s')} : $amount");
            sleep(40);
        } while (true);//todo: ability to stop gracefully

        return Command::SUCCESS;
    }
}
