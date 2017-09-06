<?php

namespace att\attBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class PlanType extends AbstractType {
   
    
    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        
        $builder->add('datefrom', DateType::class,[
                'label'   => false,
                'error_bubbling' => true,
                'widget'  => 'single_text',
                'html5'   => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Date(),
                ]
            ])
                ->add('dateto', DateType::class,[
                'label'   => false,
                'error_bubbling' => true,
                'widget'  => 'single_text',
                'html5'   => false,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Date(),
                ]
            ])
                ->add('inplan', TimeType::class,[
                    'input' => 'string',
                    'widget'  => 'single_text',
                    'html5'   => false,
                    'placeholder' => [
                        'hour' => 'Hour',
                        'minute' => 'Minute',
                    ] ,
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                    new \Symfony\Component\Validator\Constraints\Time
                ]    
                ])
                
                ->add('outplan', TimeType::class,[
                    'input' => 'string',
                    'widget'  => 'single_text',
                    'html5'   => false,
                    'placeholder' => [
                        'hour' => 'Hour',
                        'minute' => 'Minute',
                    ],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Time
                    ]
                ])
                
                ->add('stateplan', EntityType::class, [
                        'class' => 'attBundle:Atstateplan',
                        'choice_label' => 'description',
                        'constraints' => [
                            new \Symfony\Component\Validator\Constraints\NotBlank()
                    
                        ]
                ])
                
                ->add('employee', EntityType::class,[
                    'class' => 'employeeBundle:Atemployee',
                    'query_builder' => function(EntityRepository $er){
                      return $er->createQueryBuilder('e')
                              ->leftJoin('e.contracts', 'c')
                              ->leftJoin('c.status', 's')
                              ->where("s.description = 'Active'");
                    } ,
                    
                    'expanded' => false,
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2'
                    ],
                    'constraints' => [
                                        new \Symfony\Component\Validator\Constraints\NotBlank()
                                     ]
                ])
                            
                 ->add('send', 
                       SubmitType::class, 
                        [
                            'label' => 'Save',
                        ])           
                ;
    }
    
}
