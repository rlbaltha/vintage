<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FileType extends AbstractType
{
    protected $options;

    public function __construct (array $options)
    {
        $this->options = $options;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options = $this->options;
        $builder
            ->add('file','file', array('label'  => 'Image to Upload', 'attr' => array('class' => 'btn-file')))
            ->add('title', 'text', array('label'=> 'Title','required'  => false,'attr' => array('class' => 'form-control')))
            ->add('body', 'textarea', array('label'=> 'Text for point','required'  => false,'attr' => array('class' => 'form-control')))
            ->add('location', 'entity', array('class' => 'AppBundle:Location','property'=>'title','query_builder' =>
                function(\AppBundle\Entity\LocationRepository $er) use ($options) {
                    $mapid = $options['mapid'] ;
                    return $er->createQueryBuilder('l')
                        ->join("l.map", 'm')
                        ->where('m.id = :id')
                        ->orderBy('l.title')
                        ->setParameter('id', $mapid);
                }, 'expanded'=>false,'multiple'=>false, 'label'  => 'Select Location', 'attr' => array('class' => 'form-control'),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\File'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_file';
    }
}
