<?php

namespace att\employeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use att\employeeBundle\Entity\Atunion;

/**
 * @Route("{mode}/employee/union" ,requirements={"mode":"frontend|backend"}) 
 */
class UnionController extends Controller {

    /**
     * @Route("/", name="employee_union_index", options={"expose"=true})
     * @param Request $request
     * @return type
     */
    public function indexAction(Request $request) {

        return $this->render("employeeBundle:Union:index.html.twig");
    }

    /**
     * @Route("/new", name="employee_union_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request) {

        $union = new Atunion();

        $form = $this->createFormBuilder($union)
                ->add("description", "text")
                ->add("abbreviation", "text")
                ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($union);
                $em->flush();
                return new Response('Union' . $union->getDescription());
            }
        }

        return $this->render("employeeBundle:Union:new.html.twig", [
                    "form" => $form->createView(),
                    'mode' => $this->get('security.context')->getToken()->getProviderKey()]);
    }

     /**
     * 
     *
     * @Route("/", name="employee_agreement_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $unions = $this->getDoctrine()->getRepository('employeeBundle:Atunion')->findAll();

        return $this->render('employeeBundle:Union:list.html.twig', array(
            'unions' => $unions,
            'mode' => $this->get('security.context')->getToken()->getProviderKey()             
        ));
    }
    
    
    /**
     * @Route("/show/{strSrch}",name="employee_union_show")
     * @Method("GET")
     */
    public function showAction($strSrch) {

        $repository2 = $this->getDoctrine()
                ->getRepository("employeeBundle:Atunion");

        $r = $repository2->createQueryBuilder('g')
                ->where('g.description LIKE :strSrch')
                ->setParameter('strSrch', '%' . $strSrch . '%')
                ->getQuery()
                ->getResult();

        return $this->render('employeeBundle:Union:index.html.twig', array('gremios' => $r));
    }

}
