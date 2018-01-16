<?php

namespace att\syncBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SyncRemoteEventCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName('sync:remote:event')
            ->setDescription('Syncronize CtrlAcc App from Remote Device or Database')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $this->getContainer()->get('sync.remote.service')->syncEvents();
        $output->writeln($message);
    }
}
