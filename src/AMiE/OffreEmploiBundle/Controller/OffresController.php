<?php
namespace AMiE\OffreEmploiBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;

use AMiE\OffreEmploiBundle\Entity\OffreEmploi;
use AMiE\OffreEmploiBundle\Entity\OffreEmploiRepository;
use AMiE\OffreEmploiBundle\Form\OffreEmploiType;
use AMiE\OffreEmploiBundle\Entity\OffreEmploi_MotClef;
use AMiE\OffreEmploiBundle\Entity\MotClef;
use AMiE\OffreEmploiBundle\Form\MotClefType;
use AMiE\OffreEmploiBundle\Entity\DocumentJoint;
use AMiE\OffreEmploiBundle\Form\DocumentJointType;
use AMiE\OffreEmploiBundle\Form\RechercheType;

use AMiE\HomeBundle\Entity\Notification;

class OffresController extends CoreController
{
    public function indexAction($actif)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        switch ($actif){
            case 'actif':
                $offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'));
                break;
            case 'inactif':
                $offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findBy(array('actif' => 'F'), array('updatedDate' => 'DESC'));
                break;
            case 'tous':
                $offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findBy(array(), array('updatedDate' => 'DESC'));
                break;
            default:
                $offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'));
                $actif = 'actif';
                break;
        }

        return $this->render('AMiEOffreEmploiBundle:Offres:index.html.twig', array(
            'layout'            => $layout,
            'actif'             => $actif,
            'offres'            => $offres
        ));
    }

    public function rechercheAction($actif)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $parameters = array();
        $query = $em->createQueryBuilder()
            ->select('o')
            ->from('AMiEOffreEmploiBundle:OffreEmploi', 'o')
            ->where('o.actif = :actif');

        switch ($actif){
            case 'actif':
                $parameters['actif'] = 'A';
                break;
            case 'inactif':
                $parameters['actif'] = 'F';
                break;
            case 'tous':
                $parameters['actif'] = 'A';
                break;
            default:
                $parameters['actif'] = 'A';
                $actif = 'actif';
                break;
        }

        if(!empty($_POST['date_ajout'])){
            $date = explode('/', $_POST['date_ajout']);
            $query->andWhere('o.addedDate >= :date');
            $date = $date[2].'-'.$date[1].'-'.$date[0].' 00:00:00';
            //$date = strtotime($date.' + 2 MONTH');
            $parameters['date'] = $date;
        }

        if(!empty($_POST['titre'])){
            $query->andWhere('o.titre LIKE :titre');
            $parameters['titre'] = '%'.$_POST['titre'].'%';
        }

        if(!empty($_POST['entreprise'])){
            $query->andWhere('o.nomEntreprise LIKE :entreprise');
            $parameters['entreprise'] = '%'.$_POST['entreprise'].'%';
        }

        if(!empty($_POST['lieu'])){
            $query->andWhere('(o.lieuTravail LIKE :lieu OR o.departement LIKE :lieu)');
            $parameters['lieu'] = '%'.$_POST['lieu'].'%';
        }

        if(!empty($_POST['typeposte'])){
            $query->andWhere('(o.typePoste = :typeposte)');
            $parameters['typeposte'] = $_POST['typeposte'];
        }

        if(!empty($_POST['typecontrat'])){
            $query->andWhere('(o.typeContrat = :typecontrat)');
            $parameters['typecontrat'] = $_POST['typecontrat'];
        }

        $query->orderBy('o.updatedDate', 'ASC')->setParameters($parameters);

        $result = $query->getQuery();
        $offres = $result->getResult();

        return $this->render('AMiEOffreEmploiBundle:Offres:index.html.twig', array(
            'layout' => $layout,
            'actif'  => $actif,
            'offres' => $offres
        ));
    }

    public function offreAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('AMiEHomeBundle:Notification')->findBy(array('idOffre' => $offre->getId(), 'vue' => 0));
        foreach($notifications as $notification) {
            $notification->setVue(1);
            $em->persist($notification);
            $em->flush();
        }

        $layout = $this->getLayout($em);

        return $this->render('AMiEOffreEmploiBundle:Offres:offre.html.twig', array(
            'layout' => $layout,
            'offre'  => $offre
        ));
    }

    public function ajouterAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $insert = self::insertOrUpdate($em, $user);
        if($insert['Valide']){
            $offre = $insert['offre'];

            $notification = new Notification();
            $notification->setAction('Ajout d\'une offre')
                         ->setDescriptif('L\'entreprise '.$user->getUsername().' a posté une offre : '.$offre->getTitre())
                         ->setIdUser($user->getId())
                         ->setIdOffre($offre->getId());
            $em->persist($notification);
            $em->flush();

            return $this->redirect($this->generateUrl('amie_offresemplois_offre', array(
                'id'    => $offre->getId(),
                'slug'  => $this->get('slugify')->slugify($offre->getTitre())
            )));
        }

        $layout = $this->getLayout($em);
        return $this->render('AMiEOffreEmploiBundle:Offres:ajouter.html.twig', array(
            'layout'    => $layout,
            'formOffre' => $insert['formOffre'],
            'formDoc'   => $insert['formDoc'], 
            'action'    => 'Ajouter'
        ));
    }

    public function modifierAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $update = self::insertOrUpdate($em, $user, $offre->getId());
        if($update['Valide']){
            $offre = $update['offre'];
            $notification = new Notification();
            $notification->setAction('Modification d\'une offre')
                         ->setDescriptif('L\'entreprise '.$user->getUsername().' a modifié l\'offre : '.$offre->getTitre())
                         ->setIdUser($user->getId())
                         ->setIdOffre($offre->getId());
            $em->persist($notification);
            $em->flush();
            return $this->redirect($this->generateUrl('amie_offresemplois_offre', array(
                'id'    => $offre->getId(), 
                'slug'  => $this->get('slugify')->slugify($offre->getTitre())
            )));
        }

        $layout = $this->getLayout($em);
        return $this->render('AMiEOffreEmploiBundle:Offres:ajouter.html.twig', array(
            'layout'    => $layout,
            'formOffre' => $update['formOffre'],
            'formDoc'   => $update['formDoc'],
            'action'    => 'Modifier',
            'offre'     => $offre
        ));
    }

    public function modifierdescriptifAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST')){
            if(!empty($_POST['descriptif'])){
                $offre->setDescriptif(trim(htmlspecialchars($_POST['descriptif'])));

                $em->persist($offre);
                $em->flush();

                $notification = new Notification();
                $notification->setAction('Modification d\'une offre')
                    ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié l\'offre : '.$offre->getTitre())
                    ->setIdUser($user->getId())
                    ->setIdOffre($offre->getId());
                $em->persist($notification);
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('amie_offresemplois_offre', array(
            'id'    => $offre->getId(),
            'slug'  => $this->get('slugify')->slugify($offre->getTitre())
        )));
    }

    public function modifiercontactAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST')){
            if(!empty($_POST['contactNom'])){
                $offre->setContactNom(trim(htmlspecialchars($_POST['contactNom'])));
            }
            if(!empty($_POST['contactMail'])){
                $offre->setContactMail(trim(htmlspecialchars($_POST['contactMail'])));
            }
            if(!empty($_POST['contactTel'])){
                $offre->setContactTel(trim(htmlspecialchars($_POST['contactTel'])));
            }
            $em->persist($offre);
            $em->flush();

            $notification = new Notification();
            $notification->setAction('Modification d\'une offre')
                ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié l\'offre : '.$offre->getTitre())
                ->setIdUser($user->getId())
                ->setIdOffre($offre->getId());
            $em->persist($notification);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('amie_offresemplois_offre', array(
            'id'    => $offre->getId(),
            'slug'  => $this->get('slugify')->slugify($offre->getTitre())
        )));
    }

    public function modifiertypeAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $requete = $this->get('request');
        if($requete->isMethod('POST')){
            if(!empty($_POST['lieu'])) {
                $offre->setLieuTravail(trim(htmlspecialchars($_POST['lieu'])));
            }
            if(!empty($_POST['typeposte'])) {
                $offre->setTypePoste(trim(htmlspecialchars($_POST['typeposte'])));
            }
            if(!empty($_POST['typecontrat'])) {
                $offre->setTypeContrat(trim(htmlspecialchars($_POST['typecontrat'])));
            }
            if(!empty($_POST['xpexigee'])) {
                $offre->setXpExigee(trim(htmlspecialchars($_POST['xpexigee'])));
            }
            if(!empty($_POST['dureevalidite'])) {
                $offre->setDureeValidite(trim(htmlspecialchars($_POST['dureevalidite'])));
            }
            if(!empty($_POST['urgence'])) {
                $offre->setUrgence(trim(htmlspecialchars($_POST['urgence'])));
            }
            if(!empty($_POST['salaire'])) {
                $offre->setSalaire(trim(htmlspecialchars($_POST['salaire'])));
            }

            $em->persist($offre);
            $em->flush();

            $notification = new Notification();
            $notification->setAction('Modification d\'une offre')
                ->setDescriptif('L\'utilisateur '.$user->getUsername().' a modifié l\'offre : '.$offre->getTitre())
                ->setIdUser($user->getId())
                ->setIdOffre($offre->getId());
            $em->persist($notification);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('amie_offresemplois_offre', array(
            'id'    => $offre->getId(),
            'slug'  => $this->get('slugify')->slugify($offre->getTitre())
        )));
    }

    public function insertOrUpdate($em, $user, $id=null){
        if($id == null){
            $offre = new OffreEmploi();
            $document = new DocumentJoint();
        }else{
            $offre = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->find($id);
            $document = $em->getRepository('AMiEOffreEmploiBundle:DocumentJoint')->findOneBy(array('idOffreEmploi' => $id));
        }
        $formOffre = $this->createForm(new OffreEmploiType(), $offre);
        $formDoc = $this->createForm(new DocumentJointType(), $document);
        
        $requete = $this->get('request');
        if($requete->isMethod('POST')){

            $formOffre->handleRequest($requete);
            $formDoc->handleRequest($requete);
            if($formOffre->isValid() && $formDoc->isValid()){
                $offre = $formOffre->getData();
                // récupérer l'entreprise avec une requête par l'id utilisateur dans la table "entreprise"
                if($id == null){
                    // cas de l'ajout
                    $offre->setIdEntreprise($user->getId())
                          ->setNomEntreprise($user->getNom())
                          ->setAddedBy($user->getUsername())
                          ->setUpdatedBy($user->getUsername());
                }else{
                    // cas de la modification
                    $offre->setUpdatedBy($user->getUsername())
                          ->setUpdatedDate(new \DateTime());
                }
                $em->persist($offre);
                $em->flush();

                $document = $formDoc->getData();
                if($id == null){
                    // cas de l'ajout
                    $document->setIdOffreEmploi($offre->getId());
                }
                $em->persist($document);
                $em->flush();

                return array('Valide' => true, 'offre' => $offre);
            }
        }
        return array('Valide' => false, 'formOffre' => $formOffre->createView(), 'formDoc' => $formDoc->createView());
    }

    public function desactiverAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();

        $offre->setActif('F');

        $em->persist($offre);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_offresemplois_offres'));
    }

    public function activerAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();

        $offre->setActif('A');

        $em->persist($offre);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_offresemplois_offres', array(
            'actif'    => 'inactif'
        )));
    }

    public function supprimerAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();

        $document = $em->getRepository('AMiEOffreEmploiBundle:DocumentJoint')->findOneBy(array('idOffreEmploi' => $offre->getId()));
        if($path = $document->getAbsolutePath())
            unlink($path);
        $em->remove($document);

        $em->remove($offre);

        $em->flush();

        return $this->redirect($this->generateUrl('amie_offresemplois_offres'));
    }
}
?>