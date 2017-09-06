<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmployeeAddressingType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                
                ->add('autocomplete' , TextType::class,[
                    'mapped' => FALSE
                ])
                
                
                ->add('address', TextType::class, [
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'disabled' => TRUE,
                        'class' => 'field'
                    ]
                ])
                ->add('city', TextType::class, [
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'disabled' => TRUE,
                        'class' => 'field'
                    ]
                ])
                ->add('state', TextType::class, [
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'disabled' => TRUE,
                        'class' => 'field'
                    ]
                ])
                ->add('addresslat', TextType::class, [
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'disabled' => TRUE,
                        'class' => 'field'
                    ]
                ])
                ->add('addresslng', TextType::class, [
                    'error_bubbling' => FALSE,
                    'attr' => [
                        'disabled' => TRUE,
                        'class' => 'field'
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
