<?php

namespace att\ctrlaccBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/ctrlacc/attendance")
 */
class AttendanceController extends Controller{    
    
    
    /**
     * @Route("/list" , name="ctrlacc_attendance_list")
     */
    public function listAction(){
                        
        $ctrlacc = $this->getDoctrine()->getEntityManager('ctrlacc');
        $atts = $ctrlacc->getRepository('ctrlaccBundle:Attendance')->findBy([],['date' => 'DESC']);
        
        $result = array();
        if($atts){
            foreach ($atts as $att){
                
                $employee = $this->getDoctrine()->getRepository("employeeBundle:Atemployee")->findOneByDni($att->getEmployee());
                if($employee){
                   $result[] = [
                       'empName' => $employee->getSurname().", ".$employee->getName(),
                       'empDni'  => $employee->getDni(),
                       'attDate' => $att->getDate(),
                       'attIn'   => $att->getInEvent(),
                       'attOut'  => $att->getOutEvent(),
                       'attHsWk' => $att->getHoursWorkedTime()
                           ];     
                }else{
                   $result[] = [
                       'empName' => NULL,
                       'empDni'  => $att->getEmployee(),
                       'attDate' => $att->getDate(),
                       'attIn'   => $att->getInEvent(),
                       'attOut'  => $att->getOutEvent(),
                       'attHsWk' => $att->getHoursWorkedTime() 
                   ];
                }
            }
        }    
    //return $this->render("attBundle:Default:dump.test.html.twig",['v' => $result, 'errors' => false]);    
    return $this->render("ctrlaccBundle:Attendance:list.html.twig",['atts' => $result]);
    }
    
    
    /**
     * @Route("/make/{day}")
     * @param type $day
     */
    public function makeAttendance($day){                
        $result = $this->get('ctrlacc.attendance.service')->makeAttendance($day);        
        if($result['status'] == 'ok'){ 
            
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
            }
    }
    
    
     /**
     * @Route("/complete/{day}")
     * @param type $day
     */
    public function completeAttendance($day){
        $result = $this->get('ctrlacc.attendance.service')->completeAttendance($day);  
        if($result['status'] == 'ok'){ 
            
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
            }
    }
}
