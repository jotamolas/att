<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace att\syncBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use att\ctrlaccBundle\Entity\DeviceMetadataConfiguration;
use att\syncBundle\Entity\Atexternsystem;
use att\syncBundle\Entity\ExternalDatabase;

/**
 * Description of AxtonService
 *
 * @author jorge.molas
 */
class RemoteService {

    protected $container;
    protected $em;

    /**
     * @param EntityManager $em
     * @param ContainerInterface $container
     */
    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function syncEvents(Atexternsystem $system, DeviceMetadataConfiguration $metadata) {

        switch ($system->getType()->getDescription()) {
            case 'Database':
                $result = $this->container->get('sync.external.database.service')->syncEventsFromExternalDatabase($system, $metadata);
                break;
        }
        //dump($result);
        if ($result['status']) {            
            $new_secuence = $this->container->get('sync.external.database.service')->updateDatabaseSequence(
                    $result['sequence'],
                    $result['ctrlacc_last_id_by_device_persisted'],
                    $result['ctrlacc_last_id_by_device_persisted'] + 1
                    );
            $result['secuence_new'] = $new_secuence;
            
            return $result;
        }
        
        return $result;
    }
   

    /**
     * 
     * @param type $fpevents
     * @return type
     * 
     */
    public function processRemoteEventsToCtrlAccEvent($fpevents, Atexternsystem $system, $inEventValue, $outEventValue) {

        $ctrlacc_events = [];        
        /* creo los eventos como objetos event del módulo crlacc*/
        foreach ($fpevents as $fpevent) {            
            $fpevent['eventtype'] === $inEventValue ? $converted_event = 1 : $converted_event = 0 ;
            $ctrlAccEvent = $this->container
                    ->get('ctrlacc.event.service')
                    ->createEvent(
                    $fpevent['employee'], date_create_from_format("Y-m-d H:i:s",gmdate("Y-m-d 00:00:00",$fpevent['eventdate'])), date_create_from_format("Y-m-d H:i:s",gmdate("Y-m-d H:i:s",$fpevent['eventtime'])), $converted_event, $system, $fpevent['idoriginal']);
            $ctrlacc_events[] = $ctrlAccEvent;
            }
        
        /* persisto los eventos y devuelo la cantidad de persistidos, el ultimo id de el sistema*/
        $result = $this->container->get('ctrlacc.event.service')->persistEvents($ctrlacc_events); 
        $last_id_persisted = $this->em->getRepository('ctrlaccBundle:Event')->getLastIdOrginalByDevice(
                $this->em->getRepository('ctrlaccBundle:Device')->findOneBy(['system' => $system]));
        
        return [
            'ctrlacc_event_persisted' => count($result['events']),
            'ctrlacc_event_error_persisting' => $result['errors_persist'],
            'ctrlacc_last_id_by_device_persisted' => $last_id_persisted
        ];
    }
        
    public function updateExternalSystemSequence(\att\syncBundle\Entity\ExternalSystemSequence $secuence, $new_last, $new_next){
        
        $secuence->setLast($new_last)
                ->setNext($new_next)
                ->setLastDateConnection(new \DateTime());
        $this->em->flush($secuence);
        return $secuence;
    }
    
    
    
}
