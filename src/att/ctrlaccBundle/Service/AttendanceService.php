<?php
namespace att\ctrlaccBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

class AttendanceService {
    
    protected $emctrlacc;
    protected $embiometric;
    protected $container;
    protected $em;
    
    public function __construct(EntityManager $em, EntityManager $emctrlacc, EntityManager $embiometric , ContainerInterface $container) {
        $this->container = $container;
        $this->emctrlacc = $emctrlacc;
        $this->embiometric = $embiometric;
        $this->em = $em;
    }
    
    
    public function makeAttendance($day){
        
        $dates = $this->container->get('util.workingday.service')->checkWorkingDay($day);
        $events = $this->getFirstEventForDay($dates['start'],$dates['end']);
        
        if($events){
            
            $atts = $this->firstStepToMakeAttendance($events);
            if($atts['attsNew']){
                
                $result =  $this->secondStepToMakeAttendance($atts['attsNew']);
                
            }else{
                
                $result = ['status' => 'error', 'errors' => 'No events to raise attendances'];
            }
        }else{
            
             $result = ['status' => 'error', 'errors' => 'No events to raise attendances'];
             
        }    
        
        return $result;
        
    }
    
    public function completeAttendance($day){
        
        $dates = $this->container->get('util.workingday.service')->checkWorkingDay($day);
        $events = $this->getLastEventForDay($dates['start'],$dates['end']);
        

       if($events){
            $atts = $this->firstStepToCompleteAttendance($events);
            if($atts){
                $result =  $this->secondStepToCompleteAttendance($atts);
            }else{
                $result = ['status' => 'error', 'errors' => 'No events to complete attendances'];
            }
        }else{
             $result = ['status' => 'error', 'errors' => 'No events to complete attendances'];
        }
        return $result;
    }
    
    /**
     * This functions create attendance, if first event (in) is registered, and return attendances object to persist. 
     * @param type $events
     * @return \att\ctrlaccBundle\Entity\Attendance
     */
    public function firstStepToMakeAttendance($events){
        $atts = array();
        $attsExist = array();
        
        foreach ($events as $event){
            

                
            $att = $this->emctrlacc->getRepository('ctrlaccBundle:Attendance')->findOneBy(
                        [
                            'employee' =>  $event[0]->getEmployee(),
                            'date' => $event[0]->getEventdate()
                        ]);          


                
          
            if(!$att){
                $att = new \att\ctrlaccBundle\Entity\Attendance();
                $att->setDate($event[0]->getEventdate())
                    ->setInEvent(date_create_from_format('Y-m-d H:i:s',$event[1]))
                    ->setEmployee($event[0]->getEmployee());
                $atts[]= $att;
            }else{
                $attsExist[] = $att;
            }
        }
        return ['attsNew' => $atts, 'attExist' => $attsExist];
    }
    
    /**
     * 
     * @param type $atts
     * @return type
     */
    public function secondStepToMakeAttendance($atts){
        
        $errors = array();
        $attsToPersist = array();
            
        foreach($atts as $att){
            
            $error = $this->container->get('validator')->validate($att);
            
            if(count($error)>0){
                print "aca error!";
                $errors [] = (string) $error;
            }else{
                $attsToPersist[] = $att;
            }
        }
         if(count($attsToPersist)>0){
            
            $result = $this->persistAttendances($attsToPersist);
            return ['status' => 'ok', 'errors' => array_merge($errors, $result['errors']), 'atts' => $result['atts']] ;
        }else{
            return ['status'=> 'error', 'errors' => $errors] ;
        }
    }
    

     /**
     * This functions complete attendance. 
     * @param type $events
     * @return \att\ctrlaccBundle\Entity\Attendance
     */
    public function firstStepToCompleteAttendance($events){
        
        $atts = array();
        foreach ($events as $event){
            
            $att = $this->emctrlacc->getRepository('ctrlaccBundle:Attendance')->findOneBy(
                    [
                        'employee' =>  $event[0]->getEmployee(),
                        'date' => $event[0]->getEventdate()
                    ]);
            
            if($att){
                if($att->getInEvent() == date_create_from_format('Y-m-d H:i:s',$event[1])){
                    $att->setOutEvent(NULL);
                }else{
                    $att->setOutEvent(date_create_from_format('Y-m-d H:i:s',$event[1]));
                    $hswktimerange = $att->getInEvent()->diff($att->getOutEvent());
                    $hswktime = date_create($att->getInEvent()->format("Y-m-d")." ".$hswktimerange->format("%H:%I:%S"));
                    $att->setHoursWorkedTime($hswktime);
                }
                
                              
                
                $atts[]= $att;
            }
        }
        
        return $atts;
    }
    
     /**
     * 
     * @param type $atts
     * @return type
     */
    public function secondStepToCompleteAttendance($atts){
        
        $errors = array();
        $attsToUpdate = array();
            
        foreach($atts as $att){
            
             $error = $this->container->get('validator')->validate($att);
            if(count($error)>0){
                 $errors [] = (string) $error;
            }else{
                $attsToUpdate[] = $att;
            }
        }
         if(count($attsToUpdate)>0){
            
            $result = $this->updateAttendances($attsToUpdate);
            return ['status' => 'ok', 'errors' => array_merge($errors, $result['errors']), 'atts' => $result['atts']] ;
        }else{
            return ['status'=> 'error', 'errors' => $errors] ;
        }
    }
    
    /**
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @return type
     */
    public function getFirstEventForDay(\DateTime $start, \DateTime $end){
        
        $qb = $this->emctrlacc->getRepository('ctrlaccBundle:Event')->createQueryBuilder('e');
        
        $rs = $qb->addSelect($qb->expr()->min('e.eventtime'))
                
                ->add('where', $qb->expr()->between('e.eventtime',':s', ':e'))
                ->andWhere('e.eventdate = :start')
                ->setParameter('start', $start->format('Y-m-d'))
                ->setParameter('s', $start->format('Y-m-d H:i:s'))
                ->setParameter('e', $end->format('Y-m-d H:i:s'))
                ->groupBy('e.employee')
                ->getQuery()
                ->getResult();
        
        return $rs;
    }    
    
     /**
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     * @return type
     */
    public function getLastEventForDay(\DateTime $start, \DateTime $end){
        
        $qb = $this->emctrlacc->getRepository('ctrlaccBundle:Event')->createQueryBuilder('e');
        
        $rs = $qb->addSelect($qb->expr()->max('e.eventtime'))
                
                ->add('where', $qb->expr()->between('e.eventtime',':s', ':e'))
                ->andWhere('e.eventdate = :start')
                ->setParameter('start', $start->format('Y-m-d'))
                ->setParameter('s', $start->format('Y-m-d H:i:s'))
                ->setParameter('e', $end->format('Y-m-d H:i:s'))
                ->groupBy('e.employee')
                ->getQuery()
                ->getResult();
        
        return $rs;
    }    

        
    /**
     * 
     * @param type $atts
     * @return type
     */
    public function persistAttendances($atts){
        $attsPersisted = array();
        $errors = array();
        $batchSize = 1;
        $i = 1;
        
        
        foreach ($atts as $att) { 
            $i ++;            
            try{
                
                if (($i % $batchSize) == 0){
                        $this->emctrlacc->persist($att);
                        $this->emctrlacc->flush();
                        $this->emctrlacc->clear();
                        $attsPersisted[] = $att;
                    }
            }catch (\Exception $e){
                $errors[] = $e->getMessage();
                $i--;
                }
        }
        return ['atts' => $attsPersisted, 'errors' => $errors];
        
    }
    
    
    /**
     * 
     * @param type $atts
     * @return type
     */
    public function updateAttendances($atts){
        $attsUpdated = array();
        $errors = array();
        $batchSize = 1;
        $i = 1;
        
        foreach ($atts as $att) { 
            $i ++;
            try{
                
                if (($i % $batchSize) == 0){
                        $this->emctrlacc->merge($att);
                        $this->emctrlacc->flush();
                        $this->emctrlacc->clear();
                        $attsUpdated[] = $att;
                    }
            }catch (\Exception $e){
                $errors[] = $e->getMessage();
                $i--;
                }
        }
        return ['atts' => $attsUpdated, 'errors' => $errors];        
    }
    
    
    public function getAttendancesFromEmployeeListAndDay(\DateTime $day, array $employees = null){
    
        $qb = $this->emctrlacc->getRepository('ctrlaccBundle:Attendance')->createQueryBuilder('a');
        $qb->where('a.date = :date')
           ->setParameter('date', $day->format('Y-m-d'));
        
        if($employees){
           $qb->andWhere($qb->expr()->in('a.employee', ':employees'))
               ->setParameter('employees', $employees);
        }
        
        $attendances = $qb->getQuery()->getResult();
        
        return $attendances;
    }
    
    public function getAttendancesFromDay(\DateTime $day){
        $qb = $this->emctrlacc->getRepository('ctrlaccBundle:Attendance')->createQueryBuilder('a');
                $qb->where('a.date = :date')
                    ->setParameter('date', $day->format('Y-m-d'));
        
        $attendances = $qb->getQuery()->getResult();
        return $attendances;
    }
    
}
