<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("/schema")
 */
class SchemaController extends Controller {

    /**
     * 
     * @Route("/ajax/list", name="employee_schema_ajax_list", condition="request.headers.get('X-Requested-With') == 'XMLHttpRequest'")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxListAction(Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        
        $agreement_id = $request->request->get('agreement_id');
        
        $schemas = $em->getRepository('employeeBundle:Atschema')->findByAgreementId($agreement_id);

        return new JsonResponse($schemas);
    }
    
    /**
     * 
     *
     * @Route("/", name="employee_schema_list")
     * @Method("GET")
     */
    public function listAction(Request $request){
        $schemas = $this->getDoctrine()->getRepository('employeeBundle:Atschema')->findAll();
        return $this->render('employeeBundle:Schema:list.html.twig', array(
            'schemas' => $schemas,
            'mode' => $this->get('security.context')->getToken()->getProviderKey() 
            
        ));
    }
   

}
