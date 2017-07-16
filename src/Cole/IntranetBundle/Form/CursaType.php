<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CursaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nota')
            ->add('ord')
            ->add('alumno')
            ->add('asignaturasCursos')
            ->add('tarea')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Cursa'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cursa';
    }
}
