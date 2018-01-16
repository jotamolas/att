<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use att\attBundle\Entity\Ataditionalhoursdetail;
use att\attBundle\Entity\Ataditionalhourstype;

/**
 * Description of AttendanceController
 *
 * @author jorge.molas
 * 
 * @Route("{mode}/attendance", requirements={"mode":"frontend|backend"})
 */
class AttendanceController extends Controller {

    /**
     * @Route("/list", name="att_attendance_list", options={"expose"=true})
     */
    public function listAction(Request $request) {
        $atts = $this->get('att.attendance.service')->getAllAttendance($request);
        $options = ['atts' => $atts['paginator'], 'states' => $atts['states']];

        return $this->render('attBundle:Attendance:list.pagination.html.twig', $options);
    }
    
    
    /**
     * @Route("/export", name="att_attendance_export", options={"expose"=true})
     */
    public function exportAction(Request $request) {
        $atts = $this->get('att.attendance.service')->getAllAttendance($request);
        $options = ['atts' => $atts['paginator'], 'states' => $atts['states']];

        return $this->render('attBundle:Attendance:list.pagination.html.twig', $options);
    }
    

    /**
     * @Route("/make/{day}", name="att_attendance_make", options={"expose"=true}) 
     * @param type $day
     */
    public function makeAction($day = null) {

        $day === NULL ? $day = date('Ymd') : NULL;

        $result = $this->get('att.wf.attendance.service')->makeAttendances($day);
        return $this->render('attBundle:Attendance:message.make.html.twig', ['result' => $result]);
    }

    /**
     * @Route("/process/{day}", name="att_attendance_process_day")
     * @Route("/process", name="att_attendance_process")
     * @param type $day
     */
    public function processAction($day = null) {
        $day === NULL ? $day = date('Ymd') : NULL; // Si no hay dia indicado le asigno el dia de hoy
        // llamo a processAction de WfAttendanceService
        $result = $this->get('att.wf.attendance.service')->processAttendance($day);

        return $this->render('attBundle:Attendance:message.process.html.twig', [
                    'result' => $result,
        ]);
    }

    /**
     * @Route("/process/overtime/{day}", name="att_attendance_process_overtime_day")
     * @ParamConverter("day", options={"format": "Ymd"})
     */
    public function processOverTime(\DateTime $day) {

        $plans = $this->getDoctrine()->getRepository('attBundle:Atplan')->findBy([
            'date' => $day
        ]);

        $attendances = $this->getDoctrine()->getRepository('attBundle:Atattendance')->findBy([
            'plan' => $plans,
            'stateattendance' => 1
        ]);
        
        
        

        $em = $this->getDoctrine()->getManager();

        foreach ($attendances as $att) {

            $hours = (int) $att->getHsworked()->format('h');
            $minutes = (int) $att->getHsworked()->format('i') / 60;
            $decimal_hour_worked = $hours + $minutes;
            // dump($decimal_hour_worked - $att->getPlan()->getHsworkplan());
            if (($decimal_hour_worked - $att->getPlan()->getHsworkplan()) > (1 / 2)) {
                $overtime = new \att\attBundle\Entity\Ataditionalhoursdetail();
                $overtime->setAttendance($att)
                        ->setAmount($decimal_hour_worked - $att->getPlan()->getHsworkplan());
                //dump($overtime);
                $em->persist($overtime);
            } else {
                $att->setHsworkedtopayment($att->getPlan()->getHsworkplan());
            }
            $em->flush();
            //dump($att);            
        }
    }

    /**
     * @Route("/auth/aditional/hours/{aditionalhours}", name="att_attendance_auth_aditionalhours")
     * @param \att\attBundle\Entity\Ataditionalhoursdetail $aditionalhours
     */
    public function authAditionalHoursAction(Request $rqst, Ataditionalhoursdetail $aditionalhours) {

        $form = $this->createForm(new \att\attBundle\Form\AuthAditionalHoursType(), $aditionalhours, [
            'agreement_id' => '1',
            'action' => $this->generateUrl('att_attendance_auth_aditionalhours', [
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey(),
                'aditionalhours' => $aditionalhours->getId()
            ]),
            
        ]);

        $form->handleRequest($rqst);
        if ($form->isSubmitted() && $form->isValid()) {
            if($aditionalhours->getIsapproved()){
                /* Si se aprueban las horas extras se le ponen*/
             $aditionalhours->getAttendance()->setHsworkedtopayment(
                     $aditionalhours->getAttendance()->getPlan()->getHsworkplan()+
                     $aditionalhours->getAmount()
                     );   
            }else{
                $aditionalhours->getAttendance()->setHsworkedtopayment(
                        $aditionalhours->getAttendance()->getPlan()->getHsworkplan());
            }            
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute("att_attendance_list", [
                        'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
            ]);
        }
        
        return $this->render('attBundle:Attendance:auth.aditionalhours.html.twig',[
            'form' => $form->createView()
        ]);
    }
    
    
    

}
