<?php

namespace att\attBundle\Workflow\State\CertificateManager\Implementation;

use att\attBundle\Workflow\State\CertificateManager\AbstractCertificateState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;

/**
 * Description of StatePresented
 *
 * @author Jota
 */
class StatePresented extends AbstractCertificateState {
    
    const KEY = "Presented";
    
    public function getKey() {
        return self::KEY;
    }
    
    public function getName() {
        return "Presented";
    }
    
    public function initialize(HasStateInterface $certificate) {
        $certificate->changeState($this->getStateWorkflow(), $this);
        return $this;
    }


    /**
     * {@inheritdoc}
     */
    public function setCertificateAsWaitingAnalysis(HasStateInterface $certificate)
    {
        $newState = $this->getStateFromStateId(StateWaitingAnalysis::KEY, __METHOD__, $certificate);
        if ($newState) {
            $certificate->changeState($this->getStateWorkflow(), $newState);
            // Implement necessary relevant transition here
        }
        return $newState;
    }
    
    
    public function cancelCertificate (HasStateInterface $certificate)
    {
        $newstate = $this->getStateFromStateId(StateCancelled::KEY, __METHOD__, $certificate);
        if($newstate){
            $certificate->changeState($this->getStateWorkflow(), $newstate);
        }
        
        return $newstate;
    }
    
    
    
}
