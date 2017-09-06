<?php

namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AtcertificateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
                
            ->add('datefrom', DateType::class, [
                'label'   => false,
                'error_bubbling' => true,
                'widget'  => 'single_text',
                'html5'   => false,
                'attr'   => [
                    'class'   => 'form-control-sm',
                     ]
            ])
                
            ->add('dateto', DateType::class, [
                'label' => false,
                'error_bubbling' => true,
                'widget'=> 'single_text',
                'html5' => false,
                'attr'   => [
                        'class'   => 'form-control-sm',
                        ]
            ])            
            ->add('details', TextareaType::class , [
                //'label' => false,
                'error_bubbling' => true,
                'attr' => [
                    'class' => 'custom-control',
                    'style' => 'resize:none',
                    'rows'   => 2
                    ]
            ])            
            ->add('scanFile', FileType::class, [
                'label' => false,
                'error_bubbling' => true,
                'attr' =>[
                    'class' => 'fileInput form-control-sm'
                    ]
            ])         
            
            
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\att\attBundle\Entity\Atcertificate'
            
        ));
    }
}
