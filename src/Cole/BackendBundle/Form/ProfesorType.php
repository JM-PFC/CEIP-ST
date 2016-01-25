<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfesorType extends AbstractType
{
    /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('nombre')
            ->add('apellido1')
            ->add('apellido2')
            ->add('direccion')
            ->add('localidad')
            ->add('provincia')
            ->add('cp')
            ->add('telefono','text', array('label' => 'Teléfono', 'max_length' => 9, 'attr' => array('validation' => 'empty,lenght', 'class' => 'telefono')))
            ->add('movil')
            //->add('email')
            ->add('fechaAlta')
            ->add('foto', 'file', array('required' => false))
            ->add('username')
            ->add('password')
            //->add('salt') No debe modificarlo el usuario
            ->add('claveUsuario')
            ->add('activo')
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))

            //->add('role') No debe modificarlo el usuario
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Profesor'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cole_backendbundle_profesor';
    }
}
