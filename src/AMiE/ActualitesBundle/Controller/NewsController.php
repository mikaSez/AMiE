<?php
namespace AMiE\ActualitesBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\ActualitesBundle\Entity\Actualite;
use AMiE\ActualitesBundle\Form\Type\ActualiteType;
use AMiE\ActualitesBundle\Entity\Image;
use AMiE\ActualitesBundle\Form\Type\ImageType;

use AMiE\HomeBundle\Entity\Notification;

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Exception\NotValidCurrentPageException;

class NewsController extends CoreController
{
    public function indexAction($actif, $page, $maxPerPage)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
		
		//$maxPerPage = 2;
        switch ($actif){
            case 'actif':
                $actualites = $em->getRepository('AMiEActualitesBundle:Actualite')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'));
				$pagerfanta = new Pagerfanta(new ArrayAdapter($actualites));
				$pagerfanta->setMaxPerPage($maxPerPage);
		
				try 
				{
					$pagerfanta->setCurrentPage($page);
				} 
				catch(NotValidCurrentPageException $e) 
				{
					throw new NotFoundHttpException();
				}
                break;
            case 'inactif':
                $actualites = $em->getRepository('AMiEActualitesBundle:Actualite')->findBy(array('actif' => 'F'), array('updatedDate' => 'DESC'));
				$pagerfanta = new Pagerfanta(new ArrayAdapter($actualites));
				$pagerfanta->setMaxPerPage($maxPerPage);
		
				try 
				{
					$pagerfanta->setCurrentPage($page);
				} 
				catch(NotValidCurrentPageException $e) 
				{
					throw new NotFoundHttpException();
				}
                break;
            case 'tous':
                $actualites = $em->getRepository('AMiEActualitesBundle:Actualite')->findBy(array(), array('updatedDate' => 'DESC'));
				$pagerfanta = new Pagerfanta(new ArrayAdapter($actualites));
				$pagerfanta->setMaxPerPage($maxPerPage);
		
				try 
				{
					$pagerfanta->setCurrentPage($page);
				} 
				catch(NotValidCurrentPageException $e) 
				{
					throw new NotFoundHttpException();
				}
                break;
            default:
                $actualites = $em->getRepository('AMiEActualitesBundle:Actualite')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'));
				$pagerfanta = new Pagerfanta(new ArrayAdapter($actualites));
				$pagerfanta->setMaxPerPage($maxPerPage);
		
				try 
				{
					$pagerfanta->setCurrentPage($page);
				} 
				catch(NotValidCurrentPageException $e) 
				{
					throw new NotFoundHttpException();
				}
                $actif = 'actif';
                break;
        }
        $imagesArray = array();
        foreach($actualites as $actualite){
            $imagesArray[$actualite->getId()] = $em->getRepository('AMiEActualitesBundle:Image')->findOneBy(array('idActualite' => $actualite->getId()));
        }

        return $this->render('AMiEActualitesBundle:News:index.html.twig', array(
            'layout'                => $layout,
            'actif'                 => $actif,
            'actualites'            => $pagerfanta,
            'images'                => $imagesArray
        ));
    }
	
    public function actualiteAction(Actualite $actualite)
    {
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('AMiEHomeBundle:Notification')->findBy(array('idActualite' => $actualite->getId(), 'vue' => 0));
        foreach($notifications as $notification) {
            $notification->setVue(1);
            $em->persist($notification);
            $em->flush();
        }

        $layout = $this->getLayout($em);

        $image = $em->getRepository('AMiEActualitesBundle:Image')->findOneBy(array('idActualite' => $actualite->getId()));

        return $this->render('AMiEActualitesBundle:News:actualite.html.twig', array(
            'layout' => $layout,
            'actualite' => $actualite,
            'image' => $image
        ));
    }

    public function ajouterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $insert = self::insertOrUpdate($em, $user);
        if($insert['Valide']){
            $actualite = $insert['actualite'];

            $notification = new Notification();
            $notification->setAction('Ajout d\'une actualité')
                ->setDescriptif('L\'utilisateur '.$user->getUsername().' a posté une actualité : '.$actualite->getTitre())
                ->setIdUser($user->getId());
            $em->persist($notification);
            $em->flush();

            return $this->redirect($this->generateUrl('amie_actualites_actualite', array(
                'id'    => $actualite->getId(),
                'slug'  => $this->get('slugify')->slugify($actualite->getTitre())
            )));
        }

        $layout = $this->getLayout($em);
        return $this->render('AMiEActualitesBundle:News:ajouter.html.twig', array(
            'layout'    => $layout,
            'formActu'  => $insert['formActu'],
            'formImage' => $insert['formImage']
        ));
    }

    public function modifierAction(Actualite $actualite)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $update = self::insertOrUpdate($em, $user, $actualite->getId());
        if($update['Valide']){
            $actualite = $update['actualite'];
            $notification = new Notification();
            $notification->setAction('Modification d\'une actualité')
                ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié l\'actualité : '.$actualite->getTitre())
                ->setIdUser($user->getId());
            $em->persist($notification);
            $em->flush();
            return $this->redirect($this->generateUrl('amie_actualites_actualite', array(
                'id'    => $actualite->getId(),
                'slug'  => $this->get('slugify')->slugify($actualite->getTitre())
            )));
        }

        $layout = $this->getLayout($em);
        return $this->render('AMiEActualitesBundle:News:modifier.html.twig', array(
            'layout'    => $layout,
            'formActu'  => $update['formActu'],
            'formImage' => $update['formImage'],
            'actualite' => $actualite
        ));
    }

    public function insertOrUpdate($em, $user, $id=null){
        if($id == null){
            $actualite = new Actualite();
            $image = new Image();
        }else{
            $actualite = $em->getRepository('AMiEActualitesBundle:Actualite')->find($id);
            $image = $em->getRepository('AMiEActualitesBundle:Image')->findOneBy(array('idActualite' => $id));
        }
        $formActu = $this->createForm(new ActualiteType(), $actualite);
        $formImage = $this->createForm(new ImageType(), $image);

        $requete = $this->get('request');
        if($requete->isMethod('POST')){

            $formActu->handleRequest($requete);
            $formImage->handleRequest($requete);
            if($formActu->isValid() && $formImage->isValid()){
                $actualite = $formActu->getData();

                if($id == null){
                    // cas de l'ajout
                    $actualite->setAddedBy($user->getUsername())
                              ->setUpdatedBy($user->getUsername());
                }else{
                    // cas de la modification
                    $actualite->setUpdatedBy($user->getUsername())
                              ->setUpdatedDate(new \DateTime());
                }
                $em->persist($actualite);
                $em->flush();

                $image = $formImage->getData();
                if($id == null){
                    // cas de l'ajout
                    $image->setIdActualite($actualite->getId())
                          ->setAlt($this->get('slugify')->slugify($actualite->getTitre()));
                }
                $em->persist($image);
                $em->flush();

                return array('Valide' => true, 'actualite' => $actualite);
            }
        }
        return array('Valide' => false, 'formActu' => $formActu->createView(), 'formImage' => $formImage->createView());
    }

    public function desactiverAction(Actualite $actualite)
    {
        $em = $this->getDoctrine()->getManager();

        $actualite->setActif('F');

        $em->persist($actualite);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_actualites_news'));
    }

    public function activerAction(Actualite $actualite)
    {
        $em = $this->getDoctrine()->getManager();

        $actualite->setActif('A');

        $em->persist($actualite);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_actualites_news', array(
            'actif'    => 'inactif'
        )));
    }

    public function supprimerAction(Actualite $actualite)
    {
        $em = $this->getDoctrine()->getManager();

        $image = $em->getRepository('AMiEActualitesBundle:Image')->findOneBy(array('idActualite' => $actualite->getId()));
        if($path = $image->getAbsolutePath())
            unlink($path);
        $em->remove($image);

        $em->remove($actualite);

        $em->flush();

        return $this->redirect($this->generateUrl('amie_actualites_news'));
    }
}
?>