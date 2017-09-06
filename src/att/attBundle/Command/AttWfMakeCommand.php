<?php
namespace att\attBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AttWfMakeCommand extends ContainerAwareCommand{
       protected function configure()
    {
        $this
            ->setName('att:attendance:make')
            ->setDescription('Make Attendance from Plan registred')
            ->addArgument('date', InputArgument::REQUIRED, 'Date to make Attendance, in format YYYYmmdd')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        
        $logger = $this->getContainer()->get('monolog.logger.att');        
        $result = $this->getContainer()->get('att.wf.attendance.service')->makeAttendances($input->getArgument('date'));
     
        $outputmsg = 
                "Status: ".$result['status']." | "
                ."Message: ".$result['message']." | ";
        
        isset($result['errors_validation']) ? $outputmsg .= "Validation Errors: ".count($result['errors_validation'])." | " : NULL ;
        isset($result['errors_persist']) ? $outputmsg .= "Exceptions: ".count($result['errors_persist'])." | " : NULL ;
        
        $output->writeln($outputmsg);
        
        if($result['status'] == 'ok'){
            $logger->info($outputmsg);
        }else{
            $logger->error($outputmsg);
        }
        
    }
}
