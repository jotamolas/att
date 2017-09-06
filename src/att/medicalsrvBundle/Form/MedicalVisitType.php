<?php

namespace att\medicalsrvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MedicalVisitType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('visitdate', DateType::class)
                ->add('matricula', TextType::class)
                ->add('diagnostic',TextareaType::class)
                ->add('medicalrest', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class)
                ->add('restdatefrom', DateType::class)
                ->add('restdateto', DateType::class)
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }

/**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '\att\medicalsrvBundle\Entity\Atmedicalvisit',
            
        ));
    }

}
