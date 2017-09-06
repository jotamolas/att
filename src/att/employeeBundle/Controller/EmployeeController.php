<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\employeeBundle\Entity\Atemployee;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Atemployee controller.
 *
 * @Route("{mode}/employee", requirements={"mode":"frontend|backend"})
 */
class EmployeeController extends Controller {

    /**
     * @Route("/import", name="employee_import", options={"expose"=true} )
     * 
     */
    public function importAction() {
        
    }

    /**
     * @Route("/sync/axton/", name="employee_sync_axton", options={"expose"=true})
     */
    public function syncEmployeesFromAxtonAction() {

        $logger = $this->get('monolog.logger.employee');
        $newEmployees = $this->container->get("employee.service")->syncNewEmployeesFromAxton();
        $activeEmployees = $this->container->get("employee.service")->syncActiveEmployeesFromAxton();
        $logger->info("Employees News: " . count($newEmployees['employees']) . " Employees Active Updated: " . count($activeEmployees['employees']));
        return $this->render('employeeBundle:Employee:import.list.html.twig', [
                    'qcreated' => count($newEmployees['employees']),
                    'qupdated' => count($activeEmployees['employees']),
                    'news' => $newEmployees['employees'],
                    'updated' => $activeEmployees['employees'],
                    'errors' => array_merge($newEmployees['errors'], $activeEmployees['errors'])
        ]);
    }

    /**
     * Lists all Atemployee entities.
     *
     * @Route("/list", name="employee_list",options={"expose"=true})
     * @Method("GET")
     */
    public function listAction(Request $request) {
        $employees = $this->get('employee.service')->pagination($request);
        $options = [
            'employees' => $employees['paginator'],
            'gender' => $employees['gender']
        ];

        return $this->render('employeeBundle:Employee:list.pagination.html.twig', $options);
    }

    /**
     * Finds and displays a Atemployee entity.
     *
     * @Route("/show/{id}", name="employee_show",options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Atemployee $atemployee) {
        return $this->render('employeeBundle:Employee:show.html.twig', array(
                    'employee' => $atemployee,
        ));
    }

    /**
     * 
     * @param Request $request
     * @Route("/new", name="employee_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $entity = new Atemployee();

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeStepType(), $entity);
        return $this->render('employeeBundle:Employee:new.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @param Request $request
     * @Route("/new/step", name="employee_new_step")
     * @return type
     */
    public function newStep(Request $request) {

        $entity = new Atemployee();
        $formStep1 = $this->createForm(new \att\employeeBundle\Form\EmployeePersonalType(), $entity, [
            'action' => $this->generateUrl('employee_create_step_1', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        return $this->render('employeeBundle:Employee:new.step.html.twig', [
                    'formStep1' => $formStep1->createView(),
                    'googleapitoken' => $this->get('util.google.service')->getToken()
        ]);
    }

    /**
     * 
     * @param Request $request
     * @Route("/create/step/1", name="employee_create_step_1")
     * @return JsonResponse
     */
    public function createStep1(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }
        $entity = new Atemployee();
        $formStep1 = $this->createForm(new \att\employeeBundle\Form\EmployeePersonalType(), $entity, [
            'action' => $this->generateUrl('employee_create_step_1', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $formStep1->handleRequest($request);

        if ($formStep1->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);


            $formStep2 = $this->createForm(new \att\employeeBundle\Form\EmployeeContactType(), $entity, [
                'action' => $this->generateUrl('employee_create_step_2', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                'method' => 'POST'
            ]);

            $serializeEntity = $this->get('jms_serializer')->serialize($entity, 'json');



            return new JsonResponse([
                'entity' => $serializeEntity,
                'message' => 'ok',
                'form' => $this->renderView('employeeBundle:Employee:form.contact.html.twig', [
                    'form' => $formStep2->createView(),
                ])
                    ], 200);
        }


        return new JsonResponse(
                [
            'form' => $this->renderView('employeeBundle:Employee:form.personal.html.twig', [
                'form' => $formStep1->createView(),
            ])
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @Route("/create/step/2", name="employee_create_step_2")
     * @return JsonResponse
     */
    public function createStep2(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $serializer = $this->get('jms_serializer');
        $entity = $serializer->deserialize($request->get('entity'), 'att\employeeBundle\Entity\Atemployee', 'json');



        $formStep2 = $this->createForm(new \att\employeeBundle\Form\EmployeeContactType(), $entity, [
            'action' => $this->generateUrl('employee_create_step_2', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $formStep2->handleRequest($request);


        if ($formStep2->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);


            $formStep3 = $this->createForm(new \att\employeeBundle\Form\EmployeeAddressingType(), $entity, [
                'action' => $this->generateUrl('employee_create_step_3', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                'method' => 'POST'
            ]);

            $serializableEntity = $serializer->serialize($entity, 'json');

            return new JsonResponse([
                'message' => 'ok',
                'entity' => $serializableEntity,
                'form' => $this->renderView('employeeBundle:Employee:form.addressing.html.twig', [
                    'form' => $formStep3->createView(),
                    'googleapitoken' => $this->get('util.google.service')->getToken()
                ])
                    ], 200);
        }


        return new JsonResponse(
                [
            'form' => $this->renderView('employeeBundle:Employee:form.contact.html.twig', [
                'form' => $formStep2->createView()
            ])
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @Route("/create/step/3", name="employee_create_step_3")
     * @return JsonResponse
     */
    public function createStep3(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $serializer = $this->get('jms_serializer');
        $entity = $serializer->deserialize($request->get('entity'), 'att\employeeBundle\Entity\Atemployee', 'json');

        $formStep3 = $this->createForm(new \att\employeeBundle\Form\EmployeeAddressingType(), $entity, [
            'action' => $this->generateUrl('employee_create_step_3', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $formStep3->handleRequest($request);


        if ($formStep3->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();


            return new JsonResponse([
                'message' => 'ok',
                'show' => $this->renderView('employeeBundle:Employee:show.html.twig', [
                    'employee' => $entity,
                ])
                    ], 200);
        }


        return new JsonResponse(
                [
            'form' => $this->renderView('employeeBundle:Employee:form.addressing.html.twig', [
                'entity' => $entity,
                'form' => $formStep3->createView(),
                'googleapitoken' => $this->get('util.google.service')->getToken()
            ])
                ], 200);
    }

    /**
     * @Route("/autocomplete", name="employee_autocomplete_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompleteAction(Request $request) {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $term = $request->query->get('q');

        $em = $this->getDoctrine()->getManager();
        $employees = $em->getRepository('employeeBundle:Atemployee')->getByTerm($term);

        return new JsonResponse($employees);
    }

    /**
     * 
     * @Route("/edit/{id}", name="employee_edit", options={"expose"=true})
     * @Method({"GET","POST"})
     * 
     */
    public function editAction(\att\employeeBundle\Entity\Atemployee $employee) {

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeType(), $employee, [
            'action' => $this->generateUrl('employee_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );

        return $this->render('employeeBundle:Employee:form.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/update", name="employee_update", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function updateAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }


        $employee = $this->getDoctrine()->getRepository("employeeBundle:Atemployee")->find($request->request->get('employee-id'));

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeType(), $employee, [
            'action' => $this->generateUrl('employee_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {


            $this->getDoctrine()->getManager()->persist($employee);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'status' => 'ok',
                'row' => $this->renderView('employeeBundle:Employee:show.row.html.twig', [
                    'employee' => $employee,
                    'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                        ]),
                'message' => 'Changes Saved'
                    ], 200);
        }

        return new JsonResponse(
                [
            'status' => 'fail',
            'form' => $this->renderView('employeeBundle:Employee:form.html.twig', [
                'form' => $form->createView()
            ])
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @Route("/delete/{id}", name="employee_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, \att\employeeBundle\Entity\Atemployee $employee) {

        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        return new JsonResponse([
            'status' => 'ok',
            'message' => 'The Employee has been removed'
                ]
                , 200);
    }

    /**
     * 
     * @param Request $request
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @Route("/address/show/{id}", name="employee_address_show", options={"expose"=true})
     */
    public function showAddressAction(Request $request, \att\employeeBundle\Entity\Atemployee $employee) {

        /*  if (!$request->isXmlHttpRequest()) {
          return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
          } */

        return $this->render('employeeBundle:Employee:show.address.html.twig', [
                    'employee' => $employee
        ]);
    }

    /**
     * 
     * @param Request $request
     * @param \att\employeeBundle\Entity\Atemployee $employee
     * @Route("/address/edit/{id}", name="employee_address_edit", options={"expose"=true})
     */
    public function editAddressAction(Request $request, \att\employeeBundle\Entity\Atemployee $employee) {

        /* if (!$request->isXmlHttpRequest()) {
          return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
          } */

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeAddressingType(), $employee, [
            'action' => $this->generateUrl('employee_address_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        return $this->render('employeeBundle:Employee:form.addressing.html.twig', [
                    'form' => $form->createView()
                ])
        ;
    }

    /**
     * 
     * @Route("/address/update", name="employee_address_update", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function updateAddressAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }


        $employee = $this->getDoctrine()->getRepository("employeeBundle:Atemployee")->find($request->request->get('employee-id'));

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeAddressingType(), $employee, [
            'action' => $this->generateUrl('employee_address_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);


        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->getDoctrine()->getManager()->persist($employee);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'message' => 'ok',
                    ], 200);
        }

        return new JsonResponse(
                [
            'status' => 'fail',
            'form' => $this->renderView('employeeBundle:Employee:form.addressing.html.twig', [
                'form' => $form->createView()
            ])
                ], 200);
    }
    
    /**
     * @Route("/contact/info/show/{employee}", name="att_employee_contact_info", options={"expose"=true})
     * @param Atemployee $employee
     */
    public function showContactInfoAction(Atemployee $employee){
        
        return $this->render("employeeBundle:Employee:show.contact.html.twig", [
            'employee' => $employee,
            'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                ]);
        
    }
    
    
    /**
     * 
     * @Route("/import/from/file", name="employee_import_from_file", options={"expose"=true})
     * @param Request $request
     *  
     */
    public function importFromFileAction(Request $request) {

        $form = $this->createForm(new \att\employeeBundle\Form\EmployeeImportFileType(), null, [
            'action' => $this->generateUrl('employee_import_from_file', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($form->isValid()) {

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                        $this->getParameter('kernel.root_dir') . '/../web/uploads/employee/csv', $fileName);

                $reader = \League\Csv\Reader::createFromPath($this->getParameter('kernel.root_dir') . '/../web/uploads/employee/csv/' . $fileName);
                $is_header_valid = $this->get('employee.service')->validateFileHeader($reader->fetchOne());        
                $is_header_valid['error'] ? $form->addError(new \Symfony\Component\Form\FormError($is_header_valid['message'])) : null ;
                
                if($form->isValid()){
                    
                    
                    $result = $this->get('employee.service')->importProcess($reader->fetchAssoc());
                    dump($result);
                    /*$iteratePlans = $this->get('att.plan.service')->iteratePlansFromFile($results);
                    $result = $this->get('att.plan.service')->createOrUpdatePlanFromFile($iteratePlans['validatesPlans']);
                    */
                    return $this->render('employeeBundle:Employee:import.message.html.twig',[
					  'row_updated' => count($result['updated_employees']),
                                          'row_insterted' => count($result['new_employees']),
 					  'errors_setting_entity' => $result['error_setting_entity'],
					  'errors_constraint_violation' => $result['error_contraint_violation'],
                                          'error_exceptions' => $result['error_exceptions']
					  ]);
                }
                
            }
        }

        return $this->render('employeeBundle:Employee:import.file.html.twig', [
                    'form' => $form->createView()
        ]);
    }

}
