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
 * @Route("{mode}/employee/business",requirements={"mode":"frontend|backend"})
 */
class BusinessController extends Controller{
    
    /**
     * @Route("/",name="employee_business_index", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function indexAction(Request $request){
        
        return $this->render("employeeBundle:Business:index.html.twig");        
        
    }
    
    
    
    /**
     * 
     * @param type $desc
     * @param type $country
     * @param type $state
     * @param type $companyid
     * @Route("/create/{desc}/{country}/{state}/{companyid}")
     */
    public function createAction($desc, $country, $state, $companyid){
        
        $business = new \att\employeeBundle\Entity\Atbusiness();
        $business->setState($state)
                ->setCompany($this->getDoctrine()->getRepository('employeeBundle:Atcompany')->find($companyid))
                ->setCountry($country)
                ->setDescription($desc)
        ;
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($business);
        $em->flush();
    }
    
    /**
     *  @Route("/new",name="employee_business_new", options={"expose"=true})
     * @return type
     */
    public function newAction(){
        $business = new \att\employeeBundle\Entity\Atbusiness();
        $form = $this->createForm(new \att\employeeBundle\Form\BusinessType(), $business);
        return $this->render('employeeBundle:Business:new.html.twig', array(
            'mode' => $this->get('security.context')->getToken()->getProviderKey(),
            'form' => $form->createView(),
        ));
    }
    
    
    /**
     * 
     * @Route("/list")
     * 
     */
    public function listAction(){
        
        $business = $this->getDoctrine()->getRepository("employeeBundle:Atbusiness")->findAll();
        return $this->render("employeeBundle:Business:list.html.twig", [
            'business' => $business,
            'mode' => $this->get('security.context')->getToken()->getProviderKey() 
                ]);
        
    }
    
    
    
    
    
}
