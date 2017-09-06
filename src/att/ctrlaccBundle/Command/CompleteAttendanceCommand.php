<?php

namespace att\ctrlaccBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Description of MakeAttendanceCommand
 *
 * @author JotaMolas
 */
class CompleteAttendanceCommand extends ContainerAwareCommand {
    
    public function configure() {
        $this->setName('ctrlacc:att:complete')
               ->setDescription('Complete Attendances every day from remote Devices')
               ->addArgument('day', InputArgument::REQUIRED, 'Date to complete Attendanc, in format yyyymmdd');
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
                
        $result = $this->getContainer()->get('ctrlacc.attendance.service')->completeAttendance($input->getArgument('day')); 

        $logger = $this->getContainer()->get('monolog.logger.ctrlacc');
        $logger->info("Execute command:completeatt for day: ".$input->getArgument('day'));
        $output->writeln("==================================================");
        $output->writeln("Execute command:completeatt for day: ".$input->getArgument('day'));
        
        if($result['status'] == 'ok'){
            $logger->info("Status: ". $result['status']." Completed Attendances: ".count($result['atts']));
            $output->writeln("Status: ". $result['status']." Completed Attendances: ".count($result['atts']));
             
            foreach($result['atts'] as $att){                
                $logger->info($att);
            }
        }else{
            $logger->error("Status: ". $result['status']." Error: ".$result['errors']);
            $output->writeln("Status: ". $result['status']." Error: ".$result['errors']);
        }

         $output->writeln("==================================================");
        

        
       
    }
    
}
