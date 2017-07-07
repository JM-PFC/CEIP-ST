<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TareaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            //->add('fecha')
            ->add('grupo','entity',array('class' => 'BackendBundle:Grupo','query_builder' => function (\Cole\BackendBundle\Entity\GrupoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.letra', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un grupo','required'=> true))
            ->add('profesor','entity',array('class' => 'BackendBundle:Profesor','query_builder' => function (\Cole\BackendBundle\Entity\ProfesorRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un profesor','required'=> true))
            ->add('asignatura','entity',array('class' => 'BackendBundle:AsignaturasCursos','query_builder' => function (\Cole\BackendBundle\Entity\AsignaturasCursosRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.curso','ASC')->addOrderBy('u.asignatura', 'ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un curso','required'=> false))
            ->add('seleccion','entity',array('class' => 'BackendBundle:Grupo','multiple'=>true,'property' => 'id','mapped' => false,'expanded' => true,'query_builder' => function (\Cole\BackendBundle\Entity\GrupoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id','ASC');},'required'=> false))
            ->add('trimestre','choice',array('label' => 'Trimestre',  'mapped' => false, 'choices' => array('1' => '1º Trimestre', '2'=>'2º Trimestre', '3'=>'3º Trimestre'),'empty_data' => null,'required'=> true,'multiple'=>false))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Tarea'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tarea';
    }
}
