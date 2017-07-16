<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BusquedaAdministrativoType extends AbstractType
{
    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('nombre','text',array('label' => 'Nombre', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            //->add('apellido1','text',array('label' => 'Primer Apellido', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            //->add('apellido2','text',array('label' => 'Segundo Apellido','max_length' => 30, 'attr' => array('validation' => 'Empty, Words')))
            ->add('nombre','text',array('label' => 'Nombre o Apellidos', 'max_length' => 100))
            ->add('limpiar', 'button', array('label' => 'Limpiar Búsqueda','attr' => array('class' => 'limpiar')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Administrativo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'busqueda_administrativo';
    }
}