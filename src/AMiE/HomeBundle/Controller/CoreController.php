<?php
namespace AMiE\HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function getLayout($em){
        $securityContext = $this->get('security.context');
        $user = $securityContext->getToken()->getUser();
        $notifications = array();
        $conversations = array();
        $correspondants = array();
        $conversationsNonLues = 0;
        $firstMessages = array();
        if($securityContext->isGranted('ROLE_ADMIN')){ //$user->hasRole('ROLE_ADMIN')

            // On recherche les notifications non vues par l'administrateur
            if($securityContext->isGranted('ROLE_SUPER_ADMIN')){
                $notifications = $em->getRepository('AMiEHomeBundle:Notification')->findBy(
                    array('vue' => 0),
                    array('postedDate' => 'ASC')
                );
            }

            // On récupère toutes les conversations datant du mois dernier ayant pour destinataire l'utilisateur connecté
            $query = $em->createQueryBuilder()
                ->select('c')
                ->from('AMiEHomeBundle:Conversation', 'c')
                ->where('c.startDate > :lastMonth AND (c.idUtilisateur1 = :currentUser OR c.idUtilisateur2 = :currentUser)')
                ->setParameters(array('lastMonth' => date('Y-m-d', strtotime('-1 month')), 'currentUser' => $user->getId()))
                ->getQuery()
            ;
            $conversations = $query->getResult();

            foreach ($conversations as $conversation) {
                if($conversation->getIdUtilisateur1() == $user->getId()){
                    $correspondants[$conversation->getId()] = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur2());
                }else{
                    $correspondants[$conversation->getId()] = $em->getRepository('AMiEUserBundle:User')->findOneById($conversation->getIdUtilisateur1());
                }
            }

            foreach ($conversations as $conversation) {

                $messages = $em->getRepository('AMiEHomeBundle:Message')->findBy(array('idConversation' => $conversation->getId()), 
                    array('sentDate' => 'ASC')
                );

                if($conversation->getVue() == 0){
                    $conversationsNonLues++;
                }

                foreach ($messages as $message) {
                    $firstMessages[$conversation->getId()] = $message;
                    break;
                }
            }
        }
        return array(
            'notifications'         => $notifications,
            'conversations'         => $conversations,
            'conversationsNonLues'  => $conversationsNonLues,
            'firstMessages'         => $firstMessages,
            'correspondants'        => $correspondants
        );
    }
}
