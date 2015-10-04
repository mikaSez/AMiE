<?php
namespace AMiE\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conversation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\HomeBundle\Entity\ConversationRepository")
 */
class Conversation
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=120)
     */
    private $titre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user1", type="integer")
     */
    private $idUtilisateur1;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user2", type="integer")
     */
    private $idUtilisateur2;


    /**
     * @var boolean
     *
     * @ORM\Column(name="vue", type="boolean")
     */
    private $vue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdate", type="datetime")
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastMessage", type="datetime")
     */
    private $lastMessage;

    function __construct(){
        $this->vue = 0;
        $this->startDate = new \DateTime();
        $this->lastMessage = new \DateTime();
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
     * Set titre
     *
     * @param string $titre
     * @return Message
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set idUtilisateur1
     *
     * @param integer $idUtilisateur1
     * @return Message
     */
    public function setIdUtilisateur1($idUtilisateur1)
    {
        $this->idUtilisateur1 = $idUtilisateur1;
    
        return $this;
    }

    /**
     * Get idUtilisateur1
     *
     * @return integer 
     */
    public function getIdUtilisateur1()
    {
        return $this->idUtilisateur1;
    }

    /**
     * Set idUtilisateur2
     *
     * @param integer $idUtilisateur2
     * @return Message
     */
    public function setIdUtilisateur2($idUtilisateur2)
    {
        $this->idUtilisateur2 = $idUtilisateur2;
    
        return $this;
    }

    /**
     * Get idUtilisateur2
     *
     * @return integer 
     */
    public function getIdUtilisateur2()
    {
        return $this->idUtilisateur2;
    }


    /**
     * Set vue
     *
     * @param boolean $vue
     * @return Message
     */
    public function setVue($vue)
    {
        $this->vue = $vue;
    
        return $this;
    }

    /**
     * Get vue
     *
     * @return boolean 
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return Message
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set lastMessage
     *
     * @param \DateTime $lastMessage
     * @return Message
     */
    public function setLastMessage($lastMessage)
    {
        $this->lastMessage = $lastMessage;
    
        return $this;
    }

    /**
     * Get lastMessage
     *
     * @return \DateTime 
     */
    public function getLastMessage()
    {
        return $this->lastMessage;
    }
}
