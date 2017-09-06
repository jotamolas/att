<?php


namespace att\attBundle\Workflow\State\CertificateManager;

use Gmorel\StateWorkflowBundle\StateEngine\AbstractState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
/**
 * Description of AbstractCertificateState
 *
 * @author molasjota
 */
abstract class AbstractCertificateState extends AbstractState implements CertificateStateInterface{
       /**
     * {@inheritdoc}
     */
    public function initialize(HasStateInterface $certificate)
    {
        throw $this->buildUnsupportedTransitionException(__METHOD__, $certificate);
    }
    
    public function setCertificateAsAnalyzed(HasStateInterface $certificate)
    {
        throw $this->buildUnsupportedTransitionException(__METHOD__, $certificate);
    }
    
    public function setCertificateAsWaitingAnalysis(HasStateInterface $certificate)
    {
        throw $this->buildUnsupportedTransitionException(__METHOD__, $certificate);
    }
    
    public function cancelCertificate (HasStateInterface $certificate)
    {
        throw $this->buildUnsupportedTransitionException(__METHOD__, $certificate);
    }
    
    public function setCertificateAsRectify(HasStateInterface $certificate)
    {
        throw $this->buildUnsupportedTransitionException(__METHOD__, $certificate);
    }
    
}
