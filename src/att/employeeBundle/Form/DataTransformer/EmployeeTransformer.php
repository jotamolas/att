<?php
namespace att\employeeBundle\Form\DataTransformer;

use att\employeeBundle\Entity\Atemployee;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class EmployeeTransformer implements DataTransformerInterface {
    
    private $manager;
    
    public function __construct(ObjectManager $manager) {
        $this->manager = $manager;
    }
    
    
    
    /**
     * Transforms a string (number) to an object (employee).
     *
     * @param  string $value
     * @return Atemployee|null
     * @throws TransformationFailedException if object (employee) is not found.
     */
    function reverseTransform($value) {
        
        // no value number? It's optional, so that's ok
        if (!$value) {
            return;
        }
        
        $employee = $this->manager->getRepository("employeeBundle:Atemployee")->findOneByDni($value);
        
        if (null === $employee) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            
            throw new TransformationFailedException(sprintf(
                'An employee with dni "%s" does not exist!',
                $value
            ));
        }
        
        return $employee;
        
    } 
    
    
    /**
     * Transforms an object ($value) to a string (number).
     *
     * @param  value|null $value
     * @return string
     */
    function transform($value) {
        
        if (null === $value) {
            return '';
        }
        
        return $value->getId();
        
    }

}
