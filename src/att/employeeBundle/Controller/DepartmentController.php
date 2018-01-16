<?php
namespace att\employeeBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use att\employeeBundle\Entity\Atdepartment;


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
        
        $form = $this->createForm(new \att\employeeBundle\Form\DepartmentType(), $dpt,[
            'action' => $this->generateUrl("employee_department_new",[
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
            ])
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($dpt);
            $em->flush();
            
            $departments = $this->getDoctrine()->getRepository("employeeBundle:Atdepartment")->findAll();
            
            if($request->isXmlHttpRequest()){
                return new JsonResponse
                ([
                    'status' => true,
                    'message' => $this->get('translator')->trans('Department '.$dpt->getName().' has been created'),
                    'table' => $this->renderView('employeeBundle:Department:list.table.html.twig',[
                        'departments' => $departments,
                        'mode' => $this->get('security.context')->getToken()->getProviderKey()
                    ])
                ]);

            }else{
                return $this->redirectToRoute('employee_department_list',[
                    'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                ]);
            }
            
            /*return $this->redirectToRoute('employee_department_list', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]);*/
        }

        if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'form' => $this->renderView('employeeBundle:Department:form.html.twig',[
                    'form' => $form->createView()
                ])
            ]);

        }else{
            return $this->render('employeeBundle:Department:new.html.twig', array(
            'form' => $form->createView()
        ));
        }
        
    }

    /**
     * @Route("/edit/{department}",name="employee_department_edit", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function editAction(Request $request, Atdepartment $department){
        
        
        
        $form = $this->createForm(new \att\employeeBundle\Form\DepartmentType(), $department,[
            'action' => $this->generateUrl("employee_department_edit",[
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey(),
                'department' => $department->getId()
            ])
        ]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            
            $departments = $this->getDoctrine()->getRepository("employeeBundle:Atdepartment")->findAll();

            if($request->isXmlHttpRequest()){
                return new JsonResponse
                ([
                    'status' => true,
                    'message' => $this->get('translator')->trans('Department '.$department->getName().' has been updated'),
                    'table' => $this->renderView('employeeBundle:Department:list.table.html.twig',[
                        'departments' => $departments,
                        'mode' => $this->get('security.context')->getToken()->getProviderKey()
                    ])
                ]);
            }else{
                return $this->redirectToRoute('employee_department_list',[
                    'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                ]);
            }


            
        }

        if($request->isXmlHttpRequest()){
            return new JsonResponse([
                'form' => $this->renderView('employeeBundle:Department:form.html.twig',[
                    'form' => $form->createView()
                ])
            ]);

        }else{
            return $this->render('employeeBundle:Department:form.html.twig', array(
            'form' => $form->createView()
        ));
        }
        
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
