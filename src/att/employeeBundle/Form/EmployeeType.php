<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;

class EmployeeType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {


        $builder
                ->add('name', TextType::class, [
                    'label' => 'Name',
                    'error_bubbling' => FALSE,
                        ]
                )
                ->add('surname', TextType::class, [
                    'label' => 'Last Name'
                        ]
                )
                ->add('dni', TextType::class, [
                    'label' => 'Dni',
                    'error_bubbling' => FALSE,

                ])
                ->add('sex', ChoiceType::class, [
                    'choices' => [
                        '' => NULL,
                        'F' => 'Female',
                        'M' => 'Male',
                    ]
                ])
                ->add('birthday', DateType::class, [
                    'label' => 'Birthday',
                    'error_bubbling' => true,
                    'widget' => 'single_text',
                    'html5' => false,
                        // 'format' => 'Y-m-d'   
                ])
                ->add('email', EmailType::class, ['label' => 'E-Mail'])
                ->add('celphone', PhoneNumberType::class, [
                    'label' => 'Cell Phone',
                    'default_region' => 'AR',
                    'format' => PhoneNumberFormat::NATIONAL
                ])
                ->add('phone', PhoneNumberType::class, [
                    'label' => 'Phone',
                    'default_region' => 'AR',
                    'format' => PhoneNumberFormat::NATIONAL
                ])
                
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function setDefaultOptions(\Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atemployee',
            
        ));
    }

}
