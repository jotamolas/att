<?php

namespace att\attBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AttWfProcessCommand extends ContainerAwareCommand {
    
    public function configure() {
        $this->setName('att:attendance:process')
        ->setDescription('Add CtrlAcc Events for Attendances registred')
        ->addArgument('date', InputArgument::REQUIRED, 'Date to add Events to Attendances, in format YYYYmmdd');
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('monolog.logger.att');
        $result = $this->getContainer()->get('att.wf.attendance.service')->processAttendance($input->getArgument('date'));
        
        if(count($result['atts']) > 0){
            $outputmsg = 
                    "Status: Ok | "
                    ."Message: One or more Attendances have events | ";
        }else{
            $outputmsg = 
                    "Status: Error | "
                    ."Message: No attendance records to process | ";
            
        }
        
        $outputmsg .=
                "Processed: ".count($result['atts'])." Attendances | "
                ."Absences found: ". count($result['abss'])." | "
                ."Inconsistencies found: ". count($result['exps']) ;
        
        $output->writeln($outputmsg);
        $logger->info($outputmsg);
        
    }
    
}
    