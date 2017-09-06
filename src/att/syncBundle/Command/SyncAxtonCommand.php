<?php


namespace att\syncBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SyncAxtonCommand extends ContainerAwareCommand {
    
        protected function configure()
    {
        $this
            ->setName('sync:axton:employees')
            ->setDescription('Syncronize CtrlAcc App from Biometric')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('monolog.logger.sync');
        
        $resultImport = $this->getContainer()->get('sync.axton.service')->syncEmployee();
        
        
        $output->writeln("==================================================");
        $output->writeln("Employee Sync From External System Axton");
        
        $output->writeln("==================================================");
        $output->writeln(count($resultImport)." records imported from Axton WebServices");
        $output->writeln("==================================================");
        
        
        $logger->info(count($resultImport)." records imported from Axton WebServices");
        
    }
    
}
