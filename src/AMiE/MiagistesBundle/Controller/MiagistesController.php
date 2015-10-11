<?php
namespace AMiE\MiagistesBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\MiagistesBundle\Entity\Formulaire;
use AMiE\MiagistesBundle\Entity\FormulaireSearch;
use AMiE\MiagistesBundle\Form\Type\FormulaireSearchType;
use AMiE\MiagistesBundle\Form\Type\FormulaireType;
use Ob\HighchartsBundle\Highcharts\Highchart;
use AMiE\HomeBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Doctrine\ORM\Tools\Pagination\Paginator;


class MiagistesController extends CoreController
{
    public function rechercheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $recherche = new FormulaireSearch();
        $formRech = $this->createForm(new FormulaireSearchType(), $recherche);
        $requete = $this->get('request');
	//	$miagistes = $em->getRepository('AMiEMiagistesBundle:Formulaire')->findAll();
        $query = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->searchAll();
        $miagistes = $query->getResult();
		
		if ($this->getRequest()->request->get('submitAction') == 'rechercher')
		{
			$formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->search($recherche);
            $miagistes = $query->getResult();
		}
		if ($this->getRequest()->request->get('submitAction') == 'exporter')
		{
			$formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEMiagistesBundle:Formulaire')->search($recherche);
            $miagistes = $query->getResult();
			$response = $this->exportexcel($miagistes);
			return $response;
		}		
	
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
	
	public function modifiercoordonneesAction(Formulaire $f)
    {
        $em = $this->getDoctrine()->getManager();
		$layout = $this->getLayout($em);
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST'))
		{
            if(!empty($_POST['rue']))
			{
                $f->setRue(($_POST['rue']));
			}
			if(!empty($_POST['codePostal']))
			{
                $f->setCodePostal(($_POST['codePostal']));
			}
			if(!empty($_POST['ville']))
			{
                $f->setVille(($_POST['ville']));
			}
			if(!empty($_POST['numero']))
			{
                $f->setNumero(($_POST['numero']));
			}
			if(!empty($_POST['mail']))
			{
                $f->setMail(($_POST['mail']));
			}
			if(!empty($_POST['anneePromo']))
			{
                $f->setAnneePromo(($_POST['anneePromo']));
			}
                $em->persist($f);
                $em->flush();

                $notification = new Notification();
                $notification->setAction('Modification d\'un formulaire Miagiste')
                    ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié le formulaire de : '.$f->getPrenom().$f->getNom())
                    ->setIdUser($user->getId())
                    ->setIdOffre($f->getId());
                $em->persist($notification);
                $em->flush();
            
        }

        return $this->render('AMiEMiagistesBundle:Miagistes:fiche.html.twig', array(
            'layout' => $layout,
            'formulaire' => $f
        ));
    }

    public function modifierinsertionAction(Formulaire $f)
    {
        $em = $this->getDoctrine()->getManager();
		$layout = $this->getLayout($em);
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST'))
		{
            if(!empty($_POST['entAccueil']))
			{
                $f->setEntAccueil(($_POST['entAccueil']));
			}
			if(!empty($_POST['embauche']))
			{
                $f->setEmbauche(($_POST['embauche']));
			}
			if(!empty($_POST['typeContrat']))
			{
                $f->setTypeContrat(($_POST['typeContrat']));
			}
			if(!empty($_POST['typeContratAutre']))
			{
                $f->setTypeContratAutre(($_POST['typeContratAutre']));
			}
			if(!empty($_POST['entActuelle']))
			{
                $f->setEntActuelle(($_POST['entActuelle']));
			}
			if(!empty($_POST['raisonNonEmbauche']))
			{
                $f->setRaisonNonEmbauche(($_POST['raisonNonEmbauche']));
			}			
			if(!empty($_POST['raisonAutre']))
			{
                $f->setRaisonAutre(($_POST['raisonAutre']));
			}			
			if(!empty($_POST['entrepriseAutre']))
			{
                $f->setEntrepriseAutre(($_POST['entrepriseAutre']));
			}			
			if(!empty($_POST['niveauSalaire']))
			{
                $f->setNiveauSalaire(($_POST['niveauSalaire']));
			}			
			if(!empty($_POST['situationNonEmbauche']))
			{
                $f->setSituationNonEmbauche(($_POST['situationNonEmbauche']));
			}			
			if(!empty($_POST['situationAutre']))
			{
                $f->setSituationAutre(($_POST['situationAutre']));
			}			
                $em->persist($f);
                $em->flush();

                $notification = new Notification();
                $notification->setAction('Modification d\'un formulaire Miagiste')
                    ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié le formulaire de : '.$f->getPrenom().$f->getNom())
                    ->setIdUser($user->getId())
                    ->setIdOffre($f->getId());
                $em->persist($notification);
                $em->flush();
            
        }

        return $this->render('AMiEMiagistesBundle:Miagistes:fiche.html.twig', array(
            'layout' => $layout,
            'formulaire' => $f
        ));
    }

    public function modifierembaucheAction(Formulaire $f)
    {
        $em = $this->getDoctrine()->getManager();
		$layout = $this->getLayout($em);
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST'))
		{
            if(!empty($_POST['secteur']))
			{
                $f->setSecteur(($_POST['secteur']));
			}
			if(!empty($_POST['sectAutre']))
			{
                $f->setSectAutre(($_POST['sectAutre']));
			}
			if(!empty($_POST['metier']))
			{
                $f->setMetier(($_POST['metier']));
			}
			if(!empty($_POST['metAutre']))
			{
                $f->setMetAutre(($_POST['metAutre']));
			}
                $em->persist($f);
                $em->flush();

                $notification = new Notification();
                $notification->setAction('Modification d\'un formulaire Miagiste')
                    ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié le formulaire de : '.$f->getPrenom().$f->getNom())
                    ->setIdUser($user->getId())
                    ->setIdOffre($f->getId());
                $em->persist($notification);
                $em->flush();
            
        }

        return $this->render('AMiEMiagistesBundle:Miagistes:fiche.html.twig', array(
            'layout' => $layout,
            'formulaire' => $f
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
	
	public function exportexcel($miagistes)
	{
	   $user = $this->get('security.context')->getToken()->getUser();
	// ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

       $phpExcelObject->getProperties()->setCreator($user->getUsername())
           ->setLastModifiedBy($user->getUsername())
           ->setTitle("export_miagistes")
    //       ->setSubject("Office 2005 XLSX Test Document")
    //      ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
    //       ->setKeywords("office 2005 openxml php")
    //       ->setCategory("Test result file");
	;
		$column = 'A';
		$fields = array('Nom', 'Prenom', 'Mail', 'Rue', 'CodePostal', 'Ville', 'Numero', 'EntAccueil', 'AnneePromo', 'Embauche', 'EntActuelle', 'EntrepriseAutre',
						'TypeContrat', 'TypeContratAutre', 'NiveauSalaire', 'RaisonNonEmbauche', 'RaisonAutre', 'SituationNonEmbauche', 'SituationAutre',
						'Metier', 'MetAutre', 'Secteur', 'SectAutre');
		$columnName = array('Nom', 'Prénom', 'Mail', 'Rue', 'Code Postal', 'Ville', 'Numéro', 'Entreprise d\'accueil', 'Annee promotion M2', 'Embauché ou non ?', 'Par l\'entreprise d\'accueil ?', 'Par une autre entreprise', 'Type de contrat', 'Autre type de contrat', 'Niveau de salaire', 'Raisons de non embauche', 'Autres raisons de non embauche', 'Situation si non embauché', 'Autre situation si non embauché', 'Métier', 'Autre métier', 'Secteur', 'Autre secteur');
	    
		$styleTitre = array('font'=>array(
                                'bold'=>true,
                                'size'=>14,
                                'name'=>'Calibri',
                                'color'=>array('rgb'=>'000000')),
                  'alignment'=>array('horizontal'=>\PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		);
		
		$styleData = array('font'=>array(
                                'bold'=>true,
                                'size'=>11,
                                'name'=>'Calibri',
                                'color'=>array('rgb'=>'000000')),
                  'alignment'=>array('horizontal'=>\PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
		);
		
		for($j = 0; $j < sizeof($fields); $j++) {
			$row = 2;
			for ($i = 0; $i < sizeof($miagistes); $i++) {
				$get = 'get'.$fields[$j];
				$phpExcelObject->setActiveSheetIndex(0)
					->setCellValue($column.'1', $columnName[$j])
					->setCellValue($column.$row, $miagistes[$i]->$get())
					->getStyle($column.'1')->applyFromArray($styleTitre)
					;
				$row++;
			} 
			$column++;
		}
       $phpExcelObject->getActiveSheet()->setTitle('Miagistes');
		
		for($let = 'A'; $let < $column; $let++) { 
	   $phpExcelObject->getActiveSheet()->getColumnDimension($let)->setAutoSize(true);
	   }

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
		$datetime = gmdate('dmYHi');
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'export_miagistes_'.$datetime.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
	}
}

?>