<?php

namespace att\utilBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/util")
 */
class DefaultController extends Controller {

    /**
     * @Route("/google/service/geocode/{address}" , name="util_google_service_geocode")
     */
    public function geoCodeAction($address) {

        return $this->render("attBundle:Default:dump.test.html.twig", [
                    "v" => $this->get('util.google.service')->geocode($address, 'json'),
                    "errors" => null
        ]);
    }

    /**
     * @Route("/google/service/test" , name="util_google_service_test")
     * @return type
     */
    public function testGoogleService() {
        return $this->render("utilBundle:Google:place.autocomplete.html_1.twig", [
                    'token' => $this->get('util.google.service')->getToken()
        ]);
    }

    /**
     * @Route("/test" , name="util_google_service_test")
     */
    public function testAction() {

        $c = new \att\employeeBundle\Entity\Atcontract();
        $c->setStartDate(date_create_from_format('Y-m-d', '2017-06-01'))
                ->setInTime(date_create_from_format('Y-m-d H:i:s', '2017-06-01 08:00:00'))
                ->setOutTime(date_create_from_format('Y-m-d H:i:s', '2017-06-01 14:00:00'))
                ->setFileNumber(366999)
                ->setStatus(1)
                ->setRestDays([1])
                ->setSchema(1);



        $e = $this->getDoctrine()->getRepository("employeeBundle:Atemployee")->find(2104);
        $b = $this->getDoctrine()->getRepository("employeeBundle:Atbusiness")->find(2);

        $e->addcontract($c);
        $b->addcontract($c);
    }

    /**
     * @Route ("/loquequieraprobar/{from}/{to}", name="pruebas")
     */
    public function loQueQuieraProbarAction($from, $to) {
        $dateFrom = \DateTime::createFromFormat('Ymd', $from);
        $dateTo = \DateTime::createFromFormat('Ymd', $to);
        $result = $this->getDoctrine()->getRepository('attBundle:Atattendance')->resumePresenteeismByDate($dateFrom, $dateTo);

        return $this->render('attBundle:Default:dump.test.html.twig', [
                    'v' => $result,
                    'errors' => NULL
        ]);
    }

}
