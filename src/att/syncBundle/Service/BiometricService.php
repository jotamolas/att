<?php

namespace att\syncBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Description of Tools
 *
 * @author jorge.molas
 */
class BiometricService{
    
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
     * @param type $id
     * @return type
     * 
     */
    public function getBiometricEventsForSync($id){
    
        if(!$id){
            //print "sin id";
            $events = $this->embiometric->getRepository("att\syncBundle\Entity\Biometric\Checkinout")->findAll();
            
        }else{
            
           // print "con id";
            
            $qbm = $this->embiometric->createQueryBuilder('e')
                        ->select('e')
                        ->from('att\syncBundle\Entity\Biometric\Checkinout', 'e')
                        ->where('e.logid > :id' )
                        ->setParameter('id',$id);
            $events = $qbm->getQuery()->getResult();
        }
        
        return $events;
    }
    
    /**
     * 
     * @param type $fpevents
     * @return type
     * 
     */
    public function transformToCtrlAccEvent($fpevents){
        $ctrlAccEvents = array();
        foreach($fpevents as $fpevent){
            
            $employee = $this->embiometric->getRepository("att\syncBundle\Entity\Biometric\Userinfo")->findOneByUserid($fpevent->getUserid());
            if($employee){
                $ctrlAccEvent = $this->container
                        ->get('ctrlacc.event.service')
                        ->createEvent(
                                $employee->getBadgenumber(),
                                $fpevent->getChecktime(),
                                $fpevent->getChecktime(),
                                ($fpevent->getChecktype() == 'I' ? 1 : 0),
                                $fpevent->getSensorid(),
                                $fpevent->getLogid());
                $ctrlAccEvents[] = $ctrlAccEvent;
            }
        }
        return $ctrlAccEvents;
    }    
    
    public function syncEvents(){
        
        $id = $this->container->get('ctrlacc.event.service')->getLastIdEvent();
        $fpevents = $this->getBiometricEventsForSync($id);
        $ctrlaccEvents = array();
        if(count($fpevents) > 0){
             $ctrlaccEvents = $this->transformToCtrlAccEvent($fpevents);
             $message = "Hay ".count($ctrlaccEvents)." eventos nuevos por Sincronizar";
             if($ctrlaccEvents){
                 
                if(count($ctrlaccEvents) > 20){
                    $this->container->get('ctrlacc.event.service')->persistALotOfEvents($ctrlaccEvents);
                }else{
                    foreach ($ctrlaccEvents as $event){
                    $this->container->get('ctrlacc.event.service')->persistEvent($event);
                    }
                }
                $message = $message." /n La sincronizacion se realizo con exito";
            }else{
                $message = $message." /n La sincronizacion a fallado";
                }
        }else{
            $message = "No hay eventos nuevos por sincronizar";
        }
     
        
        return $message;
    }
    

    
}
