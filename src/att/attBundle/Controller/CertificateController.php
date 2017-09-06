<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atcertificate;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("{mode}/certificate", requirements={"mode":"frontend|backend"})
 */
class CertificateController extends Controller {

    /**
     * @Route("/" , name="att_certificate_index", options={"expose"=true})
     * 
     */
    public function indexAction() {
        return $this->render('attBundle:Certificate:index.html.twig');
    }

    /**
     * @Route("/front" , name="att_certificate_front", options={"expose"=true})
     * @param Request $request
     */
    public function frontAction(Request $request) {
        return $this->render('attBundle:Certificate:front.html.twig');
    }

    /**
     * @Route("/certification/step/first" , name="att_certification_first_step", options={"expose"=true})
     * @param Request $request
     */
    public function firstStepCertification(Request $request) {

        $form = $this->createFormBuilder()
                ->add('certificate_type', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choice_translation_domain' => true,
                    'label' => 'Certificate Type',
                    'choices' => [
                        'Medical' => 'Medical',
                        'Stude' => 'Stude',
                        'Other' => 'Other'
                    ]
                ])
                ->add('select', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, ['label' => 'Select'])
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() & $form->isValid()) {
            $data = $form->getData();
            if ($data['certificate_type'] === 'Medical') {
                return $this->forward('attBundle:Certificate:newMedical');
            } elseif ($data['certificate_type'] === 'Stude') {
                return $this->forward('attBundle:Certificate:newStude');
            } else {
                return $this->forward('attBundle:Certificate:new');
            }
        }

        return $this->render('attBundle:Certificate:certification.first.step.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    public function showInRowAction($id) {

        $certificate = $this->getDoctrine()->getRepository('attBundle:Atcertificate')->find($id);
        return $this->render('attBundle:Certificate:show.row.html.twig', [
                    'certificate' => $certificate
        ]);
    }

    /**
     *  @Route("/list", name="att_certificate_list", options={"expose"=true})
     *  @Method("GET")
     */
    public function listAction(Request $request) {

        $this->get('security.token_storage')->getToken()->getProviderKey() == 'frontend' ?
                        $certificates = $this->get('certificate.manager')->pagination($request, $this->getUser()->getEmployee()) :
                        $certificates = $this->get('certificate.manager')->pagination($request);

        $options = [
            'certificates' => $certificates['paginator']
        ];

        dump($certificates);
        
        $this->get('security.token_storage')->getToken()->getProviderKey() == 'frontend' ?
                $page = $this->render("attBundle:Certificate:list.frontend.html.twig", $options ):
                $page = $this->render("attBundle:Certificate:list.backend.html.twig", $options );
        return $page;
        
    }

    /**
     * @Route("/listwf", name="att_certificate_list_wf", options={"expose"=true})
     * @Method("GET")
     */
    public function listWfAction() {
        if ($this->get('security.token_storage')->getToken()->getProviderKey() == 'frontend') {
            $wfs = $this->getDoctrine()->getRepository('attBundle:Atworkflow')
                    ->findByCertificatesAndEmployee(
                    $this->getDoctrine()->getRepository('attBundle:Atcertificate')->findByEmployee($this->getUser()->getEmployee()), $this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));
        } else {
            $wfs = $this->getDoctrine()->getRepository('attBundle:Atworkflow')
                    ->findByWorkflow($this->getDoctrine()->getRepository('attBundle:Atworkflowtype')->find('wf.certificate'));
        }
        return $this->render('attBundle:Certificate:list.wf.html.twig', ['wfs' => $wfs]);
    }

    /**
     * 
     * @param Request $request
     * @Route("/new", name="att_certificate_new", options={"expose"=true})
     * @Method("GET")
     */
    public function newAction(Request $request) {

        $entity = new Atcertificate();
        $form = $this->createForm(
                new \att\attBundle\Form\AtcertificateType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create', [
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        return $this->render('attBundle:Certificate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'formPath' => 'attBundle:Certificate:form.html.twig'
        ));
    }

    /**
     * 
     * @param Request $request
     * @Route("/newmedical", name="att_certificate_new_medical", options={"expose"=true})
     * @Method("GET")
     */
    public function newMedicalAction(Request $request) {

        $entity = new \att\attBundle\Entity\Atcertificatemedical();
        $form = $this->createForm(
                new \att\attBundle\Form\CertificateMedicalType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create_medical', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        return $this->render('attBundle:Certificate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'formPath' => 'attBundle:Certificate:form.medical.html.twig'
        ));
    }

    /**
     * 
     * @param Request $request
     * @Route("/newstude", name="att_certificate_new_stude", options={"expose"=true})
     * @Method("GET")
     */
    public function newStudeAction(Request $request) {

        $entity = new \att\attBundle\Entity\Atcertificatestude();
        $form = $this->createForm(
                new \att\attBundle\Form\CertificateStudeType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create_stude', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        return $this->render('attBundle:Certificate:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'formPath' => 'attBundle:Certificate:form.stude.html.twig'
        ));
    }

    /**
     * 
     * @param Request $request
     * @Route("/createmedical", name="att_certificate_create_medical", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function createMedicalAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }


        $entity = new \att\attBundle\Entity\Atcertificatemedical();
        $certificate = new Atcertificate();

        $entity->setCertificado($certificate);

        $entity->getCertificate()
                ->setType(
                        $this->getDoctrine()
                        ->getRepository('attBundle:Atcertificatetype')
                        ->find(2)
                )
                ->setAprobationstate(0)
                ->setAttachdoc(0)
                ->setDate(new \DateTime())
                ->setEmployee(
                        $this->getUser()->getEmployee()
        );



        $form = $this->createForm(
                new \att\attBundle\Form\CertificateMedicalType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create_medical', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);
            $em->flush();


            return new JsonResponse([
                'message' => 'ok',
                'id' => $entity->getCertificate()->getId(),
                    ], 200);
        }


//$entity->setScan(NULL);  
        return new JsonResponse([
//'message' => $form->getErrorsAsString(),
            'form' => $this->renderView('attBundle:Certificate:form.medical.html.twig', [
                'entity' => $entity,
                'form' => $form->createView(),
            ])
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @Route("/createstude", name="att_certificate_create_stude", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function createStudeAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }


        $entity = new \att\attBundle\Entity\Atcertificatestude();
        $certificate = new Atcertificate();

        $entity->setCertificado($certificate);

        $entity->getCertificate()
                ->setType(
                        $this->getDoctrine()
                        ->getRepository('attBundle:Atcertificatetype')
                        ->find(3)
                )
                ->setAprobationstate(0)
                ->setAttachdoc(0)
                ->setDate(new \DateTime())
                ->setEmployee($this->getUser()->getEmployee());



        $form = $this->createForm(
                new \att\attBundle\Form\CertificateStudeType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create_stude', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->merge($entity);
            $em->flush();


            return new JsonResponse([
                'message' => 'ok',
                'id' => $entity->getCertificate()->getId(),
                    ], 200);
        }


//$entity->setScan(NULL);  
        return new JsonResponse([
//'message' => $form->getErrorsAsString(),
            'form' => $this->renderView('attBundle:Certificate:form.stude.html.twig', [
                'entity' => $entity,
                'form' => $form->createView(),
            ])
                ], 200);
    }

    /**
     * 
     * @param Request $request
     * @Route("/{id}/edit", name="att_certificate_edit", options={"expose"=true})
     * @Method({"GET","POST"})
     */
    public function editAction(Request $request, Atcertificate $entity) {


        $entity->setScan(
                new File($this->getParameter('kernel.root_dir') . '/../web/uploads/certificates/' . $entity->getScan()));


        $form = $this->createForm(
                new \att\attBundle\Form\AtcertificateType($this->getDoctrine()->getManager()), $entity
        );



        return $this->render('attBundle:Certificate:edit.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
                    'formPath' => 'attBundle:Certificate:form.html.twig'
        ));
    }

    /**
     * 
     * @param Request $request
     * @Route("/create", name="att_certificate_create", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function createAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }


        $entity = new Atcertificate();
        $entity->setDate(new \DateTime());
        $entity->setEmployee($this->getUser()->getEmployee());
        $entity->setAprobationstate(0);
        $entity->setType($this->getDoctrine()->getRepository('attBundle:Atcertificatetype')->find(1));
        $entity->setAttachdoc(0);
        $form = $this->createForm(
                new \att\attBundle\Form\AtcertificateType($this->getDoctrine()->getManager()), $entity, [
            'action' => $this->generateUrl('att_certificate_create', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);


        if ($form->isValid()) {

            $persistedEntity = $this->get('certificate.manager')->persistCertificate($entity);

            return new JsonResponse([
                'message' => 'ok',
                'id' => $persistedEntity->getId(),
                    ], 200);
        }


//$entity->setScan(NULL);  
        return new JsonResponse(
                [
            'form' => $this->renderView('attBundle:Certificate:form.html.twig', [
                'entity' => $entity,
                'form' => $form->createView(),
            ])
                ], 200);
    }

    /**
     * @param Request $request
     * @Route("/{id}/update", name="att_certificate_update", options={"expose"=true})
     * @Method({"POST"})
     */
    public function updateAction(Request $request, Atcertificate $certificate) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }

        $form = $this->createForm(new \att\attBundle\Form\AtcertificateType($this->getDoctrine()->getManager()), $certificate);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $persistedEntity = $this->get('certificate.manager')->persistCertificate($certificate);
            return new JsonResponse([
                'message' => 'ok',
                'id' => $persistedEntity->getId(),
                    ], 200);
        }

        return new JsonResponse(
                [
//'message' => $form->getErrorsAsString(),
            'form' => $this->renderView('attBundle:Certificate:edit.html.twig', [
                'entity' => $certificate,
                'form' => $form->createView(),
            ])
                ], 200);
    }

    /**
     *
     * @Route("/{id}", name="att_certificate_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(Atcertificate $certificate) {
        $deleteForm = $this->createDeleteForm($certificate);

        return $this->render('attBundle:Certificate:show.html.twig', array(
                    'certificate' => $certificate,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atcertificate entity.
     * 
     * @Route("/delete", name="att_certificate_delete", options={"expose"=true})
     * @Method("POST")
     */
    public function deleteAction(Request $request) {


        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }
        $certificate = $this->get('certificate.manager')
                ->getCertificateById(
                $this->getRequest()->request->get('id')
        );


        $validationErrors = $this->get('certificate.manager')->validateCertificateToDeleted($certificate);

        if (!$validationErrors) {
            $data = [
                'status' => true,
                'message' => "Certificate Id:" . $certificate->getId() . " was deleted"
            ];
            $em = $this->getDoctrine()->getManager();
            $em->remove(
                    $certificate
            );
            $em->flush();
        } else {

            $data = [
                'status' => false,
                'message' => $validationErrors
            ];
        }

        return new Response(json_encode($data));
    }

    /**
     * Creates a form to delete a Atcertificate entity.
     *
     * @param Atcertificate $certificate The Atcertificate entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atcertificate $certificate) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('att_certificate_delete', [
                                    'mode' => $this->get('security.token_storage')->getToken()->getProviderKey(),
                                    'id' => $certificate->getId()]))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
