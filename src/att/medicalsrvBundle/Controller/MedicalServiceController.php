<?php

namespace att\medicalsrvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * @Route("{mode}/medical/service", requirements={"mode":"frontend|backend"})
 */
class MedicalServiceController extends Controller {

    /**
     * @Route("/", name="medical_service_index")
     * @return type
     */
    public function indexAction() {
        return $this->render('medicalsrvBundle:Default:index.html.twig');
    }

    /**
     * @Route("/new", name="medical_service_new")
     * @param Request $request
     * @return type
     */
    public function newAction(Request $request) {

        $medicalservice = new \att\medicalsrvBundle\Entity\Atmedicalservice();
        $form = $this->createForm(new \att\medicalsrvBundle\Form\MedicalServiceType, $medicalservice, [
            'action' => $this->generateUrl('medical_service_new', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()])
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $medicalservice = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalservice);
            $em->flush();

            return $this->redirectToRoute('medical_service_list', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]);
        }

        return $this->render('medicalsrvBundle:MedicalService:new.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="medical_service_edit", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function editAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalservice $medicalservice) {


        $form = $this->createForm(new \att\medicalsrvBundle\Form\MedicalServiceType, $medicalservice, [
            'action' => $this->generateUrl('medical_service_edit', [
                'id' => $medicalservice->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalservice);
            $em->flush();

            return $this->redirectToRoute('medical_service_list',['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]);
        }

        return $this->render('medicalsrvBundle:MedicalService:edit.modal.html.twig', [
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list", name="medical_service_list")
     * @param Request $request
     * @return type
     */
    public function listAction(Request $request) {

        $mds = $this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalservice')->findAll();
        return $this->render('medicalsrvBundle:MedicalService:list.html.twig', [
                    'mds' => $mds
        ]);
    }
    
    
     /**
     * 
     * @param Request $request
     * @param \att\medicalsrvBundle\Entity\Atmedicalservice $medicalservice
     * @Route("/delete/{id}", name="medical_service_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalservice $medicalservice) {

        $em = $this->getDoctrine()->getManager();
        $em->remove($medicalservice);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse([
            'status' => 'ok',
            'message' => 'Service has been removed'
                ]
                , 200);
    }

}
