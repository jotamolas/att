<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of CompanyController
 *
 * @author JotaMolas
 * @Route("{mode}/employee/company",requirements={"mode":"frontend|backend"})
 */
class CompanyController extends Controller {

    /**
     * @Route("/",name="employee_company_index", options={"expose"=true})
     */
    public function indexAction(Request $request) {

        return $this->render("employeeBundle:Company:index.html.twig");
    }

    /**
     * @Route("/create/{desc}", name="employee_company_create")
     * @param type $desc
     */
    public function createAction($desc) {

        $company = new \att\employeeBundle\Entity\Atcompany();
        $company->setDescription($desc);
        $em = $this->getDoctrine()->getManager();
        $em->persist($company);
        $em->flush();
    }

    /**
     * @Route("/new" , name="employee_company_new", options={"expose"=true})
     * @return type
     */
    public function newAction() {
        $company = new \att\employeeBundle\Entity\Atcompany();
        
        $form = $this->createForm(new \att\employeeBundle\Form\CompanyType(), $company);
        
        return $this->render('employeeBundle:Company:new.html.twig', array(
                    'mode' => $this->get('security.context')->getToken()->getProviderKey(),
                    'form' => $form->createView(),
        ));
    }

    /**
     * 
     * @Route("/list")
     * 
     */
    public function listAction() {

        $companies = $this->getDoctrine()->getRepository("employeeBundle:Atcompany")->findAll();
        return $this->render("employeeBundle:Company:list.html.twig", [
                    'companies' => $companies,
                    'mode' => $this->get('security.context')->getToken()->getProviderKey()
        ]);
    }

}
