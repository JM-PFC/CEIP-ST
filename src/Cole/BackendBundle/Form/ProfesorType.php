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
            ->add('dni','text',array('label' => 'DNI/NIE','max_length' => 10, 'attr' => array('lengthmin'=> 9,'validation' => ' Equal,Empty,Length,Dni', 'class' => 'dni')))
            ->add('nombre','text',array('label' => 'Nombre', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido1','text',array('label' => 'Primer Apellido', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido2','text',array('label' => 'Segundo Apellido','max_length' => 30, 'attr' => array('validation' => 'Empty, Words')))
            ->add('sexo', 'choice', array('label' => 'Sexo','choices' => array('Masculino' => 'Masculino', 'Femenino'=>'Femenino'),'required'=> true, 'expanded'=>true, 'multiple'=>false))
            ->add('fechaNacimiento','date',array('label' => 'Fecha Nacimiento', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy', 'attr' => array('lengthmin'=> 8,'class' => 'fecha', 'placeholder'=>'__/__/____','validation' => 'Empty,Length,Fecha,Fecha_Adulto')))
            ->add('direccion','text',array('label' => 'Dirección','max_length' => 50, 'attr' => array('validation' => 'Empty, LetterInitial')))
            ->add('localidad','text',array('label' => 'Localidad','max_length' => 50, 'attr' => array('validation' => 'Empty, Letters')))
            ->add('provincia','text',array('label' => 'Provincia','max_length' => 30,'attr' => array('validation' => 'Empty, Letters')))
            ->add('cp','text',array('label' => 'Código Postal', 'max_length' => 5, 'attr' => array('class' => 'cp','validation' => 'Empty,Length,CP')))
            ->add('telefono','text', array('label' => 'Teléfono', 'max_length' => 12, 'attr' => array('class' => 'telefono', 'lengthmin'=> 9, 'validation' => 'Length,Telefono')))
            ->add('movil','text', array('label' => 'Móvil', 'max_length' => 12, 'attr' => array('lengthmin'=> 9,'class' => 'telefono','validation' => 'Length,Telefono')))
            ->add('email','email', array('label' => 'Email','required'=> false,'attr' => array('class' => 'text_transform_none','validation' => 'Email')))
            //->add('fechaAlta')
            ->add('foto', 'file', array('data_class' => null,'required' => false, 'attr' => array( 'class' => 'archivo','size' => 50, 'mimeTypes' => '.png,.jpg,.jpeg','validation' => 'MimeTypes, MaxSize')))
            ->add('perfilAcademico', 'textarea',array('label' => 'Observaciones:', 'max_length' => 1200, 'attr' => array('type'=>'textarea', 'validation' => 'Words')))
            ->add('perfilProfesional', 'textarea',array('label' => 'Observaciones:', 'max_length' => 1200, 'attr' => array('type'=>'textarea', 'validation' => 'Words')))
            ->add('nivel', 'choice', array('label' => 'Profesor de:','choices' => array('Infantil' => 'Infantil', 'Primaria'=>'Primaria'), 'empty_data' => null,'empty_value'=> 'Seleccione un nivel','required'=> true, 'multiple'=>false, 'attr' => array('validation' => 'Empty')))
            ->add('fechaAlta','date',array('label' => 'Fecha Alta', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy'))
            ->add('fechaBaja','date',array('label' => 'Fecha Baja', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy'))
            ->add('observaciones', 'textarea',array('label' => 'Observaciones:', 'max_length' => 500, 'attr' => array('type'=>'textarea')))
            ->add('observaciones', 'textarea',array('label' => 'Observaciones:', 'max_length' => 500, 'attr' => array('type'=>'textarea')))
            ->add('horas','integer', array('label' => 'Jornada Laboral','required' => true,'attr' => array('min' => 15,'max' => 40,'step' => 0.5,'validation' => ' Empty')))
            ->add('horasLectivas','integer', array('label' => 'Horas lectivas (Horas de clase)','required' => true,'attr' => array('min' => 5,'max' => "25",'step' => 0.5,'validation' => ' Empty')))
            //->add('username')
            //->add('password')
            //->add('salt')
            //->add('claveUsuario')
            //->add('activo')
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
        return 'profesor';
    }
}
