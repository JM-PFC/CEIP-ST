<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfirmacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pregunta','text', array('label' => 'Pregunta', 'required'=> true,'max_length' => 100))
            ->add('respuesta','text', array('label' => 'Respuesta','required'=> true,'max_length' => 100))
            ->add('password', 'repeated', array('mapped' => false,'error_bubbling' => true,'required' => false, 'first_options'  => array('label' => 'Nueva contraseña'),'second_options' => array('label' => 'Repite contraseña'),'type' => 'password' ,'invalid_message'=> 'Las contraseñas deben ser iguales.'))
        ;
    }
    


    /**
     * @return string
     */
    public function getName()
    {
        return 'confirmacion';
    }
}