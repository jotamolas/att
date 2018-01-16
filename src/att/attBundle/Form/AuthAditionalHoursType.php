<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AuthAditionalHoursType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('isapproved', ChoiceType::class,[
                    'label' => 'Is Approved?',
                    'choices' => [
                        TRUE => 'Yes',
                        FALSE => 'No'
                    ],
                    'expanded' => true,
                    'multiple' => false
                ])
                ->add('type', EntityType::class, [
                    'class' => 'attBundle:Ataditionalhourstype',
                    'choice_label' => function ($aditionalhourstype) {
                        return $aditionalhourstype->getName() . " " . $aditionalhourstype->getDescription();
                    },
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er ) use ( $options ) {
                        return $er->createQueryBuilder('aht')
                                ->leftJoin('aht.agreement', 'a')
                                ->where('a.id = :id')
                                ->setParameter('id', $options['agreement_id']);
                    }
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\attBundle\Entity\Ataditionalhoursdetail',
            'agreement_id' => 'agreement_id'
        ));
    }

}
