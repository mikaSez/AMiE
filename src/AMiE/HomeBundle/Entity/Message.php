<?php

namespace AMiE\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\HomeBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_conversation", type="integer")
     */
    private $idConversation;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_envoyeur", type="integer")
     */
    private $idEnvoyeur;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_destinataire", type="integer")
     */
    private $idDestinataire;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_date", type="datetime")
     */
    private $sentDate;

    function __construct(){
        $this->sentDate = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idConversation
     *
     * @param string $idConversation
     * @return Message
     */
    public function setIdConversation($idConversation)
    {
        $this->idConversation = $idConversation;
    
        return $this;
    }

    /**
     * Get idConversation
     *
     * @return string 
     */
    public function getIdConversation()
    {
        return $this->idConversation;
    }

    /**
     * Set idEnvoyeur
     *
     * @param integer $idEnvoyeur
     * @return Message
     */
    public function setIdEnvoyeur($idEnvoyeur)
    {
        $this->idEnvoyeur = $idEnvoyeur;
    
        return $this;
    }

    /**
     * Get idEnvoyeur
     *
     * @return integer 
     */
    public function getIdEnvoyeur()
    {
        return $this->idEnvoyeur;
    }

    /**
     * Set idDestinataire
     *
     * @param integer $idDestinataire
     * @return Message
     */
    public function setIdDestinataire($idDestinataire)
    {
        $this->idDestinataire = $idDestinataire;
    
        return $this;
    }

    /**
     * Get idDestinataire
     *
     * @return integer 
     */
    public function getIdDestinataire()
    {
        return $this->idDestinataire;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Message
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    
        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set sentDate
     *
     * @param \DateTime $sentDate
     * @return Message
     */
    public function setSentDate($sentDate)
    {
        $this->sentDate = $sentDate;

        return $this;
    }

    /**
     * Get sentDate
     *
     * @return \DateTime
     */
    public function getSentDate()
    {
        return $this->sentDate;
    }
}
