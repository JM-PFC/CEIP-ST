<?php

namespace Cole\BackendBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class AlumnoIntranetType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telefono','text', array('label' => 'Teléfono', 'max_length' => 12, 'attr' => array('class' => 'telefono', 'lengthmin'=> 9, 'validation' => 'Length,Telefono')))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cole\BackendBundle\Entity\Alumno',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        //return 'cole_backendbundle_alumno';
        return 'alumno';
    }
}