<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AsignaturaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre', 'attr' => array('validation' => 'Empty_')))
            ->add('tipo')
            ->add('abreviatura','text',array('label' => 'Abreviatura', 'max_length' => 15, 'attr' => array('validation' => 'Empty_')))
            ->add('opcional', 'checkbox', array('label' => 'Opcional','required'=> false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Asignatura'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'asignatura';
    }
}
