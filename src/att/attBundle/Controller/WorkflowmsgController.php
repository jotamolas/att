<?php
namespace att\attBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
 * @Route("{mode}/workflowmsg" , requirements={"mode":"frontend|backend"})
 */

class WorkflowmsgController extends Controller {
   
    
    /**
     * 
     * @param Request $request
     * @Route("/new/{wf}", name="att_workflowmsg_new", options={"expose"=true})
     * @Method("GET")
     */
    public function newAction(Request $request,  \att\attBundle\Entity\Atworkflow $wf) {
        
        $entity = new \att\attBundle\Entity\Atworkflowmsg();

        $form = $this->createForm(
                new \att\attBundle\Form\WorkFlowMsgType(),
                $entity,
                [
                    'action' => $this->generateUrl('att_workflowmsg_create',['mode' => $this->get('security.token_storage')->getToken()->getProviderKey()]),
                    'method' => 'POST',                    
                    'wf' => [$wf]
                ]
            );
        
        return $this->render('attBundle:Workflowmsg:new.html.twig',[
            'entity' => $entity,
            'form' => $form->createView(),
            'formPath' => 'attBundle:Workflowmsg:form.html.twig'
        ]);
        
    }
    
    /**
     * 
     * @param Request $request
     * @Route("/create", name="att_workflowmsg_create", options={"expose"=true})
     * @Method({"POST"})
     * 
     */
    public function createAction(Request $request)
    {
        
        if(!$request->isXmlHttpRequest()){
            return new JsonResponse(['message' => 'You can access this only using Ajax!'], 400);
        }
        $parameter = $request->request->get('work_flow_msg'); 
        $wf = $this->getDoctrine()->getRepository('attBundle:Atworkflow')->find($parameter['workflow']);
        
        $entity = new \att\attBundle\Entity\Atworkflowmsg();
        $this->get('security.token_storage')->getToken()->getProviderKey() === 'frontend' ?
        $entity->setUsername($this->getUser()->getEmployee()->getSurname()." ".$this->getUser()->getEmployee()->getName()) :
        $entity->setUsername($this->getUser()->getUsername());
        $form = $this->createForm(
                        new \att\attBundle\Form\WorkFlowMsgType(),
                        $entity,
                        [
                            'action' => $this->generateUrl('att_workflowmsg_create',[
                                'mode' => $this->get('security.token_storage')->getToken()->getProviderKey()
                            ]),
                            'method' => 'POST',
                            
                            'wf' => [$wf]

                        ]);
        
        $form->handleRequest($request);
        
        
        $entity->setWorkflow($wf);
        $entity->setWfstate($entity->getWorkflow()->getStatekey());
        
        
       if($form->isValid())
           
           {

            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            
            return new JsonResponse([
                                        'message' => 'ok',
                                        'id' => $entity->getId(),
                                        
                                    ], 200);
            
        }


        
        return new JsonResponse(
        [
            'form' => $this->renderView('attBundle:Workflowmsg:form.html.twig',
                    [
                        'entity' => $entity,
                        'form' => $form->createView(),
                    ])
            
        ], 200);

       
    } 
    
    /**
     * @Route("/list/{wf}", name="att_workflowmsg_list" )
     * @param \att\attBundle\Entity\Atworkflow $wf
     */
    public function listMessages(\att\attBundle\Entity\Atworkflow $wf){
        $msgs = $this->getDoctrine()->getRepository("attBundle:Atworkflowmsg")->findByWorkflow($wf);
        
    }
    
   
    
}
