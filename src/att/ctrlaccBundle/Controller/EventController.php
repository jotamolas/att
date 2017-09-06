<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace att\ctrlaccBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of EventController
 *
 * @author jorge.molas
 * @Route("/ctrlacc/event")
 */
class EventController extends Controller{
    
    
    /**
     * @Route("/list/{dni}", name="ctrlacc_event_list")
     */
    public function listAction($dni){        
        $events = $this->getDoctrine()->getRepository('ctrlaccBundle:Event', 'ctrlacc')->findByEmployee($dni);
        
        return $this->render('ctrlaccBundle:Event:list.html.twig', ['events' => $events]);
        
    }

    /**
     * @Route("/list", name="ctrlacc_event_list")
     */
    public function listAllAction(){        
        $events = $this->getDoctrine()->getRepository('ctrlaccBundle:Event', 'ctrlacc')->findAll();
        
        return $this->render('ctrlaccBundle:Event:list.html.twig', ['events' => $events]);
        
    }    

}
