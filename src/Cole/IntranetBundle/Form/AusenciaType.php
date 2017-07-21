<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AusenciaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('tipo')
            ->add('justificacion','textarea',array('label' => 'Justificación', 'attr' => array('type'=>'textarea','required'=> false)))
            //->add('alumno','entity',array('class' => 'BackendBundle:Alumno','query_builder' => function (\Cole\BackendBundle\Entity\AlumnoRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un alumno','required'=> true))
            ->add('asignatura','entity',array('class' => 'BackendBundle:AsignaturasCursos','query_builder' => function (\Cole\BackendBundle\Entity\AsignaturasCursosRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.asignatura', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un curso','required'=> true))
            ->add('responsable','entity',array('class' => 'BackendBundle:Padres','query_builder' => function (\Cole\BackendBundle\Entity\PadresRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un responsable','required'=> false))
            ->add('horario','entity',array('class' => 'BackendBundle:Horario','query_builder' => function (\Cole\BackendBundle\Entity\HorarioRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un horario','required'=> true))
            ->add('faltas','entity',array('class' => 'BackendBundle:Alumno','multiple'=>true,'property' => 'id','mapped' => false,'expanded' => true,'query_builder' => function (\Cole\BackendBundle\Entity\AlumnoRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.id','ASC');},'required'=> false))
            ->add('retrasos','entity',array('class' => 'BackendBundle:Alumno','multiple'=>true,'property' => 'id','mapped' => false,'expanded' => true,'query_builder' => function (\Cole\BackendBundle\Entity\AlumnoRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.id','ASC');},'required'=> false))
            ->add('fecha','datetime',array('date_widget'=> 'text','required'=> true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Ausencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ausencia';
    }
}
