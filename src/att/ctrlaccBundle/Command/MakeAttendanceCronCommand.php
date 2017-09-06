<?php

namespace att\ctrlaccBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class MakeAttendanceCronCommand extends ContainerAwareCommand {
    
    public function configure() {
        $this->setName('ctrlacc:att:make:cron')
               ->setDescription('Makes Attendances every day from remote Devices');               
    }
    
    public function execute(InputInterface $input, OutputInterface $output) {
        
        $now = new \DateTime();
        ($now->format('H:i:s') >= $this->getContainer()->getParameter('util.workingday.end') 
           ? $now
               : $now->modify('-1 Day')); 
                
        $command = $this->getApplication()->find('ctrlacc:att:make');
        $arg = [
            'command' => 'ctrlacc:att:make',
            'day' => $now->format('Ymd')
        ];
        $greetInput =  new \Symfony\Component\Console\Input\ArrayInput($arg);
        $returnCode = $command->run($greetInput, $output);
        
        if($returnCode == 0){
            $output->writeln("The command was executed successfully ");
        }else{
            $output->writeln("The command did not run successfully ");
        }
           
    }
}
