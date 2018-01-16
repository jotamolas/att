<?php

namespace att\attBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atcertificado;
use att\attBundle\Form\WorkFlowCertType;

/**
 * @Route("{mode}/attendance", requirements={"mode":"frontend|backend"} )
 */
class DefaultController extends Controller {

    /**
     * @Route ("/", name="att_attendance_index")
     */
    public function indexAction() {


        $last_month_ini = new \DateTime("first day of last month");
        $last_month_end = new \DateTime("last day of last month");

        $to_day_ini = new \DateTime("now");
        $to_day_end = new \DateTime("now");

        $resume_absenteeism_last_month = $this->getDoctrine()->getRepository('attBundle:Atattendance')->resumePresenteeismByDate(
                $last_month_ini->setTime('00', '00', '00'), $last_month_end->setTime('23', '59', '59'));

        $resume_absenteeism_today = $this->getDoctrine()->getRepository('attBundle:Atattendance')->resumePresenteeismByDate(
                $to_day_ini->setTime('00', '00', '00'), $to_day_end->setTime('23', '59', '59'));

        $notification = $this->getDoctrine()->getRepository('attBundle:Atabsencenotification')->sumToDay(new \DateTime());

        dump($resume_absenteeism_today);
        dump($resume_absenteeism_last_month);
        return $this->render('attBundle:Default:index.html.twig', [
                    'resume_absenteeism_last_month' => $resume_absenteeism_last_month,
                    'resume_absenteeism_today' => $resume_absenteeism_today,
                    'last_month_ini' => $last_month_ini,
                    'last_month_end' => $last_month_end,
                    'notifications_today_qty' => count($notification)
        ]);
    }

    /**
     * @Route("/prplan", name="att_plan_prueba")
     */
    public function pruebaAction() {

        /* $contract = $this->getDoctrine()->getRepository('employeeBundle:Atcontract')->find([
          'employee' => 5835,
          'business' => 3
          ]);
          $dt = new \DateTime();

          dump($contract->getInTime()->format('H:i'));
          dump(date_create_from_format("Y-m-d H:i", $dt->format('Y-m-d')." ". $contract->getInTime()->format('H:i')));
          dump($contract); */
        $date = date_create_from_format('Ymd', '20171022');
        dump($date);
        dump($date->format('l'));
    }

    /**
     * @Route("/resume", name="att_plan_resume")
     */
    public function resumeHsWorkedAction() {
        $last_month_ini = new \DateTime("first day of last month");
        $last_month_end = new \DateTime("last day of last month");
        $resume = $this->getDoctrine()->getRepository('attBundle:Atattendance')->resumeHsWorkedToPayement($last_month_ini->setTime('00', '00', '00'), $last_month_end->setTime('23', '59', '59'));
        $response = $this->render('attBundle:Attendance:resume.csv.twig', array('resume' => $resume));
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');
        return $response;
       // dump($resume);
    }

    /**
     * @Route ("wf", name="atcertificado_workflow")
     * 
     */
    public function wfAction() {

        $lw = new \att\attBundle\Entity\Atworkflow();
        $lw->getEntityid();


        $wfs = $this->getDoctrine()->getRepository('attBundle:Atworkflow')
                ->findByWorkflow($this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));

        $certificates = $this->getDoctrine()->getRepository('attBundle:Atcertificate')->findAll();

        foreach ($certificates as $certificate) {
            $idscertificates[] = $certificate->getId();
        }

        foreach ($wfs as $wf) {
            $idscertificatesWithWorkflow [] = $wf->getEntityid();
        }

        $result = array_diff($idscertificates, $idscertificatesWithWorkflow);

        var_dump($result);
        //var_dump($idscertificatesWithWorkflow);


        /* $certificadoWorkFlow = $this->get('wf.certificate');           

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
    public function crearUnaInasistencia() {

        $em = $this->getDoctrine()->getManager();
        $plan = new \att\attBundle\Entity\Atplan();


        $plan->setDate(new \DateTime('2016-08-01 08:00:00'))
                ->setEmployee($this->getDoctrine()->getRepository('employeeBundle:Atemployee')->findOneByDni('39058989'))
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
    public function loadLightGalleryJsAction() {
        return $this->render('attBundle:Default:lightgallery.javascript.html.twig');
    }

}
