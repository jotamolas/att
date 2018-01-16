<?php

namespace att\appBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Ldap\LdapClient;

/**
 * @Route("{mode}" ,requirements={"mode":"frontend|backend"} )
 */
class DefaultController extends Controller {

    /**
     * @Route("/", name="att_index")
     */
    public function indexAction(Request $request) {
        
        ($this->get('security.context')->getToken()->getProviderKey() === 'backend') ?
                        $page = $this->render('appBundle:app:index.html.twig') :
                        $page = $this->render('appBundle:dev:index.html.twig');
        

                        
        return $page;


        
    }
    
    /**
     * @Route("/app/test", name="app_test")
     */
    public function testAction(){
        dump($this->getParameter('att_max_hours_tolerance'));
    }

}

