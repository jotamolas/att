<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class DepartmentType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, [
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank()
                    ]
                ])
                ->add('business', EntityType::class, [
                    'class' => 'employeeBundle:Atbusiness',
                    'expanded' => FALSE,
                    'multiple' => FALSE,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank()
                    ]
                ])
                
                


        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atdepartment'
        ));
    }

}
