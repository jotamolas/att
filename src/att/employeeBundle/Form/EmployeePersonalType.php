<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class EmployeePersonalType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    'label' => FALSE,
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'placeholder' => 'Name'
                    ]
                ])
                ->add('surname', TextType::class, ['label' => FALSE,
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'placeholder' => 'Last Name'
                    ]
                ])
                ->add('dni', TextType::class, [
                    'label' => FALSE,
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'placeholder' => 'Dni'
                    ]
                ])
                ->add('sex', ChoiceType::class, [
                    'choices' => [
                        'F' => 'Female',
                        'M' => 'Male',
                    ],
                    'expanded' => TRUE,
                    'multiple' => FALSE,
                    'label' => 'Gender',
                    'label_attr' => [
                        'class' => 'radio-inline'
                    ],
                    'empty_data' => null,
                    'placeholder' => 'Choose your gender',
                    'error_bubbling' => false
                ])
                ->add('birthday', DateType::class, [
                    'label' => FALSE,
                    'error_bubbling' => false,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'datepicker',
                        'placeholder' => 'Birthday',
                    ]
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atemployee'
        ));
    }

}
