<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\attBundle\Entity\Atworkflowtype;
use att\attBundle\Form\AtworkflowtypeType;

/**
 * Atworkflowtype controller.
 *
 * @Route("/workflowtype")
 */
class WorkflowtypeController extends Controller
{
    /**
     * Lists all Atworkflowtype entities.
     *
     * @Route("/", name="att_workflows_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $atworkflowtypes = $em->getRepository('attBundle:Atworkflowtype')->findAll();

        return $this->render('attBundle:WorkFlowType:index.html.twig', array(
            'atworkflowtypes' => $atworkflowtypes,
        ));
    }

    /**
     * Creates a new Atworkflowtype entity.
     *
     * @Route("/new", name="att_workflows_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $atworkflowtype = new Atworkflowtype();
        $form = $this->createForm('att\attBundle\Form\AtworkflowtypeType', $atworkflowtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atworkflowtype);
            $em->flush();

            return $this->redirectToRoute('att_workflows_show', array('id' => $atworkflowtype->getId()));
        }

        return $this->render('attBundle:WorkFlowType:new.html.twig', array(
            'atworkflowtype' => $atworkflowtype,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Atworkflowtype entity.
     *
     * @Route("/{id}", name="att_workflows_show")
     * @Method("GET")
     */
    public function showAction(Atworkflowtype $atworkflowtype)
    {
        $deleteForm = $this->createDeleteForm($atworkflowtype);

        return $this->render('attBundle:WorkFlowType:show.html.twig', array(
            'atworkflowtype' => $atworkflowtype,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atworkflowtype entity.
     *
     * @Route("/{id}/edit", name="att_workflows_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atworkflowtype $atworkflowtype)
    {
        $deleteForm = $this->createDeleteForm($atworkflowtype);
        $editForm = $this->createForm('att\attBundle\Form\AtworkflowtypeType', $atworkflowtype);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atworkflowtype);
            $em->flush();

            return $this->redirectToRoute('att_workflows_edit', array('id' => $atworkflowtype->getId()));
        }

        return $this->render('attBundle:WorkFlowType:edit.html.twig', array(
            'atworkflowtype' => $atworkflowtype,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atworkflowtype entity.
     *
     * @Route("/{id}", name="att_workflows_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atworkflowtype $atworkflowtype)
    {
        $form = $this->createDeleteForm($atworkflowtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atworkflowtype);
            $em->flush();
        }

        return $this->redirectToRoute('att_workflows_index');
    }

    /**
     * Creates a form to delete a Atworkflowtype entity.
     *
     * @param Atworkflowtype $atworkflowtype The Atworkflowtype entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atworkflowtype $atworkflowtype)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('att_workflows_delete', array('id' => $atworkflowtype->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
