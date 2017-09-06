<?php

namespace att\attBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("{mode}/notification")
 */
class NotificationController extends Controller {

    /**
     * @Route("/", name="att_notification_menu")
     * @return type
     */
    public function indexAction() {

        return $this->render("attBundle:Notification:menu.html.twig");
    }

    /**
     * @Route("/message/{id}/{code}", name="att_notification_message", options={"expose"=true})
     */
    public function messageAction($id, $code) {

        return $this->render("attBundle:Notification:message.html.twig", [
                    'id' => $id,
                    'code' => $code
        ]);
    }

    /**
     * @Route("/list", name="att_notification_list", options={"expose"=true})
     */
    public function listAction(Request $request) {
        
        $this->get('security.token_storage')->getToken()->getProviderKey() == 'frontend' ?
                        $notifications = $this->get('att.notification.service')->pagination($request, $this->getUser()->getEmployee()) :
                        $notifications = $this->get('att.notification.service')->pagination($request);
        
        
        $options = [
            'notifications' => $notifications['paginator']
                ];
        

        return $this->render("attBundle:Notification:list.html.twig", 
                    $options
        );
    }

    /**
     * 
     * @param Request $request
     * @Route("/new/{employee}", name="att_notification_new_w_employee", options={"expose"=true})
     * @Route("/new", name="att_notification_new", options={"expose"=true})
     * 
     */
    public function newAction(Request $request, \att\employeeBundle\Entity\Atemployee $employee = null) {

        $entity = new \att\attBundle\Entity\Atabsencenotification();
        
        if ($employee) {
            $entity->setEmployee($employee);
        } else {
            $entity->setEmployee($this->getUser()->getEmployee());
        }

        $form = $this->createForm(new \att\attBundle\Form\NotificationType(), $entity);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $entity->setDate(new \DateTime());
            $entity->setDays(
                    date_diff($form->get('todate')->getData(), $form->get('fromdate')->getData())->d + 1
            );
            $entity->setCode(uniqid());
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $em->clear();

            return $this->redirectToRoute("att_notification_message",[
                'id' => $entity->getId(),
                'code' => $entity->getCode(),
                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
            ]);
        }

        return $this->render('attBundle:Notification:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }



    /**
     * 
     * @Route("/edit/{id}", name="att_notification_edit", options={"expose"=true})
     * @Method({"GET","POST"})
     * 
     */
    public function editAction(\att\attBundle\Entity\Atabsencenotification $notification) {

        $form = $this->createForm(new \att\attBundle\Form\NotificationType(), $notification, [
            'action' => $this->generateUrl('att_notification_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );

        return $this->render('attBundle:Notification:edit.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    /**
     * 
     * @Route("/update", name="att_notification_update", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function updateAction(Request $request) {

        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }


        $notification = $this->getDoctrine()->getRepository("attBundle:Atabsencenotification")->find($request->request->get('notification-id'));

        $form = $this->createForm(new \att\attBundle\Form\NotificationType(), $notification, [
            'action' => $this->generateUrl('att_notification_update', ['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
            'method' => 'POST'
                ]
        );

        $form->handleRequest($request);

        if ($form->isValid()) {


            $this->getDoctrine()->getManager()->persist($notification);
            $this->getDoctrine()->getManager()->flush();

            return new JsonResponse([
                'status' => 'ok',
                'id' => $notification->getId(),
                'code' => $notification->getCode(),
                    ], 200);
        }

        return new JsonResponse(
                [
            'status' => 'fail',
            'form' => $this->renderView('attBundle:Notification:edit.html.twig', [
                'form' => $form->createView()
            ])
                ], 200);
    }

}
