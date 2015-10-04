<?php
namespace AMiE\EntreprisesBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\HomeBundle\Entity\Notification;
use AMiE\UserBundle\Entity\UserSearch;
use AMiE\UserBundle\Form\Type\UserSearchType;
use AMiE\UserBundle\Entity\User;
use AMiE\UserBundle\Entity\UserRepository;
use Ob\HighchartsBundle\Highcharts\Highchart;

class EntreprisesController extends CoreController
{
    public function rechercheAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $recherche = new UserSearch();
        $formRech = $this->createForm(new UserSearchType(), $recherche);
        $requete = $this->get('request');

        if ($requete->getMethod() == 'POST') {
            $formRech->bind($requete);
            $recherche = $formRech->getData();
            $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->search($recherche);
            $results = $query->getResult();

            return $this->render('AMiEEntreprisesBundle:Entreprises:recherche.html.twig', array(
                'layout' => $layout,
                'entreprises' => $results,
                'formRech' => $formRech->createView()
            ));
        }

        $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchAllEntreprises();
        $entreprises = $query->getResult();

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

        $graphSecteur = $this->graphiqueSecteur();
        $graphNbOffre = $this->graphiqueNbOffre();

        return $this->render('AMiEEntreprisesBundle:Entreprises:graphiques.html.twig', array(
            'layout' => $layout,
            'graphSecteur' => $graphSecteur,
            'graphNbOffre' => $graphNbOffre
        ));
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
}

?>