<?php

namespace att\attBundle\Workflow\State\CertificateManager\Implementation;
use att\attBundle\Workflow\State\CertificateManager\AbstractCertificateState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
/**
 * Description of StateAguardandoAnalisis
 *
 * @author molasjota
 */
class StateWaitingAnalysis extends AbstractCertificateState {
    
    const KEY = "Awaiting Analysis";
    
    public function getKey() {
        return self::KEY;
    }
    
    public function getName() {
        return "Awaiting Analysis";
    }
    
    public function setCertificateAsAnalizado(HasStateInterface $certificate)
    {
        $newstate = $this->getStateFromStateId(StateAnalyzed::KEY, __METHOD__, $certificate);
        if ($newstate) {
            $certificate->changeState($this->getStateWorkflow(), $newstate);
            // Implement necessary relevant transition here
        }
        return $newstate;
        
    }
    
    public function cancelCertificate (HasStateInterface $certificate){
        $newstate = $this->getStateFromStateId(StateCancelled::KEY, __METHOD__, $certificate);
        if($newstate){
            $certificate->changeState($this->getStateWorkflow(), $newstate);
        }
        
        return $newstate;
    }
    
}
