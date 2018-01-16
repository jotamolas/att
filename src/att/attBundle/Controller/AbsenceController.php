<?php
namespace att\attBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atabsence;


/**
 * Description of AbsenceController
 *
 * @author jorge.molas
 * 
 * @Route("{mode}/absence", requirements={"mode":"frontend|backend"})
 */
class AbsenceController extends Controller{
    
    
    
    /**
     * @Route("/list", name="att_absence_list", options={"expose"=true})
     */
    public function listAction(Request $request){
        $abss = $this->get('att.absence.service')->paginationAbsence($request);
        $options = ['abss' => $abss['paginator']];
        return $this->render('attBundle:Absence:list.pagination.html.twig', $options);  
    }
    
    /**
     * @Route("/manage/{id}", name="att_absence_manage",options={"expose"=true})
     */
    public function manageAction(Atabsence $absence){
        
        $is_notificated = $this->get('att.absence.service')->searchAbsenceNotificactions($absence);
        $is_certificated = $this->get('att.absence.service')->searchWorkflowCertification($absence);        
        
 
        
        return $this->render('attBundle:Absence:manage.html.twig',[
            'absence' => $absence,
            'employee' => $absence->getAttendance()->getPlan()->getEmployee(),
            'notification' => $is_notificated ? $is_notificated : NULL,
            'certificate' => $is_certificated ? $is_certificated['certificate'] : NULL,
            'workflow_certification' => $is_certificated ? $is_certificated['workflow_certification'] : NULL,
            'workleave' => $absence->getWorkleave() ? $absence->getWorkleave() : null,
            'medicalorder' => $absence->getMedicalorder() ? $absence->getMedicalorder() : null
        ]);
    }

}
