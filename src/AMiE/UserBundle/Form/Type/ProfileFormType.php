<?php

namespace AMiE\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('nom', 'text', array('max_length' => 255, 'required' => false))
            ->add('prenom', 'text', array('max_length' => 255, 'required' => false))
            ->add('siret', 'text', array('max_length' => 14, 'required' => false))
            ->add('rue', 'text', array('max_length' => 255, 'required' => false))
            ->add('codePostal', 'text', array('max_length' => 10, 'required' => false))
            ->add('ville', 'text', array('max_length' => 60, 'required' => false))
            ->add('numero', 'text', array('max_length' => 15, 'required' => false))
            ->add('secteur', 'choice', array('choices' => array('Assurance' => 'Assurance', 'Distribution' => 'Distribution', 'Services Informatiques' => 'Services informatiques', 'Secteur public, administration' => 'Secteur public, administration', 'Banque' => 'Banque', 'Transports' => 'Transports', 'Industrie' => 'Industrie', 'Autres' => 'Autre'), 'required' => false))
            ->add('sectAutre', 'text', array('max_length' => 120, 'required' => false))
            ->add('metier', 'choice', array('choices' => array('Ingénieur réseau (Telecom) ou architecte réseau/télécom' => 'Ingénieur réseau (Telecom) ou architecte réseau/télécom', 'Consultant en progiciels (ou architecte de système applicatif)' => 'Consultant en progiciels (ou architecte de système applicatif)', 'Consultant/expert dans une SSII' => 'Consultant/expert dans une SSII', 'Chef de projet en entreprise' => 'Chef de projet en entreprise', 'Ingénieur d\'études' => 'Ingénieur d\'études', 'Administrateur de bases de données' => 'Administrateur de bases de données', 'Ingénieur commercial' => 'Ingénieur commercial', 'Chef de projet dans une SSII' => 'Chef de projet dans une SSII', 'Ingénieur de développement' => 'Ingénieur de développement', 'Ingénieur système' => 'Ingénieur système', 'Architecte en système d\'informations' => 'Architecte en système d\'informations', 'Ingénieur technico-commercial' => 'Ingénieur technico-commercial', 'Autre' => 'Autre'), 'required' => false))
            ->add('metAutre', 'text', array('max_length' => 120, 'required' => false));
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'acme_user_profile';
    }
}