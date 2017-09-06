<?php

namespace att\syncBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * @Route("/sync/extern/system")
 */
class ExternsystemController extends Controller {

    /**
     * @Route("/list" , name="sync_extern_system_list",options={"expose"=true})
     */
    public function listAction() {

        $ess = $this->getDoctrine()->getRepository('syncBundle:Atexternsystem')->findAll();
        return $this->render('syncBundle:ExternSystem:list.html.twig', ['ess' => $ess]);
    }

    /**
     * @Route("/new" , name="sync_extern_system_new",options={"expose"=true})
     */
    public function newAction() {

        $es = new \att\syncBundle\Entity\Atexternsystem();
        $form = $this->createForm(new \att\syncBundle\Form\AtexternsystemType, $es, [
            'action' => $this->generateUrl('sync_extern_system_create'),
            'method' => 'POST'
        ]);
        return $this->render('syncBundle:ExternSystem:new.html.twig', [
                    'entity' => $es,
                    'form' => $form->createView(),
                    'formPath' => 'syncBundle:ExternSystem:form.html.twig'
        ]);
    }

    /**
     * @param Request $request
     * @Route("/create" , name="sync_extern_system_create",options={"expose"=true})
     * @Method({"POST"})
     */
    public function createAction(Request $request) {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }
        $es = new \att\syncBundle\Entity\Atexternsystem();
        $form = $this->createForm(new \att\attBundle\Form\AtexternsystemType, $es, [
            'action' => $this->generateUrl('sync_extern_system_create'),
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($es);
            $em->flush();
            $em->clear();
            return new JsonResponse([
                'message' => 'ok',
                'id' => $es->getId(),
                    ], 200);
        }

        return new JsonResponse(
                [
            'form' => $this->renderView('syncBundle:ExternSystem:new.html.twig', [
                'entity' => $es,
                'form' => $form->createView(),
                'formPath' => 'syncBundle:ExternSystem:form.html.twig'
            ])
                ], 200);
    }

}
