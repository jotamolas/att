<?php

namespace att\appBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SecurityController extends Controller {

    /**
     * @Route("frontend/login", name="att_frontend_login")
     */
    public function loginAction() {

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('appBundle:Security:login.frontend.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }
    
    /**
     * @Route("frontend/logout", name="att_frontend_logout")
     */
    public function logoutAction(){
        $this->get('security.token_storage')->setToken(null);
        $this->get('request')->getSession()->invalidate();
        return $this->redirect($this->generateUrl('att_frontend_login'));
    }

}
