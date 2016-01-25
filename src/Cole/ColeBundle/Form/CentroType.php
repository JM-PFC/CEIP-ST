<?php

namespace Cole\ColeBundle\Form;

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
            ->add('nombre')
            ->add('direccion')
            ->add('localidad')
            ->add('provincia')
            ->add('cp')
            ->add('telefono')
            ->add('fax')
            ->add('web')
            ->add('logo')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\ColeBundle\Entity\Centro'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cole_colebundle_centro';
    }
}
