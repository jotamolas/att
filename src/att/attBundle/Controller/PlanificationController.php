<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("{mode}/plan", requirements={"mode":"frontend|backend"})
 */
class PlanificationController extends Controller {

    /**
     * @Route("/", name="att_plan_index")
     * @param Request $request
     */
    public function indexAction(Request $request) {
        return $this->render("attBundle:Planification:index.html.twig");
    }

    /**
     * @Route("/list", name="att_plan_list", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function listAction(Request $request) {
        $plans = $this->get('att.plan.service')->getAllPlans($request);
        $options = [
            'plans' => $plans['paginator'],
            'states' => $plans['states']
        ];
        return $this->render('attBundle:Planification:list.pagination.html.twig', $options);
    }

    /**
     * @Route("/new", name="att_plan_new", options={"expose" = true})
     * @param Request $request
     * @return type
     * @Method("GET")
     */
    public function newAction(Request $request) {

        $form = $this->createForm(
                new \att\attBundle\Form\PlanType(), null, [
            'action' => $this->generateUrl('att_plan_create', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );


        return $this->render('attBundle:Planification:new.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/from/schema", name="att_plan_new_from_schema", options={"expose" = true})
     * @param Request $request
     */
    public function newFromSchema(Request $request) {

        $form = $this->createForm(new \att\attBundle\Form\PlanFromSchemaType(), null, [
            'action' => $this->generateUrl('att_plan_new_from_schema', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
        ]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $data = $request->request->get('plan_from_schema');
            $result = $this->get('att.plan.service')->processPlansSchemaForm($data);

            dump($result);
            /* return $this->render('attBundle:Planification:action.result.html.twig',[
              'status' => 'ok',
              'plans' => count($result['plan_persisted']),
              'persist_error' => count($result['plan_persist_error']),
              'process_error' => count($result['plan_process_errors']) . " of contract employees has no registered schema data or not Active "
              ]); */
        }

        return $this->render('attBundle:Planification:new.from.schema.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/create", name="att_plan_create", options={"expose" = true})
     * @param Request $request
     * @return type
     * 
     */
    public function createAction(Request $request) {

        $form = $this->createForm(
                new \att\attBundle\Form\PlanType(), null, [
            'action' => $this->generateUrl('att_plan_create', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $request->request->get('plan');
            $result = $this->get('att.plan.service')->processPlansForm($data);


            return new JsonResponse(
                    ['status' => 'ok',
                'errors' => "Errors founds: " . count($result['errors_persist']),
                'plansNew' => "Persisted records: " . count($result['plansNew']),
                'plansUpdate' => "Updated records: " . count($result['plansUpdate'])
                    ], 200);
        }

        return $this->render('attBundle:Planification:new.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * 
     * @Route("/create/from/file", name="att_plan_import_from_file", options={"expose"=true})
     * @param Request $request
     *  
     */
    public function importFromFileAction(Request $request) {

        $form = $this->createForm(new \att\attBundle\Form\PlanImportFileType(), null, [
            'action' => $this->generateUrl('att_plan_import_from_file', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $form->get('file')->getData();

            if ($form->isValid()) {

                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                        $this->getParameter('kernel.root_dir') . '/../web/uploads/plan/xls', $fileName);

                $reader = \League\Csv\Reader::createFromPath($this->getParameter('kernel.root_dir') . '/../web/uploads/plan/xls/' . $fileName);
                $is_header_valid = $this->get('att.plan.service')->validateFileHeader($reader->fetchOne());
                $is_header_valid['error'] ? $form->addError(new \Symfony\Component\Form\FormError($is_header_valid['message'])) : null;

                if ($form->isValid()) {
                    $results = $reader->fetchAssoc();
                    $iteratePlans = $this->get('att.plan.service')->iteratePlansFromFile($results);
                    $result = $this->get('att.plan.service')->createOrUpdatePlanFromFile($iteratePlans['validatesPlans']);

                    return $this->render('attBundle:Planification:create.message.html.twig', [
                                'plans' => $result['entities'],
                                'plans_qty' => count($result['entities']),
                                'errors_file' => $iteratePlans['errors'],
                                'errors_entities' => $result['errors']
                    ]);
                }
            }
        }

        return $this->render('attBundle:Planification:import.file.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="att_plan_edit", options={"expose" = true})
     * @return type
     */
    public function editAction(Request $request, \att\attBundle\Entity\Atplan $plan) {

        $form = $this->createForm(
                new \att\attBundle\Form\PlanEditType(), $plan, [
            'action' => $this->generateUrl('att_plan_edit', [
                'id' => $plan->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('att_plan_list',[
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey(),
            ]);
        }

        return $this->render('attBundle:Planification:edit.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

}
