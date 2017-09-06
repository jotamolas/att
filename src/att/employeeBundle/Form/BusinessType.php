<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BusinessType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextType::class)
            ->add('state', TextType::class)
            ->add('country', TextType::class)
            ->add('company', EntityType::class , [
                                                'class' => 'employeeBundle:Atcompany',
                                                'property' => 'description'
                                                ])
            ->add('obs', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atbusiness'
        ));
    }
}
