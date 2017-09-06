<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atstateplan;
use att\attBundle\Form\StateplanType;

/**
 * Atstateplan controller.
 *
 * @Route("/stateplan")
 */
class StateplanController extends Controller
{
    /**
     * Lists all Atstateplan entities.
     *
     * @Route("/", name="att_stateplan_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $atstateplans = $em->getRepository('attBundle:Atstateplan')->findAll();

        return $this->render('attBundle:States:index.html.twig', array(
            'states' => $atstateplans,
        ));
    }

    /**
     * Creates a new Atstateplan entity.
     *
     * @Route("/new", name="att_stateplan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $atstateplan = new Atstateplan();
        $form = $this->createForm('att\attBundle\Form\StateplanType', $atstateplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atstateplan);
            $em->flush();

            return $this->redirectToRoute('att_stateplan_show', array('id' => $atstateplan->getId()));
        }

        return $this->render('attBundle:States:new.html.twig', array(
            'atstateplan' => $atstateplan,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Atstateplan entity.
     *
     * @Route("/{id}", name="att_stateplan_show")
     * @Method("GET")
     */
    public function showAction(Atstateplan $atstateplan)
    {
        $deleteForm = $this->createDeleteForm($atstateplan);

        return $this->render('attBundle:States:show.html.twig', array(
            'state' => $atstateplan,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atstateplan entity.
     *
     * @Route("/{id}/edit", name="att_stateplan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atstateplan $atstateplan)
    {
        $deleteForm = $this->createDeleteForm($atstateplan);
        $editForm = $this->createForm('att\attBundle\Form\StateplanType', $atstateplan);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atstateplan);
            $em->flush();

            return $this->redirectToRoute('att_stateplan_edit', array('id' => $atstateplan->getId()));
        }

        return $this->render('attBundle:States:edit.html.twig', array(
            'atstateplan' => $atstateplan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atstateplan entity.
     *
     * @Route("/{id}", name="att_stateplan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atstateplan $atstateplan)
    {
        $form = $this->createDeleteForm($atstateplan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atstateplan);
            $em->flush();
        }

        return $this->redirectToRoute('att_stateplan_index');
    }

    /**
     * Creates a form to delete a Atstateplan entity.
     *
     * @param Atstateplan $atstateplan The Atstateplan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atstateplan $atstateplan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('att_stateplan_delete', array('id' => $atstateplan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
