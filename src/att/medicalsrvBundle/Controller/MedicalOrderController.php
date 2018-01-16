<?php

namespace att\medicalsrvBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use att\employeeBundle\Entity\Atemployee;
use att\medicalsrvBundle\Entity\Atmedicalorder;
use att\attBundle\Entity\Atabsence;

/**
 * @Route("{mode}/medical/order", requirements={"mode":"frontend|backend"})
 */
class MedicalOrderController extends Controller {

    /**
     * @Route("/new/{employee}/{absence}", name="medical_order_new")
     * @param Request $request
     * @return type
     */
    public function newAction(Request $request, Atemployee $employee, Atabsence $absence) {

        $medicalorder = new \att\medicalsrvBundle\Entity\Atmedicalorder();
        $medicalorder
                ->setEmployee($employee)
                ->addAbsence($absence)
                ->setStatus($this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalorderstatus')->findOneByDescription('draft'))
                ->setDate(new \DateTime)
                ->setTime(new \DateTime);

        $form = $this->createForm(\att\medicalsrvBundle\Form\MedicalOrderType::class, $medicalorder, [
            'action' => $this->generateUrl('medical_order_new', 
                    [
                        'employee' => $employee->getId(),
                        'absence' => $absence->getId(),
                        'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()])
        ]);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            
            $medicalorder->setStatus($this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalorderstatus')->findOneByDescription('assigned'));
            $em = $this->getDoctrine()->getManager();            
            $absence->setMedicalorder($medicalorder);            
            $em->persist($medicalorder);
            $em->flush();
            dump($medicalorder);
            //return $this->redirectToRoute('medical_order_list',[
              //  'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
            //]);
        }
        
        return $this->render('medicalsrvBundle:MedicalOrder:new.html.twig', [
                    'form' => $form->createView(),
                    'medicalorder' => $medicalorder
        ]);

    }
    


    /**
     * @Route("/list", name="medical_order_list", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function listAction(Request $request) {

        $mos = $this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalorder')->findAll();
        return $this->render('medicalsrvBundle:MedicalOrder:list.html.twig', [
                    'mos' => $mos
        ]);
    }

    
    /**
     * @Route("/show/{id}", name="medical_order_show", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function showAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalorder $medicalorder) {

        return $this->render('medicalsrvBundle:MedicalOrder:show.html.twig', [
                    'order' => $medicalorder
        ]);
    }
    
            /**
     * 
     * @param Request $request
     * @param \att\medicalsrvBundle\Entity\Atmedicalorder $medicalorder
     * @Route("/delete/{id}", name="medical_order_delete", options={"expose"=true})
     * @Method({"DELETE"})
     */
    public function deleteAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalorder $medicalorder) {

        $em = $this->getDoctrine()->getManager();
        $em->remove($medicalorder);
        $em->flush();

        return new \Symfony\Component\HttpFoundation\JsonResponse([
            'status' => 'ok',
            'message' => 'Order has been removed'
                ]
                , 200);
    }
    
     /**
     * @Route("/edit/{id}", name="medical_order_edit", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function editAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalorder $medicalorder) {


        $form = $this->createForm(new \att\medicalsrvBundle\Form\MedicalOrderType, $medicalorder, [
            'action' => $this->generateUrl('medical_order_edit', [
                'id' => $medicalorder->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($medicalorder);
            $em->flush();

            return $this->redirectToRoute('medical_service_list',['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]);
        }

        return $this->render('medicalsrvBundle:MedicalService:edit.modal.html.twig', [
                    'form' => $form->createView(),
        ]);
    }
    
    
     /**
     * @Route("/add/visit/{id}", name="medical_order_visit_add", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function addVisitAction(Request $request, \att\medicalsrvBundle\Entity\Atmedicalorder $medicalorder) {
        
        $visit = new \att\medicalsrvBundle\Entity\Atmedicalvisit();        
        $visit->setMedicalOrder($medicalorder);
        
        $form = $this->createForm(new \att\medicalsrvBundle\Form\MedicalVisitType, $visit, [
            'action' => $this->generateUrl('medical_order_visit_add', [
                'id' => $medicalorder->getId(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()])
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medicalorder->addMedicalvisit($visit);
            
            if($visit->getConfirmOrder()){
                $medicalorder->setStatus($this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalorderstatus')->find(4));
                foreach ($medicalorder->getAbsences() as $abs){
                    $abs->setStatejustif(true);
                }
            } 
                    
                   
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($visit);
            $em->flush();

            return $this->redirectToRoute('medical_order_list',
                    [
                        'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                    ]);
        }
        
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                    [
                'form' => $this->renderView('medicalsrvBundle:MedicalOrder:visit.form.html.twig', [
                    'form' => $form->createView()
                ])]
            );
        } else {
            return $this->render('medicalsrvBundle:MedicalOrder:visit.form.html.twig', [
                        'form' => $form->createView(),
            ]);
        }
    }
    
}
