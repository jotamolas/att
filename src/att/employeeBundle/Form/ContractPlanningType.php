<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ContractPlanningType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('start_date', DateType::class, [
                    'label' => 'Start Date',
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'datepicker',
                        'placeholder' => 'Start Date',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\DateTime
                    ]
                ])
                ->add('end_date', DateType::class, [
                    'label' => 'End Date',
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'disabled' => true,
                    'attr' => [
                        'class' => 'datepicker',
                        'placeholder' => 'End Date',
                    ]
                ])
                ->add('in_time', TimeType::class, [
                    'widget' => 'single_text',
                    'html5' => false,
                    'error_bubbling' => FALSE,
                    'placeholder' => [
                        'hour' => 'Hour',
                        'minute' => 'Minute',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Time
                    ]
                ])
                ->add('out_time', TimeType::class, [
                    'widget' => 'single_text',
                    'html5' => false,
                    'error_bubbling' => FALSE,
                    'placeholder' => [
                        'hour' => 'Hour',
                        'minute' => 'Minute',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Time
                    ]
                ])
                ->add('status', EntityType::class, [
                    'class' => 'employeeBundle:Atemployeestatus',
                    'choice_label' => 'description',
                    'error_bubbling' => FALSE,
                    'attr' => [
                        //'class' => 'select2',
                        //'style' => "width:100%"
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                    ]
                ])
                ->add('department', EntityType::class,[
                    'class' => 'employeeBundle:Atdepartment',
                    'expanded' => FALSE,
                    'multiple' => FALSE,
                    'error_bubbling' => TRUE,
                    'choice_label' => 'name'
                ])


        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atcontract',
            'd' => 'd'
        ));
    }

}
