<?php

namespace att\attBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NotificationType extends AbstractType {
    
    
    /*
     * @param FormBuilderInterface $builder
     * @param array $options
     * 
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder->add('fromdate', DateType::class,[
                    'error_bubbling' => true,
                    'widget'  => 'single_text',
                    'html5'   => false,
                    'attr'   => [
                    'class'   => 'input-sm',                    
                    ]
                ])
                
                ->add('todate', DateType::class, [
                    'error_bubbling' => true,
                    'widget' => 'single_text',
                    'html5' => false,
                    'attr' => [
                        'class' => 'input-sm',
                        
                    ]
                ])
                
                ->add('subject', \Symfony\Component\Form\Extension\Core\Type\TextareaType::class)
                
                ->add('absencetype', EntityType::class, [
                    'class' => 'attBundle:Atworkleavetype',
                    'property' => 'description'
                ])
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }
    
    /*
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
            $resolver->setDefaults([
                'data_class' => '\att\attBundle\Entity\Atabsencenotification',
            ]);
        
    }
}
