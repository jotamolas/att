<?php

namespace att\attBundle\Command;

    use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;

class AttWfCheckPlanCommand extends ContainerAwareCommand {

    protected function configure()
    {
        $this
            ->setName('att:attendance:check:plan')
            ->setDescription('Check plans to tomorrow')
        ;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $date = new \DateTime('tomorrow');
        $command = $this->getApplication()->find('att:checkplan');
        $arguments = [
            'command' => 'att:checkplan',
            'date' => $date->format('Ymd')
        ];
        $greetInput = new \Symfony\Component\Console\Input\ArrayInput($arguments);
        $returnCode = $command->run($greetInput, $output);
        
        if($returnCode){
            
        }else{
            $mail = $this->getContainer()->get('att.plan.service')->sendWarningMailNotification();
        }
        
        
        $output->writeln(var_dump($returnCode));
    }
}
