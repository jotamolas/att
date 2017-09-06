<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\employeeBundle\Entity\Atcontract;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\Serializer\SerializationContext;

/**
 * @Route("{mode}/contract", requirements={"mode":"frontend|backend"})
 */
class ContractController extends Controller {

    /**
     * @Route("/add/restday/{business}/{employee}", name="employee_contract_add_restday", options={"expose"=true})
     */
    public function addRestDay(Request $request, \att\employeeBundle\Entity\Atbusiness $business, \att\employeeBundle\Entity\Atemployee $employee) {

        $contract = $this->getDoctrine()->getRepository('employeeBundle:Atcontract')->findOneBy([
            'employee' => $employee->getId(),
            'business' => $business->getId()
        ]);
        $form = $this->createForm(new \att\employeeBundle\Form\ContractAddRestDaysType(), $contract, [
            'qty_work_days' => $contract->getSchema()->getDays(),
            'action' => $this->generateUrl("employee_contract_add_restday", [
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey(),
                'business' => $business->getId(),
                'employee' => $employee->getId()
            ]),
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->flush();


            return new JsonResponse(
                    [
                'status' => true,
                'message' => 'Rests day were saved',
                'row' => $this->renderView('employeeBundle:Contract:show.row.html.twig', [
                    'contract' => $contract,
                    'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                ]),
            ]);
        }

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                    [
                'form' => $this->renderView('employeeBundle:Contract:restday.form.html.twig', [
                    'form' => $form->createView()
                ])]
            );
        } else {
            return $this->render('employeeBundle:Contract:restday.form.html.twig', [
                        'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/sync/axton/", name="employee_contract_sync_axton", options={"expose"=true})
     */
    public function syncFromAxtonAction() {

        // $logger = $this->get('monolog.logger.employee');
        $contract = $this->container->get("employee.contract.service")->syncActiveFromAxton();

        // $logger->info("Employees News: ".count($newEmployees['employees'])." Employees Active Updated: ".count($activeEmployees['employees'])); 
        return $this->render('attBundle:Default:dump.test.html.twig', ['v' => $contract, "errors" => null]);
        /* return $this->render('employeeBundle:Employee:import.list.html.twig' ,
          [
          'qcreated' => count($newEmployees['employees']),
          'qupdated' => count($activeEmployees['employees']),
          'news' => $newEmployees['employees'],
          'updated' => $activeEmployees['employees'],
          'errors' => array_merge($newEmployees['errors'], $activeEmployees['errors'])
          ]); */
    }

    /**
     * Lists all Atcontract entities.
     *
     * @Route("/list", name="employee_contract_list")
     * @Method("GET")
     */
    public function listAction(Request $request) {


        $contracts = $this->get('employee.contract.service')->pagination($request);

        $options = [
            'contracts' => $contracts['paginator'],
            'status' => $contracts['status'],
            'schemas' => $contracts['schemas'],
            'agreements' => $contracts['agreements'],
            'companies' => $contracts['companies'],
            'business' => $contracts['business']
        ];

        return $this->render('employeeBundle:Contract:list.pagination.html.twig', $options);
    }

    /**
     * @Route("/schema/import/ccm", name="employee_contract_schema_import_ccm")
     * @Method("GET")
     */
    public function schemaAction() {

        $result = $this->get("employee.contract.service")->importSchemaFromCCM();
        return $this->render("attBundle:Default:dump.test.html.twig", ['v' => $result, 'errors' => NULL]);
    }

    /**
     * @Route("/new", name="employee_contract_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        /* if (!$request->isXmlHttpRequest()) {
          return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
          } */

        $contract = new Atcontract();

        $formStep1 = $this->createForm(new \att\employeeBundle\Form\ContractEmployeeType(), $contract, [
            'action' => $this->generateUrl("employee_contract_create_step_1", ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        return $this->render('employeeBundle:Contract:new.step.html.twig', [
                    'formEmployee' => $formStep1->createView(),
        ]);
    }

    /**
     * @Route("/create/step/1", name="employee_contract_create_step_1")
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function createStep1(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $contract = new Atcontract();

        $formStep1 = $this->createForm(new \att\employeeBundle\Form\ContractEmployeeType(), $contract, [
            'action' => $this->generateUrl('employee_contract_create_step_1', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $formStep1->handleRequest($request);
        $employee = $contract->getEmployee();

        if ($formStep1->isValid()) {

            $errors = $this->container->get('validator')->validateValue(
                    $employee, new \att\employeeBundle\Validator\Constraints\CheckContract
            );

            if (count($errors) === 0) {

                //$employee->addcontract($contract);


                $formStep2 = $this->createForm(new \att\employeeBundle\Form\ContractBusinessType(), $contract, [
                    'action' => $this->generateUrl('employee_contract_create_step_2', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                    'method' => 'POST'
                ]);

                return new JsonResponse([
                    'form' => $this->renderView('employeeBundle:Contract:form.business.html.twig', [
                        'form' => $formStep2->createView()
                    ]),
                    'employee' => $employee->getId(),
                    'message' => 'ok',
                    'contract' => $this->get('jms_serializer')->serialize($contract, 'json', SerializationContext::create()->setGroups(['Default', 'getStatus', 'getEmployee'])),
                        ], 200);
            } else {
                foreach ($errors as $error) {
                    $formStep1->get('employee')->addError(new FormError($error->getMessageTemplate()));
                }
            }
        }

        return new JsonResponse([
            'form' => $this->renderView('employeeBundle:Contract:form.employee.html.twig', [
                'form' => $formStep1->createView()
            ]),
            'message' => 'error'
        ]);
    }

    /**
     * @Route("/create/step/2", name="employee_contract_create_step_2", options={"expose"=true})
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function createStep2(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }


        $serializer = $this->get('jms_serializer');
        $contract = $serializer->deserialize($request->request->get('contract'), 'att\employeeBundle\Entity\Atcontract', 'json');
        $employee_id = $request->request->get('employee');

        $formStep2 = $this->createForm(new \att\employeeBundle\Form\ContractBusinessType(), $contract, [
            'action' => $this->generateUrl('employee_contract_create_step_2', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $formStep2->handleRequest($request);

        if ($formStep2->isValid()) {

            $business = $contract->getBusiness();
            //$business->addcontract($contract);

            $formStep3 = $this->createForm(new \att\employeeBundle\Form\ContractLegalType(), $contract, [
                'action' => $this->generateUrl('employee_contract_create_step_3', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                'method' => 'POST'
            ]);

            return new JsonResponse([
                'contract' => $this->get('jms_serializer')->serialize($contract, 'json', SerializationContext::create()->setGroups(['Default', 'getStatus', 'getEmployee', 'getBusiness'])),
                'employee' => $employee_id,
                'business' => $business->getId(),
                'message' => 'ok',
                'form' => $this->renderView('employeeBundle:Contract:form.legal.html.twig', [
                    'form' => $formStep3->createView(),
                ])
                    ], 200);
        }

        return new JsonResponse([
            'form' => $this->renderView('employeeBundle:Contract:form.business.html.twig', [
                'form' => $formStep2->createView()
            ]),
            'message' => 'fail',
                ], 200);
    }

    /**
     * @Route("/create/step/3", name="employee_contract_create_step_3", options={"expose"=true})
     * @param Request $request
     * @Method({"GET", "POST"})
     */
    public function createStep3(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }


        $serializer = $this->get('jms_serializer');
        $contract = $serializer->deserialize($request->get('contract'), 'att\employeeBundle\Entity\Atcontract', 'json');

        $employee_id = $request->request->get('employee');
        $business_id = $request->request->get('business');

        $formStep3 = $this->createForm(new \att\employeeBundle\Form\ContractLegalType(), $contract, [
            'action' => $this->generateUrl('employee_contract_create_step_3', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        $formStep3->handleRequest($request);


        if ($formStep3->isValid()) {


            $fileNumberValidator = $this->container->get('validator')->validateValue(
                    $contract, new \att\employeeBundle\Validator\Constraints\ContractFileNumber
            );

            if (count($fileNumberValidator) === 0) {

                $schema = $contract->getSchema();

                $formStep4 = $this->createForm(new \att\employeeBundle\Form\ContractPlanningType(), $contract, [
                    'action' => $this->generateUrl('employee_contract_create_persist', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                    'method' => 'POST',
                    'd' => (7 - $contract->getSchema()->getDays())
                ]);

                return new JsonResponse([
                    'contract' => $this->get('jms_serializer')->serialize($contract, 'json', SerializationContext::create()->setGroups(['Default', 'getEmployee', 'getSchema', 'getStatus', 'getAgreement', 'getBusiness'])),
                    'message' => 'ok',
                    'employee' => $employee_id,
                    'business' => $business_id,
                    'schema' => $schema->getId(),
                    'form' => $this->renderView('employeeBundle:Contract:form.planning.html.twig', [
                        'form' => $formStep4->createView(),
                    ])
                        ], 200);
            } else {
                foreach ($fileNumberValidator as $error) {
                    $formStep3->get('file_number')->addError(new FormError($error->getMessageTemplate()));
                }
            }
        }




        return new JsonResponse([
            'form' => $this->renderView('employeeBundle:Contract:form.legal.html.twig', [
                'form' => $formStep3->createView()
            ]),
                ], 200);
    }

    /**
     * 
     * @Route("/create/step/persist", name="employee_contract_create_persist", options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     */
    public function createPersist(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $serializer = $this->get('jms_serializer');
        $contract = $serializer->deserialize($request->request->get('contract'), 'att\employeeBundle\Entity\Atcontract', 'json');

        $employee_id = $request->request->get('employee');
        $business_id = $request->request->get('business');
        $schema_id = $request->request->get('schema');

        $formStep4 = $this->createForm(new \att\employeeBundle\Form\ContractPlanningType(), $contract, [
            'action' => $this->generateUrl('employee_contract_create_persist', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
            'd' => (7 - $contract->getSchema()->getDays())
        ]);

        $formStep4->handleRequest($request);

        if ($formStep4->isValid()) {
            $schemaScheduleValidator = $this->container->get('validator')->validateValue(
                    $contract, new \att\employeeBundle\Validator\Constraints\SchemaSchedule()
            );

            if (count($schemaScheduleValidator) === 0) {

                $employee = $this->getDoctrine()->getRepository("employeeBundle:Atemployee")->find($employee_id);
                $business = $this->getDoctrine()->getRepository("employeeBundle:Atbusiness")->find($business_id);
                $schema = $this->getDoctrine()->getRepository("employeeBundle:Atschema")->find($schema_id);
                $contract->setSchema($schema);
                $business->addcontract($contract);
                $employee->addcontract($contract);

                $em = $this->getDoctrine()->getManager();

                $em->persist($contract);
                $em->flush();

                return new JsonResponse([
                    'confirm' => $this->renderView('employeeBundle:Contract:show.html.twig', [
                        'contract' => $contract
                    ]),
                    'message' => 'ok',
                        ], 200);
            } else {
                foreach ($schemaScheduleValidator as $error) {
                    $formStep4->addError(new FormError($error->getMessageTemplate()));
                }
            }
        }
        return new JsonResponse([
            'form' => $this->renderView('employeeBundle:Contract:form.planning.html.twig', [
                'form' => $formStep4->createView()
            ])
                ], 200);
    }

    /**
     * @Route("/autocomplete", name="employee_contract_autocomplete_ajax")
     * @param Request $request
     * @return JsonResponse
     */
    public function autocompleteAction(Request $request) {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $term = $request->query->get('q');

        $em = $this->getDoctrine()->getManager();
        $employees = $em->getRepository('employeeBundle:Atcontract')->getByTerm($term);

        return new JsonResponse($employees);
    }

}
