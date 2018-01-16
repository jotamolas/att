<?php

namespace att\ctrlaccBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("{mode}/ctrlacc/attendance", requirements={"mode":"frontend|backend"})
 */
class AttendanceController extends Controller{    
    
    
    /**
     * @Route("/list" , name="ctrlacc_attendance_list")
     */
    public function listAction(Request $request){        
        $atts = $this->get('ctrlacc.attendance.service')->pagination($request);
        $options = ['atts' => $atts['paginator']];        
        return $this->render('ctrlaccBundle:Attendance:list.pagination.html.twig', $options);
    }
    
    
    /**
     * @Route("/make/{day}")
     * @param type $day
     */
    public function makeAttendance($day){                
        $result = $this->get('ctrlacc.attendance.service')->makeAttendance($day);   
        dump($result);
       /* if($result['status'] == 'ok'){ 
            
            return  $this->render('ctrlaccBundle:Attendance:make.html.twig', 
                                  [
                                   'action' => 'make',    
                                   'atts' => $result['atts'],
                                   'status' => $result['status'],
                                    'errors'  => $result['errors']
                                    ]
                                );
        }else{
                return  $this->render('ctrlaccBundle:Attendance:make.html.twig', 
                                  [
                                   'action' => 'make', 
                                   'status' => $result['status'],
                                    'errors'  => $result['errors'],
                                    'atts'   => array()
                                    ]
                                );
            }*/
    }
    
    
     /**
     * @Route("/complete/{day}")
     * @param type $day
     */
    public function completeAttendance($day){
        $result = $this->get('ctrlacc.attendance.service')->completeAttendance($day);  
        dump($result);
        /*if($result['status'] == 'ok'){ 
            
            return  $this->render('attBundle:Default:dump.test.html.twig', 
                                  [
                                   'v' => [   
                                   'action' => 'complete',   
                                   'atts' => $result['atts'],
                                   'status' => $result['status'],
                                   'errors'  => $result['errors']
                                    ],
                                     'errors' => FALSE
                                  ]  
                                );
        }else{
                return  $this->render('attBundle:Default:dump.test.html.twig', 
                                  ['v' => [
                                   'action' => 'complete',
                                   'status' => $result['status'],
                                    'errors'  => $result['errors'],
                                    'atts'   => array()
                                    ],
                                     'errors' => FALSE
                                  ]   
                                );
            }*/
    }
}
