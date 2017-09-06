<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContractBusinessType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('business', EntityType::class, [
                    'class' => 'employeeBundle:Atbusiness',
                    'expanded' => TRUE,
                    'multiple' => FALSE,
                    'required' => TRUE,
                    'error_bubbling' => TRUE,                    
                ])
        ;
    }

/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '\att\employeeBundle\Entity\Atcontract',
            'validation_groups' => ['StepForm2'],
        ));
    }

}
