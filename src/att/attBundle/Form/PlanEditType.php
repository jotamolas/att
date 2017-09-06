<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class PlanEditType extends AbstractType {

    public function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {

        $builder->add('date', DateType::class, [
                    'error_bubbling' => true,
                    'widget' => 'single_text',
                    'html5' => false,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Date(),
                    ]
                ])
                ->add('inplan', TimeType::class, [
                    'widget' => 'single_text',
                    'html5' => false,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Time
                    ],
                    'attr' => [
                        'class' => 'clockpicker'
                    ]
                ])
                ->add('outplan', TimeType::class, [
                    'widget' => 'single_text',
                    'html5' => false,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank(),
                        new \Symfony\Component\Validator\Constraints\Time
                    ],
                    'attr' => [
                        'class' => 'clockpicker'
                    ]
                ])
                ->add('stateplan', EntityType::class, [
                    'class' => 'attBundle:Atstateplan',
                    'choice_label' => 'description',
                    'placeholder' => 'Choose an option',
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank()
                    ]
                ])
                ->add('send', SubmitType::class, [
                    'label' => 'Save',
                ])
        ;
    }

    public function configureOptions(\Symfony\Component\OptionsResolver\OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\attBundle\Entity\Atplan'
        ));
    }

}
