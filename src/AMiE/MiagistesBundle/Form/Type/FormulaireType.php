<?php

namespace AMiE\MiagistesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', 'text', array('max_length' => 255, 'required' => true))
            ->add('prenom', 'text', array('max_length' => 255, 'required' => true))
            ->add('dateNaissance', 'birthday', array('required' => true))
            ->add('rue', 'text', array('max_length' => 255, 'required' => true))
            ->add('codePostal', 'text', array('max_length' => 10, 'required' => true))
            ->add('ville', 'text', array('max_length' => 60, 'required' => true))
            ->add('numero', 'number', array('max_length' => 15, 'required' => true))
            ->add('mail', 'email', array('max_length' => 120, 'required' => true,))
            ->add('entAccueil', 'text', array('max_length' => 60, 'required' => true))
            ->add('anneePromo', 'choice', array('choices' => array_combine(\range(date('Y') - 10, date('Y') + 10), \range(date('Y') - 10, date('Y') + 10)), 'required' => true))
            ->add('embauche', 'choice', array('choices' => array('Oui' => 'Oui', 'Non' => 'Non'), 'required' => true))
            ->add('entActuelle', 'choice', array('choices' => array('Oui' => 'Oui', 'Non' => 'Non'), 'required' => false))
            ->add('raisonNonEmbauche', 'choice', array('choices' => array('Pas de programme d\'embauche dans mon entreprise d\'accueil' => 'Pas de programme d\'embauche dans mon entreprise d\'accueil', 'Pas de proposition pour moi dans mon entreprise d\'accueil' => 'Pas de proposition pour moi dans mon entreprise d\'accueil', 'Refus de ma part d\'une proposition de mon entreprise d\'accueil' => 'Refus de ma part d\'une proposition de mon entreprise d\'accueil', 'Autre(s) proposition(s) plus attractive(s) sur le plan financier' => 'Autre(s) proposition(s) plus attractive(s) sur le plan financier', 'Autre(s) proposition(s) plus attractive(s) sur le plan technique' => 'Autre(s) proposition(s) plus attractive(s) sur le plan technique', 'Autre(s) proposition(s) plus attractive(s) en terme de responsabilités' => 'Autre(s) proposition(s) plus attractive(s) en terme de responsabilités', 'Autres raisons' => 'Autres raisons'), 'required' => false))
            ->add('raisonAutre', 'textarea', array('required' => false))
            ->add('entrepriseAutre', 'text', array('max_length' => 45, 'required' => false))
            ->add('typeContrat', 'choice', array('choices' => array('CDI' => 'CDI', 'CDD' => 'CDD', 'VIE' => 'VIE', 'Autre' => 'Autre'), 'required' => false))
            ->add('typeContratAutre', 'text', array('max_length' => 65, 'required' => false))
            ->add('situationNonEmbauche', 'choice', array('choices' => array('Mon contrat de travail est en cours de finalisation' => 'Mon contrat de travail est en cours de finalisation', 'Je suis en recherche d\'emploi' => 'Je suis en recherche d\'emploi', 'Je suis à la recherche d\'un VIE' => 'Je suis à la recherche d\'un VIE', 'Je fais une poursuite d\'études classique' => 'Je fais une poursuite d\'études classique', 'Je fais une poursuite d\'études par apprentissage' => 'Je fais une poursuite d\'études par apprentissage', 'J\'ai d\'autres objectifs' => 'J\'ai d\'autres objectifs', 'Autre situation' => 'Autre situation'), 'required' => false))
            ->add('situationAutre', 'textarea', array('required' => false))
            ->add('niveauSalaire', 'text', array('max_length' => 11, 'required' => false))
            ->add('secteur', 'choice', array('choices' => array('Assurance' => 'Assurance', 'Distribution' => 'Distribution', 'Services Informatiques' => 'Services informatiques', 'Secteur public/administration' => 'Secteur public/administration', 'Banque' => 'Banque', 'Transports' => 'Transports', 'Industrie' => 'Industrie', 'Autre' => 'Autre'), 'required' => false))
            ->add('sectAutre', 'text', array('max_length' => 120, 'required' => false))
            ->add('metier', 'choice', array('choices' => array('Ingénieur réseau (Telecom) ou architecte réseau/télécom' => 'Ingénieur réseau (Telecom) ou architecte réseau/télécom', 'Consultant en progiciels (ou architecte de système applicatif)' => 'Consultant en progiciels (ou architecte de système applicatif)', 'Consultant/expert dans une SSII' => 'Consultant/expert dans une SSII', 'Chef de projet en entreprise' => 'Chef de projet en entreprise', 'Ingénieur d\'études' => 'Ingénieur d\'études', 'Administrateur de bases de données' => 'Administrateur de bases de données', 'Ingénieur commercial' => 'Ingénieur commercial', 'Chef de projet dans une SSII' => 'Chef de projet dans une SSII', 'Ingénieur de développement' => 'Ingénieur de développement', 'Ingénieur système' => 'Ingénieur système', 'Architecte en système d\'informations' => 'Architecte en système d\'informations', 'Ingénieur technico-commercial' => 'Ingénieur technico-commercial', 'Autre' => 'Autre'), 'required' => false))
            ->add('metAutre', 'text', array('max_length' => 120, 'required' => false));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\MiagistesBundle\Entity\Formulaire'
        ));
    }

    public function getName()
    {
        return 'formulaire';
    }
}