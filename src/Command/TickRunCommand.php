<?php

namespace App\Command;

use App\Service\TickManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TickRunCommand extends Command
{
    protected static $defaultName = 'app:tick:run';
    protected static $defaultDescription = 'Command for running scheduled ticks';

    private TickManager $tickManager;

    /**
     * TickRunCommand constructor.
     * @param  TickManager  $tickManager
     */
    public function __construct(TickManager $tickManager)
    {
        $this->tickManager = $tickManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $startDate = new \DateTime();
        $amount = $this->tickManager->runTicks($startDate);

        $io->success("amount ticks run on {$startDate->format('Y-m-d H:i:s')} : $amount");

        return Command::SUCCESS;
    }
}
