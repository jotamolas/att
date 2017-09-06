<?php

namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;



class SyncEmployeeAxtonCommand extends ContainerAwareCommand{
    
    protected function configure() {
        $this->setName('employee:sync:axton')
              ->setDescription('Sync Employees from Axton WSDL - New Employees and changes of exists');
                      
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('monolog.logger.employee');
        
        $newEmployees = $this->getContainer()->get('employee.service')->syncNewEmployeesFromAxton();
        $activeEmployees = $this->getContainer()->get("employee.service")->syncActiveEmployeesFromAxton();
        
        $output->writeln("==================================================");
        $output->writeln("Employee Sync From External System Axton");
        $output->writeln("==================================================");
        $output->writeln("==================================================");
        $output->writeln(count($newEmployees['employees'])." employees new Syncronized");
        $output->writeln(count($newEmployees['errors'])." errors found.");
        $output->writeln("==================================================");
        $output->writeln(count($activeEmployees['employees'])." employees Updated");
        $output->writeln(count($activeEmployees['errors'])." errors found.");        
        $output->writeln("==================================================");
        
        $logger->info("Employees News: ".count($newEmployees['employees'])." Employees Active Updated: ".count($activeEmployees['employees'])); 
        
        
    }
}
