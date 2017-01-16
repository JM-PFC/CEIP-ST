<?php

namespace Cole\ColeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NoticiasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titulo','text',array('label' => 'Título','max_length' => 100, 'attr' => array('placeholder'=>'Inserte un título')))
            ->add('descripcion','textarea',array('label' => 'Descripción', 'attr' => array('type'=>'textarea', 'validation' => 'Empty')))
            ->add('fecha')
            ->add('categoria','choice',array('label' => 'Categoría', 'choices' => array('general' => 'General', 'infantil'=>'Ed. Infantil', 'primaria'=>'Ed. Primaria', 'profesores'=>'Profesores'),'required'=> true,'multiple'=>false))
            ->add('foto')
            ->add('galeria')
            ->add('contador')
            ->add('mostrarFoto')                  
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\ColeBundle\Entity\Noticias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'noticias';
    }
}
