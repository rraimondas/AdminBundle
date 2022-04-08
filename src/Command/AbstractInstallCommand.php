<?php

namespace Platform\Bundle\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractInstallCommand extends Command
{
    protected CommandExecutor $commandExecutor;

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $application = $this->getApplication();

        if ($application === null) {
            return;
        }

        $application->setCatchExceptions(false);

        $this->commandExecutor = new CommandExecutor(
            $input,
            $output,
            $application
        );
    }
}
