<?php

namespace att\medicalsrvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class MedicalVisitType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('status', EntityType::class, [
                    'class' => 'medicalsrvBundle:Atmedicalvisitstatus',
                    'choice_label' => 'description',
                    'translation_domain' => 'messages',
                    'attr' => [
                        'data-behavior' => 'uppercase'
                    ]
                ])
                ->add('visitdate', DateType::class, [
                    'label' => 'Date',
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'datepicker',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\DateTime
                    ]
                ])
                ->add('matricula', TextType::class)
                ->add('diagnostic', TextareaType::class)
                ->add('confirmOrder', CheckboxType::class, [
                    'label' => 'form.medical.visit.confirmorder',
                    'required' => false,
                    'translation_domain' => 'messages'
                ])
                ->add('medicalrest', CheckboxType::class, [
                    'label' => 'form.medical.visit.rest',
                    'required' => false,
                    'translation_domain' => 'messages'
                ])
                ->add('restdatefrom', DateType::class, [
                    'label' => 'Start Date',
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'datepicker',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\DateTime
                    ]
                ])
                ->add('restdateto', DateType::class, [
                    'label' => 'End Date',
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'datepicker',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\DateTime
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\medicalsrvBundle\Entity\Atmedicalvisit',
        ));
    }

}
