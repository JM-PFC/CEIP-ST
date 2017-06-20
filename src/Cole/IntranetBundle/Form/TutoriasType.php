<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TutoriasType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('fecha', 'text', array('required' => true,'label' => 'Fecha','attr' => array('class' => 'form-control input-inline datetimepicker','data-provide' => 'datepicker','data-format' => 'dd-mm-yyyy HH:ii')))
            ->add('fecha','datetime',array('date_widget'=> 'text','required'=> true))
            ->add('grupo','entity',array('mapped' => false, 'class' => 'BackendBundle:Grupo','query_builder' => function (\Cole\BackendBundle\Entity\GrupoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.letra', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un grupo','required'=> true))
            ->add('profesor','entity',array('class' => 'BackendBundle:Profesor','query_builder' => function (\Cole\BackendBundle\Entity\ProfesorRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un profesor','required'=> true))
            ->add('alumno','entity',array('class' => 'BackendBundle:Alumno','query_builder' => function (\Cole\BackendBundle\Entity\AlumnoRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un alumno','required'=> false))
            ->add('responsable','entity',array('class' => 'BackendBundle:Padres','query_builder' => function (\Cole\BackendBundle\Entity\PadresRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un responsable','required'=> false))
            //->add('seguimiento', new SeguimientoType())
            ->add('tipo', 'choice', array('mapped' => false,'choices' => array('1' => 'Profesor', '0' =>'Alumno'),'required'=> true, 'expanded'=>true, 'multiple'=>false))
            ->add('descripcion','textarea',array('mapped' => false, 'label' => 'Descripción', 'attr' => array('type'=>'textarea')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Tutorias'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cole_intranetbundle_tutorias';
    }
}
