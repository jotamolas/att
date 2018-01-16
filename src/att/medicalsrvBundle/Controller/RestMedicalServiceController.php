<?php

namespace att\medicalsrvBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * @Route("api/medical/service")
 */
class RestMedicalServiceController extends FOSRestController {

    /**
     * @Route("/list")
     * @Method({"GET"})
     */
    public function getMedicalServiceListAction() {

        $mds = $this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalservice')->findAll();
        $view = View::create();
        $view->setData(['medical_services' => $mds]);

        return $this->handleView($view);
    }

    /**
     * @Route("/orders/{token}")
     * @Route("/orders")
     * @Method({"GET"})
     */
    public function getMedicalOdersAction($token = null) {
        $view = View::create();
        $rest_orders = [] ;
        if ($token) {
            $medical_service = $this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalservice')->findOneBy([
                'token' => $token
            ]);
            if ($medical_service) {
                $orders = $this->getDoctrine()->getRepository('medicalsrvBundle:Atmedicalorder')->findBy([
                    'service' => $medical_service
                ]);
                foreach ($orders as $order){
                    $rest_orders [] = [
                        'order_number' => $order->getID(),
                        'order_prediagnostic' => $order->getDiagnostic(),
                        'employee' => $order->getEmployee()->getSurname().", ".$order->getEmployee()->getName(),
                        'employee_genre' => $order->getEmployee()->getSex(),
                        'employee_dni' => $order->getEmployee()->getDni(),
                        'employee_phone' =>  $order->getEmployee()->getPhone() ? $order->getEmployee()->getPhone() : "NO DATA",
                        'employee_cell_phone' =>  $order->getEmployee()->getCelphone() ? $order->getEmployee()->getCelphone() : "NO DATA",
                        'employee_email' => $order->getEmployee()->getEmail() ? $order->getEmployee()->getEmail() : "NO DATA",
                        'employe_address' => $order->getEmployee()->getAddress() ? $order->getEmployee()->getAddress() : "NO DATA",
                        'employe_address_lat' => $order->getEmployee()->getAddresslat() ? $order->getEmployee()->getAddresslat() : "NO DATA",   
                        'employe_address_lng' => $order->getEmployee()->getAddresslng(),
                    ];
                }
                return $this->handleView($view->setData(['orders' => $rest_orders ]));
            }else{
                 return $this->handleView($view->setData(['message' => 'El token no es valido']));
            }
        }else{
            return $this->handleView($view->setData(['message' => 'No se ha enviado el token']));
        }
    }

}
