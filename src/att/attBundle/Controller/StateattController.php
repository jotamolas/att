<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atstateatt;
use att\attBundle\Form\StateattType;

/**
 * Atstateatt controller.
 *
 * @Route("/stateatt")
 */
class StateattController extends Controller {

    /**
     * Lists all Atstateatt entities.
     *
     * @Route("/", name="att_stateatt_index")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $atstateatts = $em->getRepository('attBundle:Atstateatt')->findAll();

        return $this->render('attBundle:States:index.html.twig', array(
                    'states' => $atstateatts,
        ));
    }

    /**
     * Creates a new Atstateatt entity.
     *
     * @Route("/new", name="att_stateatt_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {
        $atstateatt = new Atstateatt();
        $form = $this->createForm('att\attBundle\Form\StateattType', $atstateatt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atstateatt);
            $em->flush();

            return $this->redirectToRoute('att_stateatt_show', array('id' => $atstateatt->getId()));
        }

        return $this->render('attBundle:States:new.html.twig', array(
                    'atstateatt' => $atstateatt,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Atstateatt entity.
     *
     * @Route("/{id}", name="att_stateatt_show")
     * @Method("GET")
     */
    public function showAction(Atstateatt $atstateatt) {
        $deleteForm = $this->createDeleteForm($atstateatt);

        return $this->render('attBundle:States:show.html.twig', array(
                    'state' => $atstateatt,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atstateatt entity.
     *
     * @Route("/{id}/edit", name="att_stateatt_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atstateatt $atstateatt) {
        $deleteForm = $this->createDeleteForm($atstateatt);
        $editForm = $this->createForm('att\attBundle\Form\StateattType', $atstateatt);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atstateatt);
            $em->flush();

            return $this->redirectToRoute('att_stateatt_edit', array('id' => $atstateatt->getId()));
        }

        return $this->render('attBundle:States:edit.html.twig', array(
                    'atstateatt' => $atstateatt,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atstateatt entity.
     *
     * @Route("/{id}", name="att_stateatt_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atstateatt $atstateatt) {
        $form = $this->createDeleteForm($atstateatt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atstateatt);
            $em->flush();
        }

        return $this->redirectToRoute('att_stateatt_index');
    }

    /**
     * Creates a form to delete a Atstateatt entity.
     *
     * @param Atstateatt $atstateatt The Atstateatt entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atstateatt $atstateatt) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('att_stateatt_delete', array('id' => $atstateatt->getId())))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

}
