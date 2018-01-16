<?php

namespace att\ctrlaccBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use att\ctrlaccBundle\Entity\Device;

/**
 * @Route("{mode}/ctrlacc", requirements={"mode":"frontend|backend"})
 */
class DefaultController extends Controller
{

   /**
   * @Route("/", name="ctrlacc_index")
   * @param Request $request
   */
    public function indexAction(Request $request)
    {

      return $this->render('ctrlaccBundle:Default:index.html.twig');
      
    }
    
    
    /**
     * @Route("/sync/from/database", name="ctrlacc_sync_from_database")
     */
    public function syncFromDatabaseAction(Request $request){
        
        $form = $this->createFormBuilder()
                ->add('Database', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class,[
                    'class' => 'ctrlaccBundle:Device',
                    'choice_label' => 'description',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er){
                      return $er->createQueryBuilder('d')
                         ->leftJoin('d.system', 's')
                         ->leftJoin('s.type', 't')
                         ->where("t.description = 'Database'");     
                    },                    
                ])
                ->getForm();          
                            
                            
         $form->handleRequest($request);
         if($form->isSubmitted() && $form->isValid()){
             $data =$form->getData('Database');
             $device = $data['Database'];
             $metadata = $device->getMetadata();
             
             if($metadata){
                $result = $this->container->get('sync.remote.service')->syncEvents($device->getSystem(), $metadata);
                dump($result);
                return $this->render('ctrlaccBundle:Syncronization:sync.result.html.twig', ['result' => $result]);
                
             }else{
                 
                 $result['status'] = false;
                 $result['message']= $this->get('translator')->trans('No metadata configuration for this Database');
                 dump($result);
                 return $this->render('ctrlaccBundle:Syncronization:sync.result.html.twig', ['result' => $result]);
                 
             }
             
         }
                    
         return $this->render('ctrlaccBundle:Syncronization:sync.database.form.html.twig',[
            'form' => $form->createView() 
         ]);           
        
        
    }
    


}
