<?php

namespace AMiE\OffreEmploiBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OffreEmploiType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $departements = self::getDepartements();
        $builder
            ->add('titre', 'text', array('label' => 'Titre *'))
            ->add('descriptif', 'textarea', array('label' => 'Descriptif de l\'offre *'))
            ->add('lieuTravail', 'text', array('label' => 'Adresse du lieu de travail *'))
            ->add('departement', 'choice', array('choices' => array($departements), 'label' => 'Département *'))
            ->add('typePoste', 'choice', array('choices' =>  array(
                'Temps plein' => 'Temps plein',
                'Temps partiel' => 'Temps partiel'
            ), 'label' => 'Type de poste *'))
            ->add('typeContrat', 'choice', array('choices' =>  array(
                'CDI' => 'CDI',
                'CDD' => 'CDD',
                'VIE' => 'VIE',
                'Stage' => 'Stage',
                'Apprentissage' => 'Apprentissage'
            ), 'label' => 'Type de contrat *'))
            ->add('xpExigee', 'text', array('label' => 'Expérience exigée *'))
            ->add('dureeValidite', 'text', array('label' => 'Pendant combien de temps cette offre est valide *'))
            ->add('urgence', 'choice', array('choices' =>  array(
                'Peu pressé (6 mois ou +)' => 'Peu pressé (6 mois ou +)',
                'Assez pressé (3 mois)' => 'Assez pressé (3 mois)',
                'Pressé (entre 1 et 3 mois)' => 'Pressé (entre 1 et 3 mois)',
                'Très pressé (- de 1 mois)' => 'Très pressé (- de 1 mois)',
                'Immédiat (dès que possible)' => 'Immédiat (dès que possible)'
            ), 'label' => 'Niveau d\'urgence de l\'offre *'))
            ->add('salaire', 'number', array('required' => false, 'label' => 'Salaire estimé par an', 'pattern' => '^[1-9][0-9]+$'))
            ->add('contactNom', 'text', array('label' => 'Nom de la personne à contacter *'))
            ->add('contactMail', 'email', array('label' => 'Mail de la personne à contacter *'))
            ->add('contactTel', 'number', array('required' => false, 'label' => 'Téléphone de la personne à contacter', 'pattern' => '^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$'));
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AMiE\OffreEmploiBundle\Entity\OffreEmploi'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'offreemploi';
    }

    public function getDepartements(){
        $depts['01'] = '01 - Ain';
        $depts['02'] = '02 - Aisne';
        $depts['03'] = '03 - Allier';
        $depts['04'] = '04 - Alpes de Haute Provence';
        $depts['05'] = '05 - Hautes Alpes';
        $depts['06'] = '06 - Alpes Maritimes';
        $depts['07'] = '07 - Ardèche';
        $depts['08'] = '08 - Ardennes';
        $depts['09'] = '09 - Ariège';
        $depts['10'] = '10 - Aube';
        $depts['11'] = '11 - Aude';
        $depts['12'] = '12 - Aveyron';
        $depts['13'] = '13 - Bouches du Rhône';
        $depts['14'] = '14 - Calvados';
        $depts['15'] = '15 - Cantal';
        $depts['16'] = '16 - Charente';
        $depts['17'] = '17 - Charente Maritime';
        $depts['18'] = '18 - Cher';
        $depts['19'] = '19 - Corrèze';
        $depts['2A'] = '2A - Corse du Sud';
        $depts['2B'] = '2B - Haute Corse';
        $depts['21'] = '21 - Côte d\'Or';
        $depts['22'] = '22 - Côtes d\'Armor';
        $depts['23'] = '23 - Creuse';
        $depts['24'] = '24 - Dordogne';
        $depts['25'] = '25 - Doubs';
        $depts['26'] = '26 - Drôme';
        $depts['27'] = '27 - Eure';
        $depts['28'] = '28 - Eure et Loir';
        $depts['29'] = '29 - Finistère';
        $depts['30'] = '30 - Gard';
        $depts['31'] = '31 - Haute Garonne';
        $depts['32'] = '32 - Gers';
        $depts['33'] = '33 - Gironde';
        $depts['34'] = '34 - Hérault';
        $depts['35'] = '35 - Ille et Vilaine';
        $depts['36'] = '36 - Indre';
        $depts['37'] = '37 - Indre et Loire';
        $depts['38'] = '38 - Isère';
        $depts['39'] = '39 - Jura';
        $depts['40'] = '40 - Landes';
        $depts['41'] = '41 - Loir et Cher';
        $depts['42'] = '42 - Loire';
        $depts['43'] = '43 - Haute Loire';
        $depts['44'] = '44 - Loire Atlantique';
        $depts['45'] = '45 - Loiret';
        $depts['46'] = '46 - Lot';
        $depts['47'] = '47 - Lot et Garonne';
        $depts['48'] = '48 - Lozère';
        $depts['49'] = '49 - Maine et Loire';
        $depts['50'] = '50 - Manche';
        $depts['51'] = '51 - Marne';
        $depts['52'] = '52 - Haute Marne';
        $depts['53'] = '53 - Mayenne';
        $depts['54'] = '54 - Meurthe et Moselle';
        $depts['55'] = '55 - Meuse';
        $depts['56'] = '56 - Morbihan';
        $depts['57'] = '57 - Moselle';
        $depts['58'] = '58 - Nièvre';
        $depts['59'] = '59 - Nord';
        $depts['60'] = '60 - Oise';
        $depts['61'] = '61 - Orne';
        $depts['62'] = '62 - Pas de Calais';
        $depts['63'] = '63 - Puy de Dôme';
        $depts['64'] = '64 - Pyrénées Atlantiques';
        $depts['65'] = '65 - Hautes Pyrénées';
        $depts['66'] = '66 - Pyrénées Orientales';
        $depts['67'] = '67 - Bas Rhin';
        $depts['68'] = '68 - Haut Rhin';
        $depts['69'] = '69 - Rhône';
        $depts['70'] = '70 - Haute Saône';
        $depts['71'] = '71 - Saône et Loire';
        $depts['72'] = '72 - Sarthe';
        $depts['73'] = '73 - Savoie';
        $depts['74'] = '74 - Haute Savoie';
        $depts['75'] = '75 - Paris';
        $depts['76'] = '76 - Seine Maritime';
        $depts['77'] = '77 - Seine et Marne';
        $depts['78'] = '78 - Yvelines';
        $depts['79'] = '79 - Deux Sèvres';
        $depts['80'] = '80 - Somme';
        $depts['81'] = '81 - Tarn';
        $depts['82'] = '82 - Tarn et Garonne';
        $depts['83'] = '83 - Var';
        $depts['84'] = '84 - Vaucluse';
        $depts['85'] = '85 - Vendée';
        $depts['86'] = '86 - Vienne';
        $depts['87'] = '87 - Haute Vienne';
        $depts['88'] = '88 - Vosges';
        $depts['89'] = '89 - Yonne';
        $depts['90'] = '90 - Territoire de Belfort';
        $depts['91'] = '91 - Essonne';
        $depts['92'] = '92 - Hauts de Seine';
        $depts['93'] = '93 - Seine St Denis';
        $depts['94'] = '94 - Val de Marne';
        $depts['95'] = '95 - Val d\'Oise';
        $depts['97'] = '97 - DOM';
        return $depts;
    }
}
