<?php

namespace att\employeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\employeeBundle\Entity\Atagreement;
use att\employeeBundle\Form\AgreementType;

/**
 * Atagreement controller.
 *
 * @Route("{mode}/employee/agreement",requirements={"mode":"frontend|backend"})
 */
class AgreementController extends Controller
{
    
    /**
     * @Route("/", name="employee_agreement_index", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function indexAction(Request $request){
        
        return $this->render("employeeBundle:Agreement:index.html.twig");        
        
    }
    
    /**
     * Lists all Atagreement entities.
     *
     * @Route("/", name="employee_agreement_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $agreements = $em->getRepository('employeeBundle:Atagreement')->findAll();

        return $this->render('employeeBundle:Agreement:list.html.twig', array(
            'las' => $agreements,
            'mode' => $this->get('security.context')->getToken()->getProviderKey() 
            
        ));
    }

    /**
     * Creates a new Atagreement entity.
     *  
     * @Route("/new", name="employee_agreement_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        $agreement = new Atagreement();
        $form = $this->createForm(new AgreementType(), $agreement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agreement);
            $em->flush();

            return $this->redirectToRoute('employee_agreement_show', array('id' => $agreement->getId()));
        }

        return $this->render('employeeBundle:Agreement:new.html.twig', array(
            'mode' => $this->get('security.context')->getToken()->getProviderKey(),
            'form' => $form->createView(),
        ));
        
    }

    /**
     * Finds and displays a Atagreement entity.
     *
     * @Route("/{id}", name="employee_agreement_show")
     * @Method("GET")
     */
    public function showAction(Atagreement $agreement)
    {
        $deleteForm = $this->createDeleteForm($agreement);

        return $this->render('employeeBundle:Agreement:show.html.twig', array(
            'agreement' => $agreement,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Atagreement entity.
     *
     * @Route("/{id}/edit", name="employee_agreement_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atagreement $agreement)
    {
        $deleteForm = $this->createDeleteForm($agreement);
        $editForm = $this->createForm(new AgreementType(), $agreement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($agreement);
            $em->flush();

            return $this->redirectToRoute('employee_agreement_edit', array('id' => $agreement->getId()));
        }

        return $this->render('employeeBundle:Agreement:edit.html.twig', array(
            'agreement' => $agreement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Atagreement entity.
     *
     * @Route("/{id}", name="employee_agreement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atagreement $agreement)
    {
        $form = $this->createDeleteForm($agreement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($agreement);
            $em->flush();
        }

        return $this->redirectToRoute('employee_agreement_index');
    }

    /**
     * Creates a form to delete a Atagreement entity.
     *
     * @param Atagreement $agreement The Atagreement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atagreement $agreement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('employee_agreement_delete', array('id' => $agreement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
