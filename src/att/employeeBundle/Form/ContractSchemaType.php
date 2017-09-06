<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ContractSchemaType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                
                
                ->add('schema', EntityType::class, [
                    'class' => 'employeeBundle:Atschema',
                    'expanded' => TRUE,
                    'multiple' => FALSE,
                    'required' => TRUE,
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
        ));
    }

}
