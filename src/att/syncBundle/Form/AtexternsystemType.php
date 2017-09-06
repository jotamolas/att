<?php

namespace att\syncBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AtexternsystemType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    'label' => 'Name',
                    'error_bubbling' => FALSE,
                ])
                ->add('ip', TextType::class, [
                    'label' => 'Ip',
                    'error_bubbling' => FALSE,
                ])
                ->add('port', TextType::class, [
                    'label' => 'Port',
                    'error_bubbling' => FALSE,
                ])
                ->add('type', EntityType::class, [
                    'label' => 'Type',
                    'empty_data' => ' ',
                    'class' => 'syncBundle:Atexternsystemtype',
                    'property' => 'description'
                ])
                ->add('module', ChoiceType::class, [
                    'label' => 'Module',
                    'expanded' => false,
                    'multiple' => false,
                    'empty_data' => ' ',
                    'choices' => [
                        'mod_employee' => 'Employee',
                        'mod_attendance' => 'Attendance',
                        'mod_ac' => 'Access Control',
                        'mod_ms' => 'Medical Service',
                        'mod_plan' => 'Planification'
                    ]
                ])
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class, [
                    'label' => 'Save'
                ])

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\syncBundle\Entity\Atexternsystem'
        ));
    }

}
