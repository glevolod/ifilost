<?php

namespace App\Command;

use App\Service\ConfirmationChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfirmationManageFailedCommand extends Command
{
    protected static $defaultName = 'app:confirmation:manage-failed';
    protected static $defaultDescription = 'Worker for managing failed confirmations (create notifications)';

    private ConfirmationChecker $confirmationChecker;

    /**
     * ConfirmationManageFailedCommand constructor.
     * @param  ConfirmationChecker  $confirmationChecker
     */
    public function __construct(ConfirmationChecker $confirmationChecker)
    {
        $this->confirmationChecker = $confirmationChecker;
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
            $failedAmount = $this->confirmationChecker->manageFailedConfirmation();
            $io->success("amount of failed confirmation on {$date->format('Y-m-d H:i:s')} : $failedAmount");
            sleep(40);
        } while (true);//todo: ability to stop gracefully

        return Command::SUCCESS;
    }
}
