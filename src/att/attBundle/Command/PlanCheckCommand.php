<?php

namespace att\attBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PlanCheckCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName('att:plan:check')
            ->setDescription('Check if there is a plan for a date')
            ->addArgument('date', InputArgument::REQUIRED, 'The Date to find Plan, in format YYYYmmdd')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        // retrieve the argument value using getArgument()
        $logger = $this->getContainer()->get('monolog.logger.plan');        
        $result = $this->getContainer()->get('att.plan.service')->checkPlanFromStringDate($input->getArgument('date'));
                
        $output->writeln("Status: ".$result['status']." Message: ".$result['message']);
        $logger->info("Status: ".$result['status']." Message: ".$result['message']);
    }
}
