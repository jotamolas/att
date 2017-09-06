<?php

namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class SyncCompanyAxtonCommand extends ContainerAwareCommand {
    
    protected function configure() {
        $this->setName('employee:company:sync:axton')
                ->setDescription("Sync Companies from Axton");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $result =  $this->getContainer()->get('employee.company.service')->syncFromAxton();
        $logger = $this->getContainer()->get('monolog.logger.employee');
        
               
        $output->writeln("==================================================");
        $output->writeln("Company Sync From External System Axton");
        $output->writeln("==================================================");
        $output->writeln("==================================================");
        $output->writeln("Status: ".$result['status']);
        $output->writeln("==================================================");
        $output->writeln("Message: ".$result['message']);        
        $output->writeln("==================================================");
        
        $logger->info("Status: ".$result['status']." Message: ".$result['message']); 
        
    }
}
