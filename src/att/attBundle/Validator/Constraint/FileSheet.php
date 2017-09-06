<?php

namespace att\attBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class CertWfCheck extends Constraint {
    
    public $message = "El certificado tiene un Tramite asociado, no se puede eliminar";
    

    
    public function validatedBy() {
        return 'validator.certwkcheck';
    }

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
}
