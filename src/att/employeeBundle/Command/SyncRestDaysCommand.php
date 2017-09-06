<?php

namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class SyncRestDaysCommand extends ContainerAwareCommand{
    
    protected function configure() {
        $this->setName('employee:sync:restdays')
              ;
                      
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        
        
        $rs = $this->getContainer()->get('employee.contract.service')->importRestDayFromCCM();
        
        $output->writeln(count($rs));
        
        
        
        
        
    }
}
