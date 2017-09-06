<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use DataDog\PagerBundle\Pagination;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("{mode}/employee", requirements={"mode":"frontend|backend"})
 */
class DefaultController extends Controller {


    /**
     * @Route("/" , name="employee_index", options={"expose"=true})
     * 
     */
    public function indexAction(Request $request) {

        return $this->render('employeeBundle:Default:index.html.twig');
    }    
    
    
    /**
     * @Method({"GET","POST"})
     * @Route("/test", name="employee_test")
     * 
     */
    public function testAction(Request $request) {
        
        
    }

    /**
     * @param Request $request
     * @Method({"GET","POST"})
     * @Route("/edit/collection", name="employee_form_collection_edit", options={"expose"=true})
     */
    public function gridAction(Request $request) {


        $paginator = $this->get('employee.service')->pagination($request);

        $form = $this->createForm(new \att\employeeBundle\Form\AllEmployeeType(), [
            'employees' => $paginator['paginator'],
                ], [
            'action' => $this->generateUrl('employee_form_collection_update'),
            'method' => 'POST'
                ]
        );

        $options = [
            'form' => $form->createView(),
            'employees' => $paginator['paginator'],
            'gender' => $paginator['gender']
        ];

        $request->isXmlHttpRequest() ?
                        $page = $this->render('employeeBundle:Employee:form.pagination.collection.html.twig', $options) :
                        $page = $this->render('employeeBundle:Employee:form.pagination.collection.html.twig', $options);

        return $page;
    }

    /**
     * @param Request $request
     * @Method({"POST"})
     * @Route("/update/collection", name="employee_form_collection_update", options={"expose"=true})
     */
    public function gridUpdateAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $paginator = $this->get('employee.service')->pagination($request);

        $form = $this->createForm(new \att\employeeBundle\Form\AllEmployeeType(), [
            'employees' => $paginator['paginator'],
                ], [
            'action' => $this->generateUrl('employee_form_collection_update'),
            'method' => 'POST'
                ]
        );

        $options = [
            'form' => $form->createView(),
            'employees' => $paginator['paginator'],
            'gender' => $paginator['gender']
        ];

        $form->handleRequest($request);

        /*
          print "<pre>";
          var_dump($paginator['paginator']);
          print "</pre>"; */

        if ($form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse([
                'message' => 'ok',
                    ], 200);
        }

        return new JsonResponse(
                [
            'message' => 'fail',
            'form' => $this->renderView('employeeBundle:Employee:form.pagination.collection.ajax.html.twig', $options)
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @Route("/edit/test", name="employee_edit_test", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function testEdit(Request $request) {
        //$paginator = $this->get('employee.service')->pagination($request);

        $employees = $this->getDoctrine()->getRepository('employeeBundle:Atemployee')->findBySurname('molas');
        $form = $this->createForm(new \att\employeeBundle\Form\AllEmployeeType($employees), NULL, [
            'action' => $this->generateUrl('employee_edit_test'),
            'method' => 'POST'
                ]
        );

        $options = [
            'form' => $form->createView(),
                //'employees' => $paginator['paginator'],
                //'gender' => $paginator['gender']
        ];

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                return $this->render("attBundle:Default:dump.test.html.twig", ['v' => $employees, 'errors' => null]);
            } else {

                $errors = $form->getErrors();
                $errorCollection = array();
                foreach ($errors as $error) {
                    $errorCollection[] = $error->getMessageTemplate();
                }
                //print"<pre>";
                return $this->render("attBundle:Default:dump.test.html.twig", ['v' => $errorCollection, 'errors' => null]);

                //print"</pre>";
            }
        }
        return $this->render('employeeBundle:Employee:edit.html.twig', $options);
    }









   

}
