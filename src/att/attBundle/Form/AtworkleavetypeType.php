<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtworkleavetypeType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('maxday', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,[
                    'label' => 'Maximum Days' 
                ])
                ->add('maxdaysseq', \Symfony\Component\Form\Extension\Core\Type\IntegerType::class,[
                    'label' => 'Consecutive Maximum Days'
                ])
                ->add('is_justifiable', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => [
                        TRUE => 'Yes',
                        FALSE => 'No'
                    ],
                    'expanded' => true,
                    'multiple' => false
                ])
                ->add('is_payable', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class, [
                    'choices' => [
                        TRUE => 'Yes',
                        FALSE => 'No'
                    ],
                    'expanded' => true,
                    'multiple' => false
                ])
                ->add('description', \Symfony\Component\Form\Extension\Core\Type\TextType::class)
                ->add('agreement', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                    'class' => 'employeeBundle:Atagreement',
                    'expanded' => false,
                    'multiple' => false
                ])
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'att\attBundle\Entity\Atworkleavetype'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'att_workleavetype';
    }

}
