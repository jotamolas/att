<?php

namespace att\attBundle\Workflow\State\CertificateManager\Implementation;

use att\attBundle\Workflow\State\CertificateManager\AbstractCertificateState;
use Gmorel\StateWorkflowBundle\StateEngine\HasStateInterface;
/**
 * Description of StateCancelled
 *
 * @author molasjota
 */
class StateCancelled extends AbstractCertificateState {
    

    const KEY = 'Cancelled';
    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return self::KEY;
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
 
        return 'Cancelled';
    }
    
     /**
     * {@inheritdoc}
     */
    public function setCertificateAsRectify(HasStateInterface $certificate)
    {
        $newState = $this->getStateFromStateId(StatePresented::KEY, __METHOD__, $certificate);
        
        if ($newState) {
            $certificate->changeState($this->getStateWorkflow(), $newState);
            // Implement necessary relevant transition here
        }
        return $newState;
    }
    
}
