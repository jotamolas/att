<?php

namespace att\utilBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use att\utilBundle\Form\DataTransformer\EntityToIdTransformer;     


class AutocompleteEntityType extends AbstractType{
    
    private $om;
    
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }   
    
     public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $class = $options['class'];

        $transformer = new EntityToIdTransformer($this->om, $class);
        $builder->addViewTransformer($transformer);

    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['update_route'] = $options['update_route'];
    }

    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class', 'update_route'));
    }
    
    
    public function getParent()
    {
        return 'hidden';
    }
    
    public function getName()
    {
        return 'autocomplete_entity';
    }
    
    
}
