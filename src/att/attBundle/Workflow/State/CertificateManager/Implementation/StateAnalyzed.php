<?php


namespace att\attBundle\Workflow\State\CertificateManager\Implementation;
use att\attBundle\Workflow\State\CertificateManager\AbstractCertificateState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;

/**
 * Description of StateAnalizado
 *
 * @author molasjota
 */
class StateAnalyzed extends AbstractCertificateState {
    
    const KEY = "Analyzed";
    
    public function getKey() {
        return self::KEY;
    }
    
    public function getName() {
        return "Analyzed";
    }
   
    public function cancelCertificate (HasStateInterface $certificate){
        $newstate = $this->getStateFromStateId(StateCancelled::KEY, __METHOD__, $certificate);
        if($newstate){
            $certificate->changeState($this->getStateWorkflow(), $newstate);
        }
        
        return $newstate;
    }
    
}
