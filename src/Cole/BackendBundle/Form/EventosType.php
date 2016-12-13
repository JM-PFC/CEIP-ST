<?php

namespace Cole\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EventosType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array('label' => 'Título','attr' => array('validation' => 'Empty')))
            ->add('description')
            ->add('description','textarea',array('label' => 'Descripción', 'attr' => array('type'=>'textarea', 'validation' => 'Empty')))
            ->add('datetime')
            ->add('categoria')
            ->add('hora')
            ->add('contador')
            ->add('limpiar', 'button', array('attr' => array('class' => 'limpiar')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Eventos'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'eventos';
    }
}
