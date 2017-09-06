<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ObjectManager;
use att\employeeBundle\Form\DataTransformer\EmployeeTransformer;


class EmployeeSelectorType extends AbstractType{
    
    private $manager;
    
    public function __construct(ObjectManager $om)
    {
        $this->manager = $om;
    }
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EmployeeTransformer($this->manager);
        $builder->addModelTransformer($transformer);

    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'invalid_message' => 'The selected employee does not exist',
        ));
    }
    
    
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TextType::class;
    }
    

    
    
}
