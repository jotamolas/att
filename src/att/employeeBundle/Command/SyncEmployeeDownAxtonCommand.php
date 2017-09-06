<?php

namespace att\employeeBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SyncEmployeeDownAxtonCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('employee:down:sync:axton')
            ->setDescription('...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('monolog.logger.employee');
        
        
        $employees = $this->getContainer()->get("employee.service")->syncInactiveFromAxton();
        
        $output->writeln("==================================================");
        $output->writeln("Employee Sync From External System Axton");
        $output->writeln("==================================================");
        $output->writeln("==================================================");
        $output->writeln(count($employees['employees'])." employees Down Updated");
        $output->writeln(count($employees['errors'])." errors found.");        
        $output->writeln("==================================================");
        
        $logger->info("Employees Down Updated: ".count($employees['employees'])); 
        
        
    }

}
