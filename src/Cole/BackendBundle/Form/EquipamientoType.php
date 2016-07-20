<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EquipamientoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('attr' => array('title'=> 'Nombre','validation' => 'Empty_')))
            ->add('unidades','integer',array('attr' => array('min' => 1, 'max' => 99,'title'=> 'Unidades','validation' => 'Empty_')))
            ->add('tipo','text',array('attr' => array('validation' => 'Empty_')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Equipamiento'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'equipamiento';
    }
}
