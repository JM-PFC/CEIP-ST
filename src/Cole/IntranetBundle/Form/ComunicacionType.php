<?php

namespace Cole\IntranetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ComunicacionType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emisor')
            ->add('receptor')
            ->add('asunto')
            ->add('mensaje')
            ->add('leido')
            ->add('fichero', 'file', array('data_class' => null,'required' => false, 'attr' => array( 'class' => 'archivo','size' => 50, 'mimeTypes' => '.png,.jpg,.jpeg,.pdf' ,'validation' => 'MimeTypes, MaxSize')))
            ->add('tipoEmisor')
            ->add('tipoReceptor')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\IntranetBundle\Entity\Comunicacion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'comunicacion';
    }
}
