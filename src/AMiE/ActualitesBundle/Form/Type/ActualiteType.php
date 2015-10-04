<?php
namespace AMiE\ActualitesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActualiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text', array('max_length' => 40, 'required' => true, 'trim' => true))
            ->add('contenu', 'textarea', array('required' => true, 'trim' => true));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\ActualitesBundle\Entity\Actualite'
        ));
    }

    public function getName()
    {
        return 'actualite';
    }
}