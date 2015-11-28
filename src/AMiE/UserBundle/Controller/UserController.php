<?php
namespace AMiE\UserBundle\Controller;

use AMiE\HomeBundle\Controller\CoreController;
use AMiE\UserBundle\Form\Type\ProfileFormType;
use AMiE\UserBundle\Entity\User;
use AMiE\UserBundle\Entity\UserRepository;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use AMiE\OffreEmploiBundle\Entity\OffreEmploi;
use AMiE\OffreEmploiBundle\Entity\OffreEmploiRepository;

class UserController extends CoreController
{
    public function gestionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $query = $this->getDoctrine()->getRepository('AMiEUserBundle:User')->searchAllUsers();
        $users = $query->getResult();
		
		$offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findAll();

        return $this->render('AMiEUserBundle:User:gestion.html.twig', array(
            'layout' => $layout,
            'utilisateurs' => $users,
			'offres'   => $offres
        ));
    }

    public function supprimeruserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $em->remove($user);

        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }

    public function afficheruserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        return $this->render('AMiEUserBundle:User:fiche.html.twig', array(
            'layout' => $layout,
            'user' => $user
        ));
    }

    public function modifieruserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $form = $this->createForm(new ProfileFormType(), $user);

        $requete = $this->get('request');

        if ($requete->getMethod() == 'POST') {
            $form->handleRequest($requete);
            //  if($form->isValid()){
            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            return $this->render('AMiEUserBundle:User:user_confirmed.html.twig', array(
                'layout' => $layout,
            ));
            // }
        }

        return $this->render('AMiEUserBundle:User:modifieruser.html.twig', array(
            'layout' => $layout,
            'id' => $user->getId(),
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    public function responsableAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user->setTypeUt('Responsable');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));


    }

    public function etudiantAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user->setTypeUt('Etudiant');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));


    }

    public function entrepriseAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user->setTypeUt('Entreprise');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));


    }

    public function desactiveruserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user->setEnabled('0');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }

    public function activeruserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user->setEnabled('1');
        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }
	
	public function desactiveroffreAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $offre->setActif('F');
        $em->persist($offre);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }

    public function activeroffreAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $offre->setActif('A');
        $em->persist($offre);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }
	
	public function supprimeroffreAction(OffreEmploi $offre)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $em->remove($offre);

        $em->flush();

        return $this->redirect($this->generateUrl('amie_user_gestion'));
    }

}

?>