<?php

namespace att\syncBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/sync")
 */
class DefaultController extends Controller
{
 
   /**
    * @Route("/test/{dni}")
    * @return type
    */
   public function test($dni){
       $rs = $this->get('sync.axton.service')->getEmpFromDb($dni);
       return $this->render('attBundle:Default:dump.test.html.twig', ['v' => $rs, 'errors' => FALSE]);

   }

}