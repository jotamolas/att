<?php

namespace att\attBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atcertificado;
use att\attBundle\Form\WorkFlowCertType;

/**
 * @Route("{mode}/attendance", requirements={"mode":"frontend|backend"} )
*/

class DefaultController extends Controller
{

   
    /**
     * @Route ("/", name="att_attendance_index")
     */
    public function indexAction(){
        $dateFrom = \DateTime::createFromFormat('Ymd', '20160101');
        $dateTo = \DateTime::createFromFormat('Ymd', '20161231');
        
        $result = $this->getDoctrine()->getRepository('attBundle:Atattendance')->resumePresenteeismByDate($dateFrom, $dateTo);
        
        $notification = $this->getDoctrine()->getRepository('attBundle:Atabsencenotification')->sumToDay(new \DateTime());

        
        
        return $this->render('attBundle:Default:index.html.twig',[
            'stats' => $result,
            'notifications_today_qty' => count($notification)
                ]);
    }
    
     /**
     * @Route ("wf", name="atcertificado_workflow")
     * 
     */
    public function  wfAction(){       
        
        $lw = new \att\attBundle\Entity\Atworkflow();
        $lw->getEntityid();
                
        
        $wfs = $this->getDoctrine()->getRepository('attBundle:Atworkflow')
                ->findByWorkflow($this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));
        
        $certificates = $this->getDoctrine()->getRepository('attBundle:Atcertificate')->findAll();
        
        foreach ($certificates as $certificate){
            $idscertificates[] = $certificate->getId();
        }
        
        foreach ($wfs as $wf){            
            $idscertificatesWithWorkflow [] = $wf->getEntityid();
        }
        
        $result = array_diff($idscertificates, $idscertificatesWithWorkflow);
        
        var_dump($result);
        //var_dump($idscertificatesWithWorkflow);
        
        
        /*$certificadoWorkFlow = $this->get('wf.certificate');           
        
        $certificado = new \att\attBundle\Workflow\Entity\WorkFlowCertificate($certificadoWorkFlow);
        $certificado->setCertificate(
                $this->getDoctrine()->getRepository('attBundle:Atcertificate')->find(2));
        
        //$atcertificado = $this->get('workflow.manager')->castToClass($certificado->getParentClass(), $certificado); 
        //$atcertificado->setWorkflow($this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->findOneByServiceid($certificadoWorkFlow->getServiceId()));

        //print $certificado->getStatekey();
        var_dump($certificado);
        
        //$persistedWf = $this->get('workflow.manager')->persistWorkFlow($atcertificado);
        
        
        //$certificado->getState($certificadoWorkFlow)->setCertificadoAsAguardandoAnalisis($certificado);
        //print $certificado->getStatekey();
        //print $certificadoWorkFlow->getName();
        //print $certificadoWorkFlow->getServiceID();
        */
    }
    

    
   
    /**
     * @Route ("crearasistencia", name="crearassitenciapruebas")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    
    public function crearUnaInasistencia(){
        
        $em = $this->getDoctrine()->getManager();
        $plan = new \att\attBundle\Entity\Atplan();
        
        
        $plan ->setDate(new \DateTime('2016-08-01 08:00:00'))
              ->setEmployee( $this->getDoctrine()->getRepository('employeeBundle:Atemployee')->findOneByDni('39058989'))
              ->setHsworkplan(6)
              ->setInplan(new \DateTime('2016-08-01 08:00:00'))
              ->setOutplan(new \DateTime('2016-08-01 08:00:00'))
              ->setStateplan($this->getDoctrine()->getRepository('attBundle:Atstateplan')->find(1))              
              ;
        
        $em->persist($plan);
        $em->flush();
        $att = new \att\attBundle\Entity\Atattendance();
        $att
            ->setPlan($plan)    
            ->setHsworked(NULL)
            ->setInatt(NULL)
            ->setOutatt(NULL)    
            ->setLogin(NULL)
            ->setLogout(NULL)
            ->setStateattendance($this->getDoctrine()->getRepository('attBundle:Atstateatt')->find(2));    
        $em->persist($att);
        $em->flush();
        $falta = new \att\attBundle\Entity\Atabsence();
        $falta->setAttendance($att)
                
               ->setId(1)
               ->setStatejustif(0)
               ->setCertification(0);        
      
        
       
       $em->persist($falta);
       $em->flush();
       
       return new \Symfony\Component\HttpFoundation\Response('<p>se ha echo</p>');
    }
    
    /**
     * @Route("loadLightGalleryJs", name="loadLightGalleryJs")
     * @return type
     */
    public function loadLightGalleryJsAction(){
        return $this->render('attBundle:Default:lightgallery.javascript.html.twig');
    }
}