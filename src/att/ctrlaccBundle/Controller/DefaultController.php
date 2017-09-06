<?php

namespace att\ctrlaccBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/ctrlacc")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/prueba")
     */
    public function pruebas(){
        
       /* 
       $now = new \DateTime();
       
       ($now->format('H:i:s') >= $this->container->getParameter('ctrlacc.attendance.workingday.end') 
               ? $now
               : $now->modify('-1 Day')); 
       
       $dates = $this->get('util.workingday.service')->checkWorkingDay($now->format('Ymd'));
       
       $range = $dates['start']->diff($dates['end']);
       
       print "<pre>";
       var_dump($dates);
       var_dump($range);
       print "</pre>";       
       // $startRange = date_create_from_format(n)
        
        */
       $ctrlacc = $this->getDoctrine()->getEntityManager('ctrlacc');
        $atts = $ctrlacc->getRepository('ctrlaccBundle:Attendance')->findAll();
        //print $atts;
        foreach ($atts as $att){
            return new \Symfony\Component\HttpFoundation\Response($att . "\n");
            
        }
       //$logger = $this->get('monolog.logger.ctrlacc');
      // var_dump($logger->info('probando el loggerrrrrr'));
      
    }
    
    /**
     * 
     * @Route("/devicetype/new/{description}")
     * 
     */
    public function createDeviceTypeAction($description){
        
        $deviceType = new \att\BundlectrlaccBundle\Entity\DeviceType();
        $deviceType->setDescription($description);
        
        $em = $this->getDoctrine()->getManager('ctrlacc');
        $em->persist($deviceType);
        $em->flush();
        $em->clear();
        
        return new \Symfony\Component\HttpFoundation\Response("<h1>Se agrego el Device Type con Exito</h1>");
        
    }
    
    
    /**
     * @Route("/device/new/{description}/{ip}/{port}/{destype}")
     * @param type $destype
     * @param type $description
     * @param type $ip
     * @param type $port
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createDeviceAction($description,$ip,$port,$destype){
        $device = new \att\ctrlaccBundle\Entity\Device();
        
        $device->setDescription($description)
                ->setIp($ip)
                ->setPort($port)
                ->setType($this->getDoctrine()->getRepository('ctrlaccBundle:DeviceType', 'ctrlacc')->findOneByDescription($destype));
        
        $em = $this->getDoctrine()->getManager('ctrlacc');
        $em->persist($device);
        $em->flush();
        $em->clear();
                return new \Symfony\Component\HttpFoundation\Response("<h1>Se agrego el Device con Exito</h1>");

    }
}
