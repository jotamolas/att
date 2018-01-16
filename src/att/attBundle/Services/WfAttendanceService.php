<?php

namespace att\attBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;


class WfAttendanceService {
    
    protected $em;    
    protected $container;
    
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;        
       
    }  
    
    
    public function makeAttendances($day){
        
        /* primer paso recupero si es que hay los Planes para la fecha evaluadoa */
        $firstStepResult = $this->container->get('att.attendance.service')->firstStepCreateAttendance($day);
        
        if($firstStepResult['status'] == 'ok'){
            /* si el primer paso devolvio planes se ejecuta el segundo paso */
            $secondStepResult = $this->container->get('att.attendance.service')->secondStepCreateAttendance($firstStepResult['message']);
            return $secondStepResult;
        }else{
            return $firstStepResult;
        }
        
    }
    
    public function processAttendance($day){
        
        /*Convieto la variable day en un Datetime */
        $date = date_create_from_format('Ymd', $day);
        /* Obtengo el dia actual */
        $today = new \DateTime(); 
        /* Verifico el dia de trabajo establecido */
        $dates = $this->container->get('util.workingday.service')->checkWorkingDay($day);
        //dump($dates);
        /* Declaro dos arreglos vacios para las ausencias y las excepciones */
        $abss = [];
        $exps = [];

        /* Verifica si el dia enviado es igual al dia actual*/
        $date->format('Ymd') === $today->format('Ymd') ?
        /* Dependiendo la diferencia llama al metodo findBetweenInPlanDates para obtner las asistencias que estan para estas fechas */
            $atts = $this->em->getRepository('attBundle:Atattendance')->findBetweenInPlanDates($dates['start'], new \DateTime()):
            $atts = $this->em->getRepository('attBundle:Atattendance')->findBetweenInPlanDates($dates['start'], $dates['end']);
            
        
        
        foreach ($atts as $att){       
            dump($att->getPlan()->getEmployee()->getSurname().''.$att->getId());
            $ctrlacc_attendances = $this->em->getRepository('ctrlaccBundle:Attendance')->findOneBy([
                'employee' => $att->getPlan()->getEmployee()->getDni(),
                'date' => date_create_from_format('Ymd', $day)
            ]);
            
            $rs =$this->container->get('att.attendance.service')->process($att, $ctrlacc_attendances);
            $rs['abs'] ? $abss []= $rs['abs'] : NULL;
            $rs['exp'] ? $exps []= $rs['exp'] : NULL;
            
        }
        
        return  ['atts' => $atts, 'abss' => $abss, 'exps' => $exps];   
        
    }

}
