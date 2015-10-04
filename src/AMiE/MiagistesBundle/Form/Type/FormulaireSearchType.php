<?php

namespace AMiE\MiagistesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormulaireSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('max_length' => 255, 'required' => false))
            ->add('prenom', 'text', array('max_length' => 255, 'required' => false))
            ->add('embauche', 'choice', array('choices' => array('Oui' => 'Oui', 'Non' => 'Non'), 'required' => false))
            ->add('typeContrat', 'choice', array('choices' => array('CDI' => 'CDI', 'CDD' => 'CDD', 'VIE' => 'VIE', 'Autre' => 'Autre'), 'required' => false))
            ->add('entAccueil', 'text', array('max_length' => 60, 'required' => false))
            ->add('niveauSalaireMin', 'text', array('max_length' => 11, 'required' => false))
            ->add('niveauSalaireMax', 'text', array('max_length' => 11, 'required' => false))
            ->add('secteur', 'choice', array('choices' => array('Assurance' => 'Assurance', 'Distribution' => 'Distribution', 'Services Informatiques' => 'Services informatiques', 'Secteur public/administration' => 'Secteur public/administration', 'Banque' => 'Banque', 'Transports' => 'Transports', 'Industrie' => 'Industrie', 'Autre' => 'Autre'), 'required' => false))
            ->add('metier', 'choice', array('choices' => array('Ingénieur réseau (Telecom) ou architecte réseau/télécom' => 'Ingénieur réseau (Telecom) ou architecte réseau/télécom', 'Consultant en progiciels (ou architecte de système applicatif)' => 'Consultant en progiciels (ou architecte de système applicatif)', 'Consultant/expert dans une SSII' => 'Consultant/expert dans une SSII', 'Chef de projet en entreprise' => 'Chef de projet en entreprise', 'Ingénieur d\'études' => 'Ingénieur d\'études', 'Administrateur de bases de données' => 'Administrateur de bases de données', 'Ingénieur commercial' => 'Ingénieur commercial', 'Chef de projet dans une SSII' => 'Chef de projet dans une SSII', 'Ingénieur de développement' => 'Ingénieur de développement', 'Ingénieur système' => 'Ingénieur système', 'Architecte en système d\'informations' => 'Architecte en système d\'informations', 'Ingénieur technico-commercial' => 'Ingénieur technico-commercial', 'Autre' => 'Autre'), 'required' => false));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\MiagistesBundle\Entity\FormulaireSearch'
        ));
    }

    public function getName()
    {
        return 'formulaireSearch';
    }
}