<?php

namespace att\utilBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class WorkingDayService {
    
    protected $container;
    
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }
    
    
    /**
    * Check Working day from parameters set.
    * @param type $day
    * @return type
    */
    
    public function checkWorkingDay($day){
        
        if($this->container->hasParameter('util.workingday.start') && $this->container->hasParameter('util.workingday.end')){
        
            $start = $this->container->getParameter('util.workingday.start');
            $end = $this->container->getParameter('util.workingday.end');
            
            if($start >= $end){
                
                $start = date_create_from_format('Ymd h:i:s', $day." ".$start);
                $end = date_create_from_format('Ymd h:i:s', $day." ".$end);
                $end->modify('+1 day');
                
            }else{
                $start = date_create_from_format('Ymd h:i:s', $day." ".$start);
                $end = date_create_from_format('Ymd h:i:s', $day." ".$end);
                
            }
            return [
                'start' => $start,
                'end' => $end
                    ];
        }else {
            $start = date_create_from_format('Ymd h:i:s', $day." 00:00:00");
            $end = date_create_from_format('Ymd h:i:s', $day." 00:00:00");
            $end->modify('+1 day');
            
            return [
                'start' => $start ,
                'end' => $end];
        }
    }
    
}
