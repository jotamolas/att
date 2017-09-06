<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtworkleaveType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateFrom')->add('dateTo')
                ->add('type', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class,[
                    'class' => 'attBundle:Atworkleavetype',
                    'choice_label' => 'description'
                ]);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'att\attBundle\Entity\Atworkleave'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'att_attbundle_atworkleave';
    }


}
