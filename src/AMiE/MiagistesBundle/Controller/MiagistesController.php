<?php
namespace AMiE\MiagistesBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\MiagistesBundle\Entity\Formulaire;
use AMiE\MiagistesBundle\Entity\FormulaireSearch;
use AMiE\MiagistesBundle\Form\Type\FormulaireSearchType;
use AMiE\MiagistesBundle\Form\Type\FormulaireType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use AMiE\HomeBundle\Entity\Notification;


class MiagistesController extends CoreController
{
    public function rechercheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $recherche = new FormulaireSearch();
        $formRech = $this->createForm(new FormulaireSearchType(), $recherche);
        $requete = $this->get('request');

        if ($requete->getMethod() == 'POST') {
            $formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->search($recherche);
            $results = $query->getResult();

            return $this->render('AMiEMiagistesBundle:Miagistes:recherche.html.twig', array(
                'layout' => $layout,
                'miagistes' => $results,
                'formRech' => $formRech->createView()
            ));
        }
        $miagistes = $em->getRepository('AMiEMiagistesBundle:Formulaire')->findAll();
        return $this->render('AMiEMiagistesBundle:Miagistes:recherche.html.twig', array(
            'layout' => $layout,
            'miagistes' => $miagistes,
            'formRech' => $formRech->createView()
        ));
    }

    public function graphiquesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        //	$miagistes = $em->getRepository('AMiEMiagistesBundle:Formulaire')->findAll();
        $graphContrat = $this->graphiqueTypeContrat();
        $graphEmbauche = $this->graphiqueEmbauche();
        $graphEntAccEmb = $this->graphiqueEntrepriseAccEmbauche();
        $graphSalaire = $this->graphiqueSalaire();


        return $this->render('AMiEMiagistesBundle:Miagistes:graphiques.html.twig', array(
            'layout' => $layout,
            'graphContrat' => $graphContrat,
            'graphEmbauche' => $graphEmbauche,
            'graphEntAccEmb' => $graphEntAccEmb,
            'graphSalaire' => $graphSalaire
        ));
    }

    public function ficheAction(Formulaire $formulaire)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $notification = $em->getRepository('AMiEHomeBundle:Notification')->findOneBy(array('idFormulaire' => $formulaire->getId(), 'vue' => 0));
        if(!empty($notification)) {
            $notification->setVue(1);
            $em->persist($notification);
            $em->flush();
        }

        return $this->render('AMiEMiagistesBundle:Miagistes:fiche.html.twig', array(
            'layout' => $layout,
            'formulaire' => $formulaire
        ));
    }

    public function formulaireAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $formulaire = new Formulaire();
        $formMiag = $this->createForm(new FormulaireType(), $formulaire);
        $requete = $this->get('request');

        if ($requete->getMethod() == 'POST') {

            $formMiag->handleRequest($requete);
              if($formMiag->isValid()){
            $formulaire = $formMiag->getData();
            $em->persist($formulaire);
            $em->flush();

            $notification = new Notification();
            $notification->setAction('Ajout d\'un formulaire')
                ->setDescriptif('Le formulaire de '.$formulaire->getPrenom().' '.$formulaire->getNom().' vient d\'être rempli');
            $em->persist($notification);
            $em->flush();

            return $this->render('AMiEMiagistesBundle:Miagistes:formulaire_confirmed.html.twig', array(
                'layout' => $layout,
            ));
             }
        }
        return $this->render('AMiEMiagistesBundle:Miagistes:formulaire.html.twig', array(
            'layout' => $layout,
            'formMiag' => $formMiag->createView(),
        ));
    }

    public function supprimerformulaireAction(Formulaire $formulaire)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $em->remove($formulaire);

        $em->flush();

        return $this->redirect($this->generateUrl('amie_miagistes_recherche'));


    }

    public function modifierformulaireAction(Formulaire $f)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $formMiagModif = $this->createForm(new FormulaireType(), $f);

        $requete = $this->get('request');

        if ($requete->getMethod() == 'POST') {
            $formMiagModif->handleRequest($requete);
              if($formMiagModif->isValid()){
            $f = $formMiagModif->getData();
            $em->persist($f);
            $em->flush();

            $notification = new Notification();
            $notification->setAction('Modification d\'un formulaire')
                ->setDescriptif('Le formulaire de '.$f->getPrenom().' '.$f->getNom().' vient d\'être modifié');
            $em->persist($notification);
            $em->flush();

            return $this->render('AMiEMiagistesBundle:Miagistes:formulaire_confirmed.html.twig', array(
                'layout' => $layout,
            ));
             }
        }

        return $this->render('AMiEMiagistesBundle:Miagistes:modifierformulaire.html.twig', array(
            'layout' => $layout,
            'id' => $f->getId(),
            'formMiagModif' => $formMiagModif->createView(),
        ));
    }

    public function graphiqueTypeContrat()
    {
        $queryCdi = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchTypeContrat('CDI');
        $queryCdd = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchTypeContrat('CDD');
        $queryVie = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchTypeContrat('VIE');
        $queryAutre = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchTypeContrat('Autre');
        $cdi = (int)$queryCdi->getSingleScalarResult(); // retourne un seul résultat en string qu'on caste en int
        $cdd = (int)$queryCdd->getSingleScalarResult();
        $vie = (int)$queryVie->getSingleScalarResult();
        $autre = (int)$queryAutre->getSingleScalarResult();

        $graph = new Highchart();
        $graph->chart->renderTo('piechartContrat');
        $graph->title->text('Type de contrat à l\'embauche');
        $graph->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => true),
            'showInLegend' => false
        ));

        $data = array(
            array('CDI', $cdi),
            array('CDD', $cdd),
            array('VIE', $vie),
            array('Autre', $autre)
        );

        $graph->series(array(array('type' => 'pie', 'name' => 'Nombre', 'data' => $data)));

        return $graph;
    }

    public function graphiqueEmbauche()
    {
        $queryEmbauche = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchEmbauche('Oui');
        $queryNonEmbauche = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchEmbauche('Non');
        $oui = (int)$queryEmbauche->getSingleScalarResult(); // retourne un seul résultat en string qu'on caste en int
        $non = (int)$queryNonEmbauche->getSingleScalarResult();

        $graph = new Highchart();
        $graph->chart->renderTo('piechartEmbauche');
        $graph->title->text('Embauché ou non ?');
        $graph->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => true),
            'showInLegend' => false
        ));

        $data = array(
            array('Oui', $oui),
            array('Non', $non)
        );

        $graph->series(array(array('type' => 'pie', 'name' => 'Nombre', 'data' => $data)));

        return $graph;
    }

    public function graphiqueEntrepriseAccEmbauche()
    {
        $queryEntAccueil = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchAllEntAccueil();
        $entAccueil = $queryEntAccueil->getResult();


        // On parcoure le tableau de toutes les entreprises d'accueil pour l'ajouter à la liste pour le graphique
        // Calcul de la taille du tableau
        $tailleEnt = sizeof($entAccueil);
        for ($i = 0; $i < $tailleEnt; $i++) {
            $entreprises[] = $entAccueil[$i]['entAccueil'];
        }

        $embauche[0]['name'] = 'Nombre d\'embauchés';
        $embauche[1]['name'] = 'Nombre de non embauchés';
        $embauche[0]['data'] = array();
        $embauche[1]['data'] = array();
        $tailleEmb = sizeof($embauche);


        for ($j = 0; $j < $tailleEnt; $j++) {
            $queryEntOui = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchEntrepriseAccueil($entreprises[$j], 'Oui');
            $queryEntNon = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchEntrepriseAccueil($entreprises[$j], 'Non');
            $oui = (int)$queryEntOui->getSingleScalarResult();
            $non = (int)$queryEntNon->getSingleScalarResult();
            $embauche[0]['data'][$j] = $oui;
            $embauche[1]['data'][$j] = $non;
        }

        $graph = new Highchart();
        $graph->chart->renderTo('barchartEntAccEmb');
        $graph->title->text('Les entreprises qui embauchent leurs apprentis');
        $graph->chart->type('column');

        $graph->yAxis->title(array('text' => "Nombre de miagistes"));

        $graph->xAxis->title(array('text' => "Entreprises"));
        $graph->xAxis->categories($entreprises);

        $graph->series($embauche);

        return $graph;
    }

    public function graphiqueSalaire()
    {
        for ($an = date('Y') - 10; $an <= date('Y'); $an++) {
            $annees[] = $an;
            $querySalaire = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchSalaire($an);
            $moyenne[] = (int)$querySalaire->getSingleScalarResult(); // retourne un seul résultat en string qu'on caste en int
        }


        $graph = new Highchart();
        $graph->chart->renderTo('splineSalaire');
        $graph->title->text('Evolution du salaire brut annuel moyen prévu par années');
        $graph->chart->type('spline');

        $series[0]['name'] = 'Salaire moyen';
        $series[0]['type'] = 'spline';
        for ($i = 0; $i < sizeof($moyenne); $i++) {
            $series[0]['data'][] = $moyenne[$i];
        }

        $yData = array(
            array(
                'title' => array(
                    'text' => "Salaire moyen (brut)"
                ),
            ),
        );

        $graph->yAxis($yData);

        $graph->xAxis->title(array('text' => "Années"));
        $graph->xAxis->categories($annees);

        $graph->series($series);

        return $graph;
    }

}

?>