<?php
namespace AMiE\ActualitesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface; 
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text', array('required' => false, 'label' => 'Titre de l\'image'))
            ->add('file', 'file', array('required' => false, 'label' => 'Image à associer à l\'actualité'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\ActualitesBundle\Entity\Image'
        ));
    }

    public function getName()
    {
        return 'image';
    }
}