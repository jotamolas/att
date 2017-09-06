<?php

namespace att\attBundle\Workflow\State\CertificateManager;

use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
use Gmorel\StateWorkflowBundle\StateEngine\StateInterface;
/**
 * Description of CertificateStateInterface
 *
 * @author molasjota
 */
interface CertificateStateInterface extends StateInterface {
    
     /**
     * @param HasStateInterface $certificate Entity
     *
     * @return StateAnalyzed New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */       
    public function setCertificateAsAnalyzed(HasStateInterface $certificate);
    
     /**
     * @param HasStateInterface $certificate Entity
     *
     * @return StateWaitingAnalysis New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */          
    public function setCertificateAsWaitingAnalysis(HasStateInterface $certificate);
    
     /**
     * @param HasStateInterface $certificate Entity
     *
     * @return StateCancelled  New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */       
    public function cancelCertificate (HasStateInterface $certificate);
    
    
    /**
     * @param HasStateInterface $certificate Entity
     *
     * @return StatePresented New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */ 
    public function setCertificateAsRectify(HasStateInterface $certificate);
}
