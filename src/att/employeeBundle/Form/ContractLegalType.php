<?php

namespace att\employeeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContractLegalType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('file_number', TextType::class, [
                    'label' => 'File Number',
                    'error_bubbling' => FALSE,
                    'attr' => ['placeholder' => 'File Number'],
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\NotBlank()
                    ]
                        
                ])
                ->addEventSubscriber(new EventListener\AddAgreementFieldSuscriber())
                ->addEventSubscriber(new EventListener\AddSchemaFieldSuscriber())


        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => '\att\employeeBundle\Entity\Atcontract',
        ));
    }

}
