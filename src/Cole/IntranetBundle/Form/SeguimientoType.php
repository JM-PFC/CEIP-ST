<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SeguimientoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion','textarea',array('label' => 'Descripción', 'attr' => array('type'=>'textarea')))
            ->add('asignatura','entity',array('class' => 'BackendBundle:AsignaturasCursos','query_builder' => function (\Cole\BackendBundle\Entity\AsignaturasCursosRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.asignatura', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un curso','required'=> false))
            ->add('grupo','entity',array('class' => 'BackendBundle:Grupo','query_builder' => function (\Cole\BackendBundle\Entity\GrupoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.letra', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un grupo','required'=> true))
            ->add('profesor','entity',array('class' => 'BackendBundle:Profesor','query_builder' => function (\Cole\BackendBundle\Entity\ProfesorRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un profesor','required'=> true))
            ->add('alumno','entity',array('class' => 'BackendBundle:Alumno','query_builder' => function (\Cole\BackendBundle\Entity\AlumnoRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un alumno','required'=> false))
            ->add('responsable','entity',array('class' => 'BackendBundle:Padres','query_builder' => function (\Cole\BackendBundle\Entity\PadresRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un responsable','required'=> false))
            ->add('seguimiento','entity',array('class' => 'IntranetBundle:Seguimiento','query_builder' => function (\Cole\IntranetBundle\Entity\SeguimientoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un seguimiento','required'=> false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Seguimiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'seguimiento';
    }
}
