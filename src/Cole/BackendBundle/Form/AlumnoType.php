<?php

namespace Cole\BackendBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AlumnoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido1','text',array('label' => 'Primer Apellido', 'max_length' => 30,'attr' => array('validation' => 'Empty, Words')))
            ->add('apellido2','text',array('label' => 'Segundo Apellido','max_length' => 30, 'attr' => array('validation' => 'Empty, Words')))
            //->add('sexo', 'choice', array('label' => 'Sexo','choices' => array('Masculino' => 'Masculino', 'Femenino'=>'Femenino'),'data'=>'Masculino','required'=> true, 'expanded'=>true, 'multiple'=>false))
            ->add('sexo', 'choice', array('label' => 'Sexo','choices' => array('Masculino' => 'Masculino', 'Femenino'=>'Femenino'),'required'=> true, 'expanded'=>true, 'multiple'=>false))
            ->add('fechaNacimiento','date',array('label' => 'Fecha de Nacimiento', 'max_length' => 10,'widget' => 'single_text','format' => 'dd/MM/yyyy', 'attr' => array('lengthmin'=> 8,'class' => 'fecha', 'placeholder'=>'__/__/____','validation' => 'Empty,Length,Fecha,Fecha_Niño')))
            //->add('fechaNacimiento', 'birthday', array('format'=> 'dd MMMM yyyy', 'widget'=> 'choice', 'years'=> range(date('Y')-15, date('Y')-2), 'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => 'Día')))
            ->add('direccion','text',array('label' => 'Dirección','max_length' => 50, 'attr' => array('validation' => 'Empty, LetterInitial')))
            ->add('localidad','text',array('label' => 'Localidad','max_length' => 50, 'attr' => array('validation' => 'Empty, Letters')))
            ->add('provincia','text',array('label' => 'Provincia','max_length' => 30,'attr' => array('validation' => 'Empty, Letters')))
            ->add('cp','text',array('label' => 'Código Postal', 'max_length' => 5, 'attr' => array('class' => 'cp','validation' => 'Empty,Length,CP')))
            ->add('telefono','text', array('label' => 'Teléfono', 'max_length' => 12, 'attr' => array('class' => 'telefono', 'lengthmin'=> 9, 'validation' => 'Length,Telefono')))
            //->add('fechaAlta','date',array('label' => 'Fecha de Alta','read_only' => 'true','widget' => 'single_text','format' => 'dd/MM/yyyy')))
            ->add('cursoIngreso','entity',array('class' => 'BackendBundle:Curso', 'empty_data' => null,'empty_value'=> 'Seleccione un curso','required'=> true, 'attr' => array('validation' => 'Empty')))

            //->add('curso')
            //->add('grupo')
            ->add('foto', 'file', array('data_class' => null,'required' => false, 'attr' => array( 'class' => 'archivo','size' => 50, 'mimeTypes' => '.png,.jpg,.jpeg' ,'validation' => 'MimeTypes, MaxSize')))
            //->add('numAlum')
            ->add('grupoSangre','choice',array('empty_value'=> 'Seleccione una opción','label' => 'Grupo Sanguíneo', 'choices' => array('A+' => 'A+', 'A-'=>'A-', 'B+'=>'B+', 'B-'=>'B-','AB+'=>'AB+','AB-'=>'AB-','0+'=>'0+','0-'=>'0-'),'required'=> false,'multiple'=>false))

            ->add('observaciones', 'textarea',array('label' => 'Observaciones:', 'max_length' => 500, 'attr' => array('type'=>'textarea', 'validation' => 'Words')))
            //->add('activo')
            //->add('responsable1')
            //->add('responsable2')
            ->add('responsable1', new PadresType(),array('label' => 'Datos Primer Responsable (Madre/Tutora)'))
            ->add('responsable2', new PadresType(),array('label' => 'Datos Segundo Responsable (Padre/Tutor)','attr' => array('id' => 'responsable2')))
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Alumno',
            'cascade_validation' => true,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        //return 'cole_backendbundle_alumno';
        return 'alumno';
    }
}
