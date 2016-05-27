<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CentroType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label' => 'Nombre', 'max_length' => 30,'attr' => array('validation' => 'Empty, LetterInitial')))
            ->add('direccion','text',array('label' => 'Dirección','max_length' => 50, 'attr' => array('validation' => 'Empty, LetterInitial')))
            ->add('localidad','text',array('label' => 'Localidad','max_length' => 50, 'attr' => array('validation' => 'Empty, Letters')))
            ->add('provincia','text',array('label' => 'Provincia','max_length' => 30,'attr' => array('validation' => 'Empty, Letters')))
            ->add('cp','text',array('label' => 'Código Postal', 'max_length' => 5, 'attr' => array('class' => 'cp','validation' => 'Empty,Length,CP')))
            ->add('telefono','text', array('label' => 'Teléfono', 'max_length' => 12, 'attr' => array('class' => 'telefono', 'lengthmin'=> 9, 'validation' => 'Length,Telefono')))
            ->add('email','email', array('label' => 'Email','required'=> false,'attr' => array('class' => 'text_transform_none','validation' => 'Email')))
            ->add('web','text',array('label' => 'Web', 'max_length' => 30,'attr' => array('class' => 'text_transform_none','validation' => 'LetterInitial')))
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))
        ;


    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Centro'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cole_backendbundle_centro';
    }
}
