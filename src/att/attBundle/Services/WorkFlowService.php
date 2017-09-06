<?php
namespace att\attBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use att\attBundle\Entity\Atworkflow;
use att\attBundle\Entity\Atworkflowmsg;

class WorkFlowService {
    
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {        
        $this->em = $em;
        $this->container = $container;
    }
    
    
    public function persistWorkFlow(Atworkflow $workflow){
        
        $this->em->persist($workflow);
        $this->em->flush();
        
        return ($workflow);
    }
    
    /**
    * Cast an object to another class, keeping the properties, but changing the methods
    *
    * @param string $class  Class name
    * @param object $object
    * @return object
    */
    public function castToClass($class, $object)
    {
	return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }
    
    /**
     * 
     * @param Atworkflow $workflow
     * @param type $message
     */
    public function createWorkflowMessage(Atworkflow $workflow, $message){
        
        $msg = new Atworkflowmsg();
        $msg->setDetails($message)
                ->setWorkflow($workflow)
                ->setWfstate($workflow->getStatekey());
        
        return $this->persistWorkflowMessage($msg);
        
    }
    
    
    /**
     * 
     * @param Atworkflowmsg $msg
     * @return Atworkflowmsg
     */
    public function persistWorkflowMessage(Atworkflowmsg $msg){
        $this->em->persist($msg);
        $this->em->flush();
        return $msg;
    }
    
    

}