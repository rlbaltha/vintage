<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PointType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array('label'=> 'Title','required'  => false,'attr' => array('class' => 'form-control')))
            ->add('body', 'ckeditor', array('config_name' => 'editor_default','label'  => 'Content for popup',))
            ->add('location', 'entity', array('class' => 'AppBundle:Location',
                'property' => 'title','expanded'=>false,'multiple'=>false,'label'  => 'Select Location', 'required'=> false,'attr' => array('class' =>
                    'form-control'),))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Point'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_point';
    }
}
