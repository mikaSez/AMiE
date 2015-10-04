<?php
namespace AMiE\OffreEmploiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentJointType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text', array('required' => false, 'label' => 'Titre du document'))
            ->add('descriptif', 'text', array('required' => false, 'label' => 'Descriptif du document'))
            ->add('file', 'file', array('required' => false, 'label' => 'Fichier de présentation de l\'offre'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\OffreEmploiBundle\Entity\DocumentJoint'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'documentjoint';
    }
}
