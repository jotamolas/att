<?php

namespace att\medicalsrvBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MedicalServiceType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('name', TextType::class)
                ->add('token', TextType::class)
                ->add('cuit', TextType::class)
                ->add('state', \Symfony\Component\Form\Extension\Core\Type\ChoiceType::class,[
                    'choices' => [
                        1 => 'Active',
                        0 => 'Down'
                    ]
                ])
                ->add('externalsystem', EntityType::class, [
                    'class' => 'syncBundle:Atexternsystem',
                    'choice_label' => 'name',
                    'expanded' => FALSE,
                    'multiple' => FALSE,
                    'required' => FALSE,
                    'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('es')
                                ->where("es.module = 'medicalsrv'")
                                ;
                    },
                ])
                ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\medicalsrvBundle\Entity\Atmedicalservice',
        ));
    }

}
