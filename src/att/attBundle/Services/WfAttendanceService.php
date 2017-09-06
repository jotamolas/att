<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;


class WfAttendanceService {
    
    protected $em;
    protected $emctrlacc;
    protected $container;
    
    public function __construct(EntityManager $em, EntityManager $emctrlacc, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;        
        $this->emctrlacc = $emctrlacc;
    }  
    
    
    public function makeAttendances($day){
        
        $firstStepResult = $this->container->get('att.attendance.service')->firstStepCreateAttendance($day);
        if($firstStepResult['status'] == 'ok'){
            $secondStepResult = $this->container->get('att.attendance.service')->secondStepCreateAttendance($firstStepResult['message']);
            return $secondStepResult;
        }else{
            return $firstStepResult;
        }
        
    }
    
    public function processAttendance($day){
        
        $date = date_create_from_format('Ymd', $day);
        $today = new \DateTime(); 
        $dates = $this->container->get('util.workingday.service')->checkWorkingDay($day);
        $abss = [];
        $exps = [];
        $date->format('Ymd') === $today->format('Ymd') ?
            $atts = $this->em->getRepository('attBundle:Atattendance')->findBetweenInPlanDates($dates['start'], new \DateTime()):
            $atts = $this->em->getRepository('attBundle:Atattendance')->findBetweenInPlanDates($dates['start'], $dates['end']);
       

        foreach ($atts as $att){
            
            $ctrlaccatt = $this->emctrlacc->getRepository('ctrlaccBundle:Attendance')->findOneBy([
                'employee' => $att->getPlan()->getEmployee()->getDni(),
                'date' => date_create_from_format('Ymd', $day)
            ]);
            
            $rs =$this->container->get('att.attendance.service')->process($att, $ctrlaccatt);
            $rs['abs'] ? $abss []= $rs['abs'] : NULL;
            $rs['exp'] ? $exps []= $rs['exp'] : NULL;
            
        }
        
        return  ['atts' => $atts, 'abss' => $abss, 'exps' => $exps];       
        
    }

}
