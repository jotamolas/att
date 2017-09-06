<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use att\attBundle\Entity\Atworkflow;
use att\attBundle\Form\WorkFlowCertType;

/**
 * @Route("{mode}/wfcertificate/" , name="att_workflow_certificate", requirements={"mode":"frontend|backend"})
 */
class ManagerCertificateController extends Controller {

    /**
     * 
     * @Route("tmlwf/{id}", name="att_certificate_timeline", options={"expose"=true})
     */
    public function timeLineWorkFlowAction($id) {


        $stateworkflow = $this->get('wf.certificate');
        $availableStates = $stateworkflow->getAvailableStates();

        foreach ($availableStates as $states) {
            $stringStates [] = $states->getName();
        }

        $wf = $this->getDoctrine()->getRepository('attBundle:Atworkflow')->find($id);

        return $this->render('attBundle:Default:prueba.html.twig', [
                    'identifier' => 'wkcertificate',
                    'states' => $stringStates,
                    'wkstate' => $wf->getStatekey(),
                    'lastDate' => $wf->getModifiedAt(),
                    'firstDate' => $wf->getCreateAt()
        ]);
    }

    /**
     * @Route("start" , name="att_workflow_certificate_start", options={"expose"=true})
     * @Method({"GET"})
     */
    public function startAction() {
        // ver otros filtros para mostrar los certificates
        //obtengo arreglo de ids de certificados que no tienen workflow asociado
        $idCertificates = $this->get('certificate.manager')->getCertificateWithoutWorkflows($this->getUser()->getEmployee());
        $certificates = $this->get('certificate.manager')->getCertificatesById($idCertificates, $this->getUser()->getEmployee());

        if ($certificates) {

            $form = $this->createForm(
                    new WorkFlowCertType(), null, [
                'certificates' => $certificates,
                    ]
            );
            return $this->render('attBundle:Certificate:wf.start.html.twig', ['form' => $form->createView()]);
        } else {
            return $this->render('attBundle:Certificate:wf.start.html.twig', ['form' => FALSE]);
        }
    }

    /**
     * 
     * @Route("validate", name="att_workflow_certificate_validate", options={"expose"=true})
     * 
     */
    public function validateWfCertificateAction(Request $request) {

        if ($request->isXMLHttpRequest()) {

            // find certificate
            $certificate = $this->get('certificate.manager')->getCertificateById($request->request->get('id'));
            $absences = $this->getDoctrine()->getRepository('attBundle:Atabsence')->findBetweenDate(
                    $certificate->getDatefrom(), $certificate->getDateto()
            );

            $render = $this->render('attBundle:Certificate:wf.validate.html.twig', [
                'certificate' => $certificate,
                'absences' => $absences,
            ]);

            return new JsonResponse($render->getContent());
        } else {

            return new Response('This is not ajax request!', 400);
        }
    }

    /**
     * 
     * @Route("create", name="att_workflow_certificate_create", options={"expose"=true})
     * 
     */
    public function createWfCertificateAction(Request $request) {

        if ($request->isXMLHttpRequest()) {

            // create a workflow certificate and initialize to default state
            $wf = new \att\attBundle\Workflow\Entity\WorkFlowCertificate($this->get('wf.certificate'));

            //cast to original class workflow 
            $atcertificate = $this->get('workflow.manager')->castToClass($wf->getParentClass(), $wf);
            // setting others atributtes
            $atcertificate->setWorkflow($this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->findOneByServiceid('wf.certificate'));
            $atcertificate->setEntityid($request->request->get('id'));

            // persist 
            $persistedWfCertificate = $this->get('workflow.manager')->persistWorkFlow($atcertificate);
            $certificate = $this->get('certificate.manager')->getCertificateById($request->request->get('id'));
            $absences = $this->getDoctrine()->getRepository('attBundle:Atabsence')->findBetweenDate(
                    $certificate->getDateFrom(), $certificate->getDateTo()
            );
            foreach ($absences as $absence) {
                $absence->setCertification($persistedWfCertificate->getId());
                $this->get('att.absence.service')->persistAbsence($absence);
            }

            $render = $this->render('attBundle:Certificate:wf.create.html.twig', [
                "workflow" => $persistedWfCertificate,
                "certificate" => $certificate,
                "absences" => $absences,
            ]);

            return new JsonResponse($render->getContent());
        } else {

            return new Response('This is not ajax!', 400);
        }
    }

    /**
     * 
     * @param type $id
     * @Route("authorize/{id}", name="att_workflow_certificate_authorize" , options={"expose" = true})
     */
    public function authorizeWfCertificate($id, Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new Response('<h1>NO TIENE ACCESO POR WEB </br> TOMUER</h1>', 400);
        }
        // Recupero el workflow.
        $wf = $this->getDoctrine()->getRepository("attBundle:Atworkflow")->find($id);

        //Creo una instancia al servicio
        $wfService = new \att\attBundle\Workflow\Entity\WorkFlowCertificate($this->get('wf.certificate'));
        // le envio el estado que tenia guardado en la base.
        $wfService->setStatekey($wf->getStatekey());

        // lo cambio al estado deseado manejando la excepciones de acuerdo al estado del workflows

        try {

            $wfService->setAsAnalyzed($this->get('wf.certificate'));

            $certificate = $this->getDoctrine()->getRepository("attBundle:Atcertificate")->find($wf->getEntityid());
            $certificate->setAprobationstate(1);

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($certificate);
            $em->flush();
            $wf->setStatekey($wfService->getStatekey());

            // creo y registro el mensaje del workflow
            $this->get('workflow.manager')->createWorkflowMessage($wf, $message);
            // grabo el worflow
            $this->get('workflow.manager')->persistWorkFlow($wf);

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'ok',
                        'message' => 'It has been approved pending. You must update your assistance.'
            ]);
        } catch (\Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException $exc) {

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'error',
                        'message' => 'The procedure can not be approved from the state ' . $wfService->getState($this->get('wf.certificate'))
                            ]
            );
        }
    }

    /**
     * 
     * @param type $id
     * @Route("cancel/{id}", name="att_workflow_certificate_cancel" , options={"expose" = true})
     */
    public function cancelWfCertificate($id, Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new Response('<h1>QUE HACES ACA? NO TIENE ACCESO POR WEB </br> TOMUER</h1>', 400);
        }
        // Recupero el workflow.
        $wf = $this->getDoctrine()->getRepository("attBundle:Atworkflow")->find($id);

        //Creo una instancia al servicio
        $wfService = new \att\attBundle\Workflow\Entity\WorkFlowCertificate($this->get('wf.certificate'));
        // le envio el estado que tenia guardado en la base.
        $wfService->setStatekey($wf->getStatekey());

        // lo cambio al estado deseado manejando la excepciones de acuerdo al estado del workflows

        try {
            // creo el mensaje a mostrar
            $message = 'The procedure was canceled';

            $wfService->cancel($this->get('wf.certificate'));

            $wf->setStatekey($wfService->getStatekey());

            // creo y registro el mensaje del workflow
            $this->get('workflow.manager')->createWorkflowMessage($wf, $message);
            // grabo el worflow
            $this->get('workflow.manager')->persistWorkFlow($wf);

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'ok',
                        'message' => $message
            ]);
        } catch (\Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException $exc) {

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'error',
                        'message' => 'The procedure can not be canceled from the state ' . $wfService->getStatekey()
            ]);
        }
    }

    /**
     * 
     * @param type $id
     * @Route("attach/{id}", name="att_workflow_certificate_attach" , options={"expose" = true})
     */
    public function attachWfCertificate($id, Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new Response('<h1>NO TIENE ACCESO POR WEB </br> TOMUER</h1>', 400);
        }

        // Recupero el workflow.
        $wf = $this->getDoctrine()->getRepository("attBundle:Atworkflow")->find($id);

        // creo servicio con el estado del wf guardado en base

        $wfService = $this->get('certificate.manager')->initWfService($wf);
        // lo cambio al estado deseado manejando la excepciones de acuerdo al estado del workflows

        try {
            // tendria que hacer todo en la transicion pero lo vamos hacer asi porque tengo apuro
            // creo el mensaje a mostrar
            $message = 'The document is attached to the process. The certificate is pending analysis.';
            //actualizo la entidad de servicio al estado selecionado si esto da error va por el cath porque lanza una exepcion
            $wfService->setAsWaitingAnalysis($this->get('wf.certificate'));

            // recupero el certificado asociado al workflow
            $certificate = $this->getDoctrine()->getRepository("attBundle:Atcertificate")->find($wf->getEntityid());
            // se attacha la documentacion
            $certificate->setAttachdoc(1);

            //actualizo el certificado
            $this->get('certificate.manager')->persistCertificate($certificate);
            //actualizo el estado del workflow
            $wf->setStatekey($wfService->getStatekey());

            // creo y registro el mensaje del workflow
            $this->get('workflow.manager')->createWorkflowMessage($wf, $message);
            // grabo el worflow
            $this->get('workflow.manager')->persistWorkFlow($wf);

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'ok',
                        'message' => $message
            ]);
        } catch (\Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException $exc) {

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'error',
                        'message' => 'You can not access this option from the state ' . $wfService->getStatekey()
                            ]
            );
        }
    }

    /**
     * 
     * @param type $id
     * @Route("rectify/{id}", name="att_workflow_certificate_rectify" , options={"expose" = true})
     */
    public function rectifyWfCertificate($id, Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new Response('<h1>QUE HACES ACA? NO TIENE ACCESO POR WEB </br> TOMUER</h1>', 400);
        }
        // Recupero el workflow.
        $wf = $this->getDoctrine()->getRepository("attBundle:Atworkflow")->find($id);

        $wfService = $this->get('certificate.manager')->initWfService($wf);

        // lo cambio al estado deseado manejando la excepciones de acuerdo al estado del workflows

        try {
            $message = 'The procedure was rectyfied';

            $wfService->setAsRectify($this->get('wf.certificate'));
            $wf->setStatekey($wfService->getStatekey());

            // creo y registro el mensaje del workflow
            $this->get('workflow.manager')->createWorkflowMessage($wf, $message);
            // grabo el worflow
            $this->get('workflow.manager')->persistWorkFlow($wf);


            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'ok',
                        'message' => $message
            ]);
        } catch (\Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException $exc) {

            return $this->render('attBundle:Certificate:msgs.html.twig', [
                        'status' => 'error',
                        'message' => 'The procedure can not be rectyfied from the state ' . $wfService->getStatekey()
            ]);
        }
    }

    /**
     * 
     * @param type $id
     * @Route("show/{id}", name="att_workflow_certificate_show" , options={"expose" = true})
     * 
     */
    public function showWfCertificate(Request $request, $id) {

        $wf = $this->getDoctrine()->getRepository("attBundle:Atworkflow")->find($id);
        $certificate = $this->getDoctrine()->getRepository("attBundle:Atcertificate")->find($wf->getEntityid());
        $msgs = $this->getDoctrine()->getRepository("attBundle:Atworkflowmsg")->findByWorkflow($wf);

        $options = [
            'wf' => $wf,
            'certificate' => $certificate,
            'msgs' => $msgs,
            'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
        ];
        $request->isXmlHttpRequest() ?
                        $page = $this->render('attBundle:Certificate:wf.show.ajax.html.twig', $options) :
                        $page = $this->render('attBundle:Certificate:wf.show..html.twig', $options);
        return $page;
    }

}
