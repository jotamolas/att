<?php
namespace att\attBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CertificateMedicalType extends AbstractType
{
    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $builder
                
                ->add('certificate', new AtcertificateType($this->em),[
                    'label' => false,
                    'data_class' => '\att\attBundle\Entity\Atcertificate', 
                    ])     
                ->add('profesional', TextType::class, [
                    'label' => false,
                    'error_bubbling' => true,
                    
                    ])
                ->add('matricula', TextType::class, [
                    'label' => false,
                    'error_bubbling' => true,
                    
                    ])
                ->add('diagnostico', TextareaType::class,[
                    'label' => false,
                    'error_bubbling' => true,
                    'attr' => ['class' => 'custom-control', 'style' => 'resize:none','rows' => 3]
                    ])
                ->add('indicareposo', CheckboxType::class,[
                    'error_bubbling' => true,
                    'required' => false,
                    'label' => false                    
                    ])
        
                
            ;
        }
    
}