<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeStepType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('personal', new \att\employeeBundle\Form\EmployeePersonalType())
            ->add('contact', new \att\employeeBundle\Form\EmployeeContactType)
            ->add('addressing', new \att\employeeBundle\Form\EmployeeAddressingType())    
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atemployee'
        ));
    }
}
