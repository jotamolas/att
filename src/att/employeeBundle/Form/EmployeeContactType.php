<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Misd\PhoneNumberBundle\Form\Type\PhoneNumberType;
use libphonenumber\PhoneNumberFormat;

class EmployeeContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder     
            ->add('email', EmailType::class, [
                'label'=> FALSE, 
                'error_bubbling' => FALSE, 
                'attr' => ['placeholder' => 'E-Mail']])
            
            ->add('celphone', PhoneNumberType::class, [
                'label' => FALSE,
                'error_bubbling' => FALSE,
                'default_region' => 'AR',
                'format' => PhoneNumberFormat::NATIONAL,
                'attr' => ['placeholder' => 'Cell Phone']
                ])            
            ->add('phone', PhoneNumberType::class, [
                'label' => FALSE,
                'error_bubbling' => FALSE,
                'default_region' => 'AR',
                'format' => PhoneNumberFormat::NATIONAL,
                'attr' => ['placeholder' => 'Phone']
                    
                ])
        ;
    }

    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atemployee'
        ));
    }

}
