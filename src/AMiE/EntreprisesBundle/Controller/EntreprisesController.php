<?php
namespace AMiE\EntreprisesBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\HomeBundle\Entity\Notification;
use AMiE\UserBundle\Entity\UserSearch;
use AMiE\UserBundle\Form\Type\UserSearchType;
use AMiE\UserBundle\Entity\User;
use AMiE\UserBundle\Entity\UserRepository;
use Ob\HighchartsBundle\Highcharts\Highchart;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use AMiE\EntreprisesBundle\Entity\Partenaire;
use AMiE\EntreprisesBundle\Entity\PartenaireRepository;
use AMiE\EntreprisesBundle\Form\Type\PartenaireType;

class EntreprisesController extends CoreController
{
    public function rechercheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $recherche = new UserSearch();
        $formRech = $this->createForm(new UserSearchType(), $recherche);
        $requete = $this->get('request');
		$query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchAllEntreprises();
        $entreprises = $query->getResult();

   /*     if ($requete->getMethod() == 'POST') {
            $formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->search($recherche);
            $entreprises = $query->getResult();

            return $this->render('AMiEEntreprisesBundle:Entreprises:recherche.html.twig', array(
                'layout' => $layout,
                'entreprises' => $entreprises,
                'formRech' => $formRech->createView()
            ));
        } */
		if ($this->getRequest()->request->get('submitAction') == 'rechercher')
		{
            $formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->search($recherche);
            $entreprises = $query->getResult();
		}
		if ($this->getRequest()->request->get('submitAction') == 'exporter')
		{
            $formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->search($recherche);
            $entreprises = $query->getResult();
			$response = $this->exportexcel($entreprises);
			return $response;
		}	



        return $this->render('AMiEEntreprisesBundle:Entreprises:recherche.html.twig', array(
            'layout' => $layout,
            'entreprises' => $entreprises,
            'formRech' => $formRech->createView()
        ));
    }

    public function graphiquesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
		$query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchAllEntreprises();
        $entreprises = $query->getResult();

		if(!empty($entreprises))
		{
			$graphSecteur = $this->graphiqueSecteur();
			$graphNbOffre = $this->graphiqueNbOffre();

			return $this->render('AMiEEntreprisesBundle:Entreprises:graphiques.html.twig', array(
				'layout' => $layout,
				'graphSecteur' => $graphSecteur,
				'graphNbOffre' => $graphNbOffre,
				'entreprises' => $entreprises
				));
		}
		else
		{
			return $this->render('AMiEEntreprisesBundle:Entreprises:graphiques.html.twig', array(
				'layout' => $layout,
				'entreprises' => $entreprises
				));
		}
    }

    public function ficheAction(User $entreprise)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $queryOffre = $this->getDoctrine()->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->searchOffre($entreprise);
        $offres = $queryOffre->getResult();

        return $this->render('AMiEEntreprisesBundle:Entreprises:fiche.html.twig', array(
            'layout' => $layout,
            'entreprise' => $entreprise,
            'offres' => $offres
        ));
    }

    public function supprimerentrepriseAction(User $entreprise)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $em->remove($entreprise);

        $em->flush();
        return $this->redirect($this->generateUrl('amie_entreprises_recherche'));
    }

    public function graphiqueSecteur()
    {
        $queryAssurance = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Assurance');
        $queryDistribution = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Distribution');
        $querySi = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Services Informatiques');
        $queryPublic = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Secteur public/administration');
        $queryBanque = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Banque');
        $queryTransport = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Transports');
        $queryIndustrie = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Industrie');
        $queryAutre = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchSecteur('Autre');

        $secteurAssurance = (int)$queryAssurance->getSingleScalarResult();
        $secteurDistribution = (int)$queryDistribution->getSingleScalarResult();
        $secteurSi = (int)$querySi->getSingleScalarResult();
        $secteurPublic = (int)$queryPublic->getSingleScalarResult();
        $secteurBanque = (int)$queryBanque->getSingleScalarResult();
        $secteurTransport = (int)$queryTransport->getSingleScalarResult();
        $secteurIndustrie = (int)$queryIndustrie->getSingleScalarResult();
        $secteurAutre = (int)$queryAutre->getSingleScalarResult();

        $graph = new Highchart();
        $graph->chart->renderTo('piechartSecteur');
        $graph->title->text('Secteur d\'activités');
        $graph->plotOptions->pie(array(
            'allowPointSelect' => true,
            'cursor' => 'pointer',
            'dataLabels' => array('enabled' => true),
            'showInLegend' => false
        ));

        $data = array(
            array('Assurance', $secteurAssurance),
            array('Distribution', $secteurDistribution),
            array('Services Informatiques', $secteurSi),
            array('Secteur public/administration', $secteurPublic),
            array('Banque', $secteurBanque),
            array('Transports', $secteurTransport),
            array('Industrie', $secteurIndustrie),
            array('Autre', $secteurAutre)
        );


        $graph->series(array(array('type' => 'pie', 'name' => 'Nombre', 'data' => $data)));

        return $graph;
    }

    public function graphiqueNbOffre()
    {
        $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchNomEntreprises();
        $ent = $query->getResult();

        $entreprises = array();
        for ($i = 0; $i < sizeof($ent); $i++) {
            $entreprises[] = $ent[$i]['nom'];
        }

        $offres[0]['name'] = 'Nombre d\'offres d\'emploi postées';
        $offres[0]['data'] = array();


        for ($j = 0; $j < sizeof($ent); $j++)
        {
            $queryNb = $this->getDoctrine()->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->searchNbOffre($ent[$j]['nom']);
            $nb = (int)$queryNb->getSingleScalarResult();
            $offres[0]['data'][$j] = $nb;
        }

        $graph = new Highchart();
        $graph->chart->renderTo('barchartNbOffre');
        $graph->title->text('Nombre d\'offres d\'emploi par entreprise (inscrite)');
        $graph->chart->type('column');

        $graph->yAxis->title(array('text' => "Nombre d'offres"));

        $graph->xAxis->title(array('text' => "Entreprises"));
        $graph->xAxis->categories($entreprises);

        $graph->series($offres);

        return $graph;
    }
	
	public function exportexcel($entreprises)
	{
	   $user = $this->get('security.context')->getToken()->getUser();
	// ask the service for a Excel5
       $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

       $phpExcelObject->getProperties()->setCreator($user->getUsername())
           ->setLastModifiedBy($user->getUsername())
           ->setTitle("export_entreprises")
    //       ->setSubject("Office 2005 XLSX Test Document")
    //      ->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
    //       ->setKeywords("office 2005 openxml php")
    //       ->setCategory("Test result file");
	;
		$column = 'A';
		$fields = array('Nom', 'Siret', 'Email', 'Numero', 'Rue', 'CodePostal', 'Ville', 'Metier', 'MetAutre', 'Secteur', 'SectAutre');
		$columnName = array('Nom', 'Siret', 'Numéro', 'Mail', 'Rue', 'Code Postal', 'Ville', 'Métier', 'Autre métier', 'Secteur', 'Autre secteur');
	    
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
			for ($i = 0; $i < sizeof($entreprises); $i++) {
				$get = 'get'.$fields[$j];
				$phpExcelObject->setActiveSheetIndex(0)
					->setCellValue($column.'1', $columnName[$j])
					->setCellValue($column.$row, $entreprises[$i]->$get())
					->getStyle($column.'1')->applyFromArray($styleTitre)
					;
				$row++;
			} 
			$column++;
		}
       $phpExcelObject->getActiveSheet()->setTitle('Entreprises');
		
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
            'export_entreprises_'.$datetime.'.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
	}
	
	public function supprimerpartenaireAction(Partenaire $part)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($part);

        $em->flush();

		$referer = $this->getRequest()->headers->get('referer');
		return $this->redirect($referer);
    }
	
}

?>