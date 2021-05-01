<?php

namespace App\Command;

use App\Entity\Confirmation;
use App\Service\ConfirmationChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfirmationMarkMissedCommand extends Command
{
    protected static $defaultName = 'app:confirmation:mark-missed';
    protected static $defaultDescription = 'Worker for marking confirmations as missed';

    private ConfirmationChecker $confirmationChecker;

    /**
     * ConfirmationMarkMissedCommand constructor.
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
            $date = (new \DateTime())->modify('-'.Confirmation::GAP_TIMEOUT.' minutes');
            $missedAmount = $this->confirmationChecker->checkConfirmations($date);
            $io->success("amount of missed confirmation on {$date->format('Y-m-d H:i:s')} : $missedAmount");
            sleep(40);
        } while (true);//todo: ability to stop gracefully

        return Command::SUCCESS;
    }
}
