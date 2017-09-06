<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class WorkFlowMsgType extends AbstractType{
    
    
    /**
     * 
     * @param \att\attBundle\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('details',
                       TextareaType::class , 
                        [
                            'label' => 'Message',
                            'error_bubbling' => true,
                            'attr' =>  
                            [   
                                'class' => 'custom-control',
                                'style' => 'resize:none',
                                'rows'   => 6
                            ]       
                        ]
                    )
                ->add('workflow',
                        EntityType::class,
                        [
                           'class' => 'attBundle:Atworkflow',
                           'choices'   => $options['wf']                           
                        ])
                ->add('send', 
                       SubmitType::class, 
                        [
                            'label' => 'Send',
                        ]);
    }
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => '\att\attBundle\Entity\Atworkflowmsg',
            'wf'              => 'wf',
        ));
    }
    
}
