<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdministrativoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni','text',array('label' => 'DNI/NIE','max_length' => 10, 'attr' => array('lengthmin'=> 9,'validation' => ' Equal,Empty,Length,Dni', 'class' => 'dni')))
            ->add('nombre','text',array('label' => 'Nombre', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido1','text',array('label' => 'Primer Apellido', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido2','text',array('label' => 'Segundo Apellido','max_length' => 30, 'attr' => array('validation' => 'Empty, Words')))
            ->add('direccion','text',array('label' => 'Dirección','max_length' => 50, 'attr' => array('validation' => 'Empty, LetterInitial')))
            ->add('localidad','text',array('label' => 'Localidad','max_length' => 50, 'attr' => array('validation' => 'Empty, Letters')))
            ->add('provincia','text',array('label' => 'Provincia','max_length' => 30,'attr' => array('validation' => 'Empty, Letters')))
            ->add('cp','text',array('label' => 'Código Postal', 'max_length' => 5, 'attr' => array('class' => 'cp','validation' => 'Empty,Length,CP')))
            ->add('telefono','text', array('label' => 'Teléfono','required'=> false, 'max_length' => 12, 'attr' => array('class' => 'telefono', 'lengthmin'=> 9, 'validation' => 'Length,Telefono')))
            ->add('movil','text', array('label' => 'Móvil', 'required'=> false,'max_length' => 12, 'attr' => array('lengthmin'=> 9,'class' => 'telefono','validation' => 'Length,Telefono')))
            ->add('email','email', array('label' => 'Email','required'=> false,'attr' => array('class' => 'text_transform_none','validation' => 'Email')))
            ->add('fechaAlta','date',array('label' => 'Fecha Alta', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy'))
            ->add('fechaBaja','date',array('label' => 'Fecha Baja', 'max_length' => 10,'required' => false,'widget' => 'single_text','format' => 'dd/MM/yyyy'))
            //->add('username')
            //->add('password')
            //->add('salt')
            //->add('password', 'repeated', array('error_bubbling' => true,'required' => false, 'first_options'  => array('label' => 'Nueva contraseña'),'second_options' => array('label' => 'Repite contraseña'),'type' => 'password' ,'invalid_message'=> 'Las contraseñas deben ser iguales.'))
            ->add('tipo', 'choice', array('label' => 'Tipo de personal','choices' => array('Administrativo' => 'Administrativo', 'Administrador web'=>'Administrador web'),'required'=> true, 'expanded'=>true, 'multiple'=>false))
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))
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
        return 'administrativo';
    }
}
