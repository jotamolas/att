<?php

namespace att\attBundle\Controller;

use att\attBundle\Entity\Atworkleavetype;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Atworkleavetype controller.
 *
 * @Route("{mode}/workleavetype",requirements={"mode":"frontend|backend"})
 */
class WorkLeaveTypeController extends Controller
{
    /**
     * Lists all atworkleavetype entities.
     *
     * @Route("/", name="att_workleavetype_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $atworkleavetypes = $em->getRepository('attBundle:Atworkleavetype')->findAll();

        return $this->render('attBundle:WorkLeaveType:index.html.twig', array(
            'atworkleavetypes' => $atworkleavetypes,
        ));
    }

    /**
     * Creates a new atworkleavetype entity.
     *
     * @Route("/new", name="att_workleavetype_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $atworkleavetype = new Atworkleavetype();
        $form = $this->createForm('att\attBundle\Form\AtworkleavetypeType', $atworkleavetype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($atworkleavetype);
            $em->flush();

            return $this->redirectToRoute('att_workleavetype_show', [
                'id' => $atworkleavetype->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                    ]);
        }

        return $this->render('attBundle:WorkLeaveType:new.html.twig', array(
            'atworkleavetype' => $atworkleavetype,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a atworkleavetype entity.
     *
     * @Route("/{id}", name="att_workleavetype_show")
     * @Method("GET")
     */
    public function showAction(Atworkleavetype $atworkleavetype)
    {
   
        return $this->render('attBundle:WorkLeaveType:show.html.twig', array(
            'atworkleavetype' => $atworkleavetype,
            
        ));
    }

    /**
     * Displays a form to edit an existing atworkleavetype entity.
     *
     * @Route("/{id}/edit", name="att_workleavetype_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atworkleavetype $atworkleavetype)
    {
        
        $editForm = $this->createForm('att\attBundle\Form\AtworkleavetypeType', $atworkleavetype);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('att_workleavetype_edit', array('id' => $atworkleavetype->getId()));
        }

        return $this->render('attBundle:WorkLeaveType:edit.html.twig', array(
            'atworkleavetype' => $atworkleavetype,
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a atworkleavetype entity.
     *
     * @Route("/{id}", name="att_workleavetype_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atworkleavetype $atworkleavetype)
    {
        $form = $this->createDeleteForm($atworkleavetype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atworkleavetype);
            $em->flush();
        }

        return $this->redirectToRoute('workflowleavetype_index');
    }

    /**
     * Creates a form to delete a atworkleavetype entity.
     *
     * @param Atworkleavetype $atworkleavetype The atworkleavetype entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atworkleavetype $atworkleavetype)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('att_workleavetype_delete', array('id' => $atworkleavetype->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
