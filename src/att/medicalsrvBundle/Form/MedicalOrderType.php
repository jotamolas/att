<?php

namespace att\medicalsrvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MedicalOrderType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('diagnostic' , TextareaType::class, [
                    'label' => 'Pre Diagnostico'
                ])
                ->add('service', EntityType::class, [
                    'class' => 'medicalsrvBundle:Atmedicalservice',
                    'expanded' => FALSE,
                    'multiple' => FALSE,
                    'empty_data' => ' ',
                    'required' => TRUE, 
                    'choice_label' => 'name' 
                ])
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }

/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '\att\medicalsrvBundle\Entity\Atmedicalorder',
        ));
    }

}
