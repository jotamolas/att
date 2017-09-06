<?php


namespace att\attBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;


class WebServiceController extends Controller{
    
    
    /**
     * @Soap\Method("getEmployee")
     * @Soap\Param("id", phpType = "string[]")
     * @Soap\Result(phpType = "\att\employeeBundle\Entity\Atemployee")
     */
    
    public function getEmployee($id){
        
        $employee = $this->getDoctrine()->getRepository('employeeBundle:Atemployee')->findOneByDni($id);
        return $employee;
    }
    
    
}
