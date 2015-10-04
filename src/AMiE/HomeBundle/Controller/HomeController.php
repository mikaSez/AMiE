<?php
namespace AMiE\HomeBundle\Controller;

use AMiE\HomeBundle\Entity\Message;
use AMiE\HomeBundle\Entity\Conversation;

class HomeController extends CoreController
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $notifications = $em->getRepository('AMiEHomeBundle:Notification')->findBy(array('vue' => 0));
        foreach($notifications as $notification) {
            $notification->setVue(1);
            $em->persist($notification);
            $em->flush();
        }

        $layout = $this->getLayout($em);
        $actualites = $em->getRepository('AMiEActualitesBundle:Actualite')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'), 4);
        $offres = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findBy(array('actif' => 'A'), array('updatedDate' => 'DESC'), 4);

        return $this->render('AMiEHomeBundle:Home:index.html.twig', array(
            'layout'     => $layout,
            'actualites' => $actualites,
            'offres'     => $offres
        ));
    }

    public function messagerieAction($idConversation=null)
    {
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $user = $this->get('security.context')->getToken()->getUser();
        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('AMiEHomeBundle:Conversation', 'c')
            ->where('c.idUtilisateur1 = :currentUser OR c.idUtilisateur2 = :currentUser')
            ->orderBy('c.lastMessage', 'DESC')
            ->setParameters(array('currentUser' => $user->getId()))
            ->getQuery()
        ;
        $conversations = $query->getResult();

        $users = array();
        foreach($conversations as $conversation){
            if($conversation->getIdUtilisateur1() != $user->getId()) {
                $entityUser = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
            }else{
                $entityUser = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
            }
            $users[$conversation->getId()] = $entityUser->getPrenom().' '.$entityUser->getNom().' ('.$entityUser->getUsername().')';
        }

        $messages = array();

        if($idConversation != null){
            $messages = $em->getRepository('AMiEHomeBundle:Message')->findBy(
                array('idConversation' => $idConversation), 
                array('sentDate' => 'ASC')
            );

            $conversation = $em->getRepository('AMiEHomeBundle:Conversation')->findOneById($idConversation);
            $conversation->setVue(1);
            $em->persist($conversation);
            $em->flush();

            if($conversation->getIdUtilisateur1() != $user->getId()){
                $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
            }else{
                $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
            }

        }else{
            $query = $em->createQueryBuilder()
                ->select('c')
                ->from('AMiEHomeBundle:Conversation', 'c')
                ->where('c.idUtilisateur1 = :currentUser OR c.idUtilisateur2 = :currentUser')
                ->orderBy('c.lastMessage', 'DESC')
                ->setMaxResults(1)
                ->setParameters(array('currentUser' => $user->getId()))
                ->getQuery()
            ;
            $conversation = $query->getResult();
            if(!empty($conversation)){
                $conversation = $conversation[0];
                $messages = $em->getRepository('AMiEHomeBundle:Message')->findBy(
                    array('idConversation' => $conversation->getId()),
                    array('sentDate' => 'ASC')
                );

                if($conversation->getIdUtilisateur1() != $user->getId()){
                    $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
                }else{
                    $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
                }
            }else{
                $conversation = null;
                $userCorrespondant = null;
            }
        }

        return $this->render('AMiEHomeBundle:Home:messagerie.html.twig', array(
            'layout'                => $layout,
            'conversations'         => $conversations,
            'usersName'             => $users,
            'messages'              => $messages,
            'conversationActuelle'  => $conversation,
            'userCorrespondant'     => $userCorrespondant
        ));
    }

    public function envoimessageAction($idConversation){
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);
        $user = $this->get('security.context')->getToken()->getUser();

        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('AMiEHomeBundle:Conversation', 'c')
            ->where('c.idUtilisateur1 = :currentUser OR c.idUtilisateur2 = :currentUser')
            ->setParameters(array('currentUser' => $user->getId()))
            ->getQuery()
        ;
        $conversations = $query->getResult();

        $users = array();
        foreach($conversations as $conversation){
            if($conversation->getIdUtilisateur1() != $user->getId()) {
                $entityUser = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
            }else{
                $entityUser = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
            }
            $users[$conversation->getId()] = $entityUser->getPrenom().' '.$entityUser->getNom().' ('.$entityUser->getUsername().')';
        }

        $conversation = $em->getRepository('AMiEHomeBundle:Conversation')->findOneById($idConversation);

        if($conversation->getIdUtilisateur1() != $user->getId()){
            $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
        }else{
            $userCorrespondant = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
        }

        $requete = $this->get('request');
        if($requete->isMethod('POST')) {
            $message = new Message();

            if(!empty($_POST['message'])){
                if($conversation->getIdUtilisateur1() == $user->getId()){
                    $idDestinataire = $conversation->getIdUtilisateur1();
                }else{
                    $idDestinataire = $conversation->getIdUtilisateur2();
                }

                $conversation->setVue(0)
                             ->setLastMessage(new \DateTime());
                $em->persist($conversation);
                $em->flush();

                $message->setIdConversation($idConversation)
                        ->setIdEnvoyeur($user->getId())
                        ->setIdDestinataire($idDestinataire)
                        ->setContenu(trim(htmlspecialchars($_POST['message'])));

                $em->persist($message);
                $em->flush();
                $messages = $em->getRepository('AMiEHomeBundle:Message')->findBy(
                    array('idConversation' => $idConversation),
                    array('sentDate'       => 'ASC')
                );

                return $this->render('AMiEHomeBundle:Home:messagerie.html.twig', array(
                    'layout'                => $layout,
                    'conversations'         => $conversations,
                    'usersName'             => $users,
                    'messages'              => $messages,
                    'conversationActuelle'  => $conversation,
                    'userCorrespondant'     => $userCorrespondant
                ));
            }
        }

        $messages = array();

        return $this->render('AMiEHomeBundle:Home:messagerie.html.twig', array(
            'layout'                => $layout,
            'conversations'         => $conversations,
            'usersName'             => $users,
            'messages'              => $messages,
            'conversationActuelle'  => $conversation,
            'userCorrespondant'     => $userCorrespondant
        ));
    }

    public function creationconversationAction(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        if(!empty($_POST['correspondant']) && !empty($_POST['contenu'])){
            $conversation = new Conversation();
            $conversation->setTitre('Conversation entre '.$user->getId().' et '.$_POST['correspondant'])
                ->setIdUtilisateur1($_POST['correspondant'])
                ->setIdUtilisateur2($user->getId());
            $em->persist($conversation);
            $em->flush();

            $message = new Message();
            $message->setIdConversation($conversation->getId())
                    ->setIdEnvoyeur($user->getId())
                    ->setIdDestinataire($_POST['correspondant'])
                    ->setContenu($_POST['contenu']);
            $em->persist($message);
            $em->flush();

            return $this->redirect($this->generateUrl('amie_home_messagerie', array(
                'idConversation' => $conversation->getId(),
            )));
        }

        $users = $em->getRepository('AMiEUserBundle:User')->findBy(array('enabled' => 1));

        $layout = $this->getLayout($em);
        return $this->render('AMiEHomeBundle:Home:envoimessage.html.twig', array(
            'layout' => $layout,
            'users'  => $users
        ));
    }

    public function notificationsAction(){
        $em = $this->getDoctrine()->getManager();
        $layout = $this->getLayout($em);

        $notifications = $em->getRepository('AMiEHomeBundle:Notification')->findAll(array('postedDate' => 'DESC'));

        $users = array();
        $offres = array();
        $actualites = array();
        $formulaires = array();
        foreach($notifications as $notification){
            $users[$notification->getId()] = $em->getRepository('AMiEUserBundle:User')->findOneById($notification->getIdUser());
            $idOffre = $notification->getIdOffre();
            $idActualite = $notification->getIdActualite();
            $idFormulaire = $notification->getIdFormulaire();
            if(!empty($idOffre)) {
                $offres[$notification->getId()] = $em->getRepository('AMiEOffreEmploiBundle:OffreEmploi')->findOneById($idOffre);
            }elseif(!empty($idActualite)){
                $actualites[$notification->getId()] = $em->getRepository('AMiEActualitesBundle:Actualite')->findOneById($idActualite);
            }elseif(!empty($idFormulaire)){
                $formulaires[$notification->getId()] = $em->getRepository('AMiEMiagistesBundle:Formulaire')->findOneById($idFormulaire);
            }
        }

        return $this->render('AMiEHomeBundle:Home:notifications.html.twig', array(
            'layout'        => $layout,
            'notifications' => $notifications,
            'users'         => $users,
            'offres'        => $offres,
            'actualites'    => $actualites,
            'formulaires'   => $formulaires
        ));
    }

    public function supprimermessageAction($idConversation)
    {
        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository('AMiEHomeBundle:Message')->findBy(array('idConversation' => $idConversation));
        foreach($messages as $message){
            $em->remove($message);
            $em->flush();
        }

        $conversation = $em->getRepository('AMiEHomeBundle:Conversation')->findOneById($idConversation);

        $em->remove($conversation);
        $em->flush();

        return $this->redirect($this->generateUrl('amie_home_messagerie_sans_conversation'));
    }
}
