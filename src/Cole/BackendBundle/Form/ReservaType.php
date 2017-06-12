<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('profesor','entity',array('class' => 'BackendBundle:Profesor','query_builder' => function (\Cole\BackendBundle\Entity\ProfesorRepository $er) {return $er->createQueryBuilder('u')->where('u.activo IN(:act)')->setParameter(':act', 1)->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un profesor','required'=> false))
            ->add('equipamiento','entity',array('class' => 'BackendBundle:Equipamiento','query_builder' => function (\Cole\BackendBundle\Entity\EquipamientoRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.nombre','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un equipamiento','required'=> true))
            ->add('horario','entity',array('class' => 'BackendBundle:Horario','query_builder' => function (\Cole\BackendBundle\Entity\HorarioRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id','ASC');}, 'empty_data' => null,'empty_value'=> 'Seleccione un horario','required'=> true))
            ->add('seleccion','entity',array('class' => 'BackendBundle:Horario','multiple'=>true,'property' => 'id','mapped' => false,'expanded' => true,'query_builder' => function (\Cole\BackendBundle\Entity\HorarioRepository $er) {return $er->createQueryBuilder('u')->orderBy('u.id','ASC');},'required'=> false))
            ->add('fecha','datetime',array('date_widget'=> 'text','required'=> true))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Reserva'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cole_backendbundle_reserva';
    }
}
