<?php
namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Description of BusinessController
 *
 * @author JotaMolas
 * @Route("{mode}/employee/department",requirements={"mode":"frontend|backend"})
 */
class DepartmentController extends Controller{

    
    /**
     * @Route("/new",name="employee_department_new", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function newAction(Request $request){
        
        $dpt = new \att\employeeBundle\Entity\Atdepartment();
        
        $form = $this->createForm(new \att\employeeBundle\Form\DepartmentType(), $dpt);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($dpt);
            $em->flush();

            return $this->redirectToRoute('employee_department_list', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]);
        }
        return $this->render('employeeBundle:Department:form.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    
    /**
     * 
     * @Route("/list",name="employee_department_list")
     * 
     */
    public function listAction(){
        
        $departments = $this->getDoctrine()->getRepository("employeeBundle:Atdepartment")->findAll();
        return $this->render("employeeBundle:Department:list.html.twig", [
            'departments' => $departments,
            'mode' => $this->get('security.context')->getToken()->getProviderKey() 
                ]);
        
    }
    
    
    
    
    
}
