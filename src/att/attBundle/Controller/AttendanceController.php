<?php
namespace att\attBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



/**
 * Description of AttendanceController
 *
 * @author jorge.molas
 * 
 * @Route("{mode}/attendance", requirements={"mode":"frontend|backend"})
 */
class AttendanceController extends Controller{
    
    
    
    /**
     * @Route("/list", name="att_attendance_list", options={"expose"=true})
     */
    public function listAction(Request $request){
        $atts = $this->get('att.attendance.service')->getAllAttendance($request);
        $options = ['atts' => $atts['paginator'], 'states' => $atts['states']];
       
        return $this->render('attBundle:Attendance:list.pagination.html.twig', $options);
                        
    }
    
     /**
     * @Route("/make/{day}", name="att_attendance_make", options={"expose"=true}) 
     * @param type $day
     */
    public function  makeAction($day = null){
        $day === NULL ? $day = date('Ymd'): NULL;
        
        $result = $this->get('att.wf.attendance.service')->makeAttendances($day);
        return $this->render('attBundle:Default:dump.test.html.twig', ['v' => $result, 'errors' =>  NULL ]);
    }
    
        
    /**
     * @Route("/process/{day}", name="att_attendance_process_day")
     * @Route("/process", name="att_attendance_process")
     * @param type $day
     */     
    public function processAction($day = null){
        $day === NULL ? $day = date('Ymd'): NULL;
        $result = $this->get('att.wf.attendance.service')->processAttendance($day);
        return $this->render('attBundle:Default:dump.test.html.twig',
                                 [
                                    'v' =>  $result, 
                                    'errors' => NULL
                                 ]);
    }
}
