<?php

namespace AMiE\EntreprisesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('max_length' => 255, 'required' => true))
            ->add('url', 'text', array('max_length' => 255, 'required' => true));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\EntreprisesBundle\Entity\Partenaire'
        ));
    }

    public function getName()
    {
        return 'partenaire';
    }
}