<?php

namespace att\ctrlaccBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;


class EventService {
    
    protected $emctrlacc;
    protected $embiometric;
    protected $container;

    /**
     * 
     * @param EntityManager $emctrlacc
     * @param EntityManager $embiometric
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $emctrlacc, EntityManager $embiometric , ContainerInterface $container) {
        $this->container = $container;
        $this->emctrlacc = $emctrlacc;
        $this->embiometric = $embiometric;
    }
    
    /**
     * 
     * @return type
     */
    public function getLastIdEvent(){
        
        $qb = $this->emctrlacc->createQueryBuilder('e')
                ->select('MAX(e.idoriginal) as id')
                ->from('ctrlaccBundle:Event', 'e');
        
        $id = $qb->getQuery()->getSingleResult();
        
        return $id['id'];
    }
    
    
    /**
     * 
     * @param type $employee
     * @param type $date
     * @param type $time
     * @param type $type
     * @param type $iddevice
     * @param type $idoriginal
     * @return \att\ctrlaccBundle\Entity\Event
     */
    public function createEvent($employee, $date, $time, $type, $iddevice, $idoriginal){
        $event = new \att\ctrlaccBundle\Entity\Event();
        $event->setEmployee($employee)
                ->setEventdate($date)
                ->setEventtime($time)
                ->setEventtype($type)
                ->setIddevice($iddevice)
                ->setIdoriginal($idoriginal);
        return $event;
    }
    
    /**
     * 
     * @param type $events
     */
    public function persistALotOfEvents($events){
        $batchSize = 20;
        $i = 1;
        foreach ($events as $event){
            $i ++;
            //print "grabando ".$i." evento </br>";
            //print ($i % $batchSize)."</br>";
            print $this->emctrlacc->persist($event);
            if (($i % $batchSize) == 0){
                $this->emctrlacc->flush();
                $this->emctrlacc->clear();
            }
        }
    }
    
    
    /**
     * 
     * @param type $event
     */
    public function persistEvent($event){
        
        $this->emctrlacc->persist($event);
        $this->emctrlacc->flush();
        
    }
}
