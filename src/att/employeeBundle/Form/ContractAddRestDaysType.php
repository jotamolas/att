<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ContractAddRestDaysType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $qty_restdays = 7 - $options['qty_work_days'];
        $builder
                ->add('rest_days', EntityType::class, [
                    'choice_translation_domain' => true,
                    'class' => 'employeeBundle:Atrestday',
                    'expanded' => FALSE,
                    'multiple' => TRUE,
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\Count([
                            'min' => $qty_restdays,
                            'max' => $qty_restdays
                                ]),
                    ],
                    'attr' => [
                        'size' => 7
                    ]
                ]);
               
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atcontract',
            'qty_work_days' => 'qty_work_days'
        ));
    }

}
