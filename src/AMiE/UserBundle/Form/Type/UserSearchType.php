<?php
/**
 * Created by PhpStorm.
 * User: Alison
 * Date: 19/06/2015
 * Time: 23:10
 */

namespace AMiE\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('max_length' => 255, 'required' => false))
            ->add('siret', 'text', array('max_length' => 14, 'required' => false))
            ->add('ville', 'text', array('max_length' => 120, 'required' => false))
            ->add('secteur', 'choice', array('choices' => array('' => '', 'Assurance' => 'Assurance', 'Distribution' => 'Distribution', 'Services Informatiques' => 'Services informatiques', 'Secteur public, administration' => 'Secteur public, administration', 'Banque' => 'Banque', 'Transports' => 'Transports', 'Industrie' => 'Industrie', 'Autres' => 'Autre'), 'required' => false, 'multiple' => true))
            ;
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\UserBundle\Entity\UserSearch'
        ));
    }

    public function getName()
    {
        return 'userSearch';
    }
}