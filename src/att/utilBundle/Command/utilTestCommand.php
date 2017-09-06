<?php

namespace att\utilBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class utilTestCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                ->setName('util:test')
                ->setDescription('Test something')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        //$rs = new \Doctrine\Common\Collections\ArrayCollection();
        //$rs->add($this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atrestday")->find(1));
        /*$c = new \att\employeeBundle\Entity\Atcontract();
        $c->setStartDate(date_create_from_format('Y-m-d', '2017-06-01'))
                ->setInTime(date_create_from_format('Y-m-d H:i:s', '2017-06-01 08:00:00'))
                ->setOutTime(date_create_from_format('Y-m-d H:i:s', '2017-06-01 14:00:00'))
                ->setFileNumber(366999)
                ->setStatus($this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atemployeestatus")->find(1))
                //->setRestDays($rs)
                ->setSchema($this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atschema")->find(1));



        $e = $this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atemployee")->find(2104);
        $b = $this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atbusiness")->find(2);

        $e->addcontract($c);
        $b->addcontract($c);*/
        $rd = $this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atrestday")->find(2);        
        $c = $this->getContainer()->get('doctrine')->getRepository("employeeBundle:Atcontract")->findOneById(19110);
        
        $rd->addContract($c);
        //$c->addRestDay($rd);        
        $em = $this->getContainer()->get('doctrine')->getManager();        
        $em->persist($rd);
        $em->persist($c);
        /*employee.service $output->writeln("==================================================");
        $output->writeln("Employee Sync From External System Axton");

        $output->writeln("==================================================");
        $output->writeln(count($resultImport) . " records imported from Axton WebServices");
        $output->writeln("==================================================");*/
        
        $em->flush();
        
        
    }

}
