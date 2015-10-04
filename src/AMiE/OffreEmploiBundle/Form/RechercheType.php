<?php
namespace AMiE\OffreEmploiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RechercheType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', 'text', array('required' => false, 'label' => 'Titre'))
            ->add('entreprise', 'text', array('required' => false, 'label' => 'Entreprise'))
            ->add('date', 'text', array('required' => false, 'label' => 'Date'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    /*
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\OffreEmploiBundle\Entity\Recherche'
        ));
    }*/

    /**
     * @return string
     */
    public function getName()
    {
        return 'recherche';
    }
}
