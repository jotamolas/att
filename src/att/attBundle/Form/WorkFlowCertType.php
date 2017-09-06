<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;



class WorkFlowCertType extends AbstractType{
    
    
     /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                ->add(
                        'certificates', EntityType::class,
                        [
                           'class'   => 'attBundle:Atcertificate',
                           'choices' => $options['certificates'],
                           'expanded' => TRUE,
                           'multiple' => FALSE,
                           'required' => TRUE, 
                        ]);
                

    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '\att\attBundle\Entity\Atcertificate',
            'certificates'    => 'certificates',
            'wf'              => 'wf',
            'csrf_protection' => false
        ));
    }
}
