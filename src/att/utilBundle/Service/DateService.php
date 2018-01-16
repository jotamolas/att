<?php


namespace att\utilBundle\Service;


class DateService {
    
    public function getIterateDayPeriod(\DateTime $begin, \DateTime $end){
        
        $interval = \DateInterval::createFromDateString('1 day');
       
        $period = new \DatePeriod($begin, $interval, $end->modify('+ 1 Day'));
        
        return $period;
        
    }   

    
    
    
}
