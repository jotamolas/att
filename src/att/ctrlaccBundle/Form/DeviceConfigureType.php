<?php

namespace att\ctrlaccBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DeviceConfigureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('table_or_method', 
                TextType::class,
                [
                    'label' => 'Table or Method'
                ])
                ->add('id_attribute', TextType::class,[
                    'label' => 'Id Original'
                ])
                ->add('eventdate_attribute', TextType::class, [
                    'label' => 'Event Date Attribute'
                ])
                ->add('eventime_attribute', TextType::class, [
                    'label' => 'Event Time Attribute'
                ])
                ->add('employee_attribute', TextType::class, [
                    'label' => 'Employee Attribute'
                ])
                ->add('event_attribute', TextType::class, [
                    'label' => 'Event Attribute'
                ])
                ->add('inEvent_value', TextType::class, [
                    'label' => 'In Event Value'
                ])
                ->add('outEvent_value', TextType::class, [
                    'label' => 'Out Event Value'
                ])
                
                
                        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\att\ctrlaccBundle\Entity\DeviceMetadataConfiguration',
        ));
    }
    
}
