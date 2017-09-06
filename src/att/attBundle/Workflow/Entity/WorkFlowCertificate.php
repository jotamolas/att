<?php

namespace att\attBundle\Workflow\Entity;
/**
 * Description of WFCertificate
 *
 * @author molasjota
 */

use att\attBundle\Entity\Atworkflow as Workflow;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
use Gmorel\StateWorkflowBundle\StateEngine\StateWorkflow;
use Gmorel\StateWorkflowBundle\StateEngine\StateInterface;
/*
 * @Entity
 */
class WorkFlowCertificate extends Workflow implements HasStateInterface  {
    
    /**
     * @OneToOne(targetEntity="\att\attBundle\Entity\Atcertificate") 
     */
    private $certificate;



    public function __construct(StateWorkflow $stateWorkFlow) {
        
        $stateWorkFlow->getDefaultState()->initialize($this);
        
    }
    
    public function getParentClass(){
        return parent::class;
    }
    
    /**
     * 
     * @param  \att\attBundle\Entity\Atcertificate $certificate
     * @return \att\attBundle\Workflow\Entity\WorkFlowCertificate
     */
    public function setCertificate( \att\attBundle\Entity\Atcertificate $certificate){
        $this->certificate = $certificate;
        return $this;
    }
    
    /**
     * 
     * @return  \att\attBundle\Entity\Atcertificate
     */
    public function  getCertificate(){
        return $this->certificate;
    }
    
    
    
    public function setMyEntityid() {
        parent::setEntityid($this->certificate->getId());
    }

    /**
     * {@inheritdoc}
     * @return CertificateStateInterface
     */
    public function getState(StateWorkflow $stateWorkFlow) {
        
        return $stateWorkFlow->getStateFromKey($this->getStateKey());
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function changeState(StateWorkflow $stateWorkFlow, StateInterface $newState) {
        $stateWorkFlow->guardExistingState($newState->getKey());
        $this->setStateKey($newState->getKey());
        return $this;
    }
    
    
     /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $certificate
     *
     * @return \att\attBundle\Workflow\State\Implementation\StateAnalizado New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function setAsAnalyzed(StateWorkflow $certificate){
        
        return $this->getState($certificate)->setCertificateAsAnalyzed($this);        
                
    }
    
     /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $certificate
     *
     * @return \att\attBundle\Workflow\State\Implementation\StateAguardandoAnalisis New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    
    public function setAsWaitingAnalysis(StateWorkflow $certificate){
        return $this->getState($certificate)->setCertificateAsWaitingAnalysis($this);
    }
    
     /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $certificate
     *
     * @return \att\attBundle\Workflow\State\Implementation\StateCancelled New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function cancel (StateWorkflow $certificate){
        return $this->getState($certificate)->cancelCertificate($this);
    }
    
    /**
     * [TellDontAsk](http://martinfowler.com/bliki/TellDontAsk.html)
     * @param StateWorkflow $certificate
     *
     * @return \att\attBundle\Workflow\State\Implementation\StateCancelled New State
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\UnsupportedStateTransitionException
     * @throws \Gmorel\StateWorkflowBundle\StateEngine\Exception\StateNotImplementedException
     */
    public function setAsRectify (StateWorkflow $certificate){
        return $this->getState($certificate)->setCertificateAsRectify($this);
    }
   
}
