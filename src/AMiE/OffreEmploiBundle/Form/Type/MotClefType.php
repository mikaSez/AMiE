<?php
namespace AMiE\OffreEmploiBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MotClefType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle', 'text', array('max_length' => 40, 'required' => true, 'label' => 'Libellé * ', 'trim' => true))
            ->add('descriptif', 'textarea', array('label' => 'Descriptif ', 'trim' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\OffreEmploiBundle\Entity\MotClef'
        ));
    }

    public function getName()
    {
        return 'motclef';
    }
}