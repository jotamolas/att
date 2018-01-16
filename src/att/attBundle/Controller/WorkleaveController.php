<?php

namespace att\attBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use att\employeeBundle\Entity\Atemployee;
use att\attBundle\Entity\Atworkleavetype;
use att\attBundle\Entity\Atworkleave;
use att\attBundle\Entity\Atcertificate;

/**
 * Atworkleave controller.
 *
 * @Route("{mode}/workleave", requirements={"mode":"frontend|backend"})
 */
class WorkleaveController extends Controller
{
    /**
     * Lists all atworkleave entities.
     *
     * @Route("/", name="att_workleave_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $atworkleaves = $em->getRepository('attBundle:Atworkleave')->findAll();

        return $this->render('attBundle:WorkLeave:index.html.twig', array(
            'atworkleaves' => $atworkleaves,
        ));
    }

    /**
     * Creates a new atworkleave entity.
     *
     * @Route("/new/{employee}/{certificate}", name="att_workleave_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Atemployee $employee, Atcertificate $certificate)
    {
        $atworkleave = new Atworkleave();
        $atworkleave->setEmployee($employee)
                ->setDateFrom($certificate->getDatefrom())
                ->setDateTo($certificate->getDateto());
        dump($certificate->getType());
        $form = $this->createForm('att\attBundle\Form\AtworkleaveType', $atworkleave,[
            'types' => $certificate->getType()->getWorkleavetypes()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {            
            
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($atworkleave);
            $em->flush();
            /* verifico si hay ausencias y le asigno la licencia */
            $absences = $this->getDoctrine()->getRepository('attBundle:Atabsence')->findBetweenDate($certificate->getDatefrom(),$certificate->getDateto());
            foreach ($absences as $absence){
                $absence->setWorkleave($atworkleave);
                $em->flush();
            }
            /* verifico la planificacion y le cambio por estado ausente */
            $plans = $this->getDoctrine()->getRepository('attBundle:Atplan')->findByEmployeeAndBetweenDates($employee,$certificate->getDatefrom(),$certificate->getDateto());
            foreach ($plans as $plan){
                $plan->setStateplan($this->getDoctrine()->getRepository('attBundle:Atstateplan')->find(2));
                $em->flush();
                
            }
            
           
            return $this->redirectToRoute('att_workleave_show', [
                'id' => $atworkleave->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                    ]);
        }

        return $this->render('attBundle:WorkLeave:new.html.twig', array(
            'atworkleave' => $atworkleave,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a atworkleave entity.
     *
     * @Route("/{id}", name="att_workleave_show")
     * 
     */
    public function showAction(Atworkleave $atworkleave)
    {
        $absences = $atworkleave->getAbsences();
        return $this->render('attBundle:WorkLeave:show.html.twig', array(
            'atworkleave' => $atworkleave,
            'absences' => $absences
        ));
    }

    /**
     * Displays a form to edit an existing atworkleave entity.
     *
     * @Route("/{id}/edit", name="att_workleave_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Atworkleave $atworkleave)
    {
        $deleteForm = $this->createDeleteForm($atworkleave);
        $editForm = $this->createForm('att\attBundle\Form\AtworkleaveType', $atworkleave);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('atworkleave_edit', array('id' => $atworkleave->getId()));
        }

        return $this->render('attBundle:WorkLeave:edit.html.twig', array(
            'atworkleave' => $atworkleave,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a atworkleave entity.
     *
     * @Route("/{id}", name="att_workleave_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Atworkleave $atworkleave)
    {
        $form = $this->createDeleteForm($atworkleave);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($atworkleave);
            $em->flush();
        }

        return $this->redirectToRoute('att_workleave_index');
    }

    /**
     * Creates a form to delete a atworkleave entity.
     *
     * @param Atworkleave $atworkleave The atworkleave entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Atworkleave $atworkleave)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('att_workleave_delete', array('id' => $atworkleave->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
