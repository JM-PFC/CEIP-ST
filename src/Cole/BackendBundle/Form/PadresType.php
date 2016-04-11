<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PadresType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni','text',array('label' => 'DNI/NIE','max_length' => 10, 'attr' => array('lengthmin'=> 9,'validation' => ' Equal,Empty,Length,Dni', 'class' => 'dni')))
            ->add('nombre','text',array('label' => 'Nombre y Apellidos', 'max_length' => 80,'attr' => array('validation' => 'Empty, Words', 'class' => 'full_name')))
            ->add('fechaNacimiento','date',array('label' => 'Fecha de Nacimiento', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy', 'attr' => array('lengthmin'=> 8,'class' => 'fecha','validation' => 'Empty,Length,Fecha,Fecha_Adulto')))
            ->add('profesion','text',array('label' => 'Profesión','max_length' => 50,'attr' => array('validation' => 'Words','class' => 'normal')))
            ->add('estadoCivil','choice',array('label' => 'Estado Civil', 'choices' => array('Casado/a' => 'Casado/a', 'Soltero/a'=>'Soltero/a', 'Separado/a'=>'Separado/a', 'Divorciado/a'=>'Divorciado/a','Pareja de hecho'=>'Pareja de hecho','Viudo/a'=>'Viudo/a'),'required'=> true,'multiple'=>false))
            ->add('movil','text', array('label' => 'Móvil', 'max_length' => 12, 'attr' => array('lengthmin'=> 9,'class' => 'telefono','validation' => 'Length,Telefono')))
            ->add('email','email', array('label' => 'Email','required'=> false,'attr' => array('class' => 'email','validation' => 'Email')))
            //->add('username','text',array('label' => 'Usuario'))
            //->add('password','text', array('label' => 'Password'))
            //->add('salt')
            //->add('claveUsuario')
            //->add('activo')
            //->add('role')
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Padres',
       
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'responsable1';
    }
}
