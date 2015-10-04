<?php

namespace AMiE\HomeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\HomeBundle\Entity\NotificationRepository")
 */
class Notification
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
     * @ORM\Column(name="action", type="string", length=60)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="text")
     */
    private $descriptif;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_offre", type="integer", nullable=true)
     */
    private $idOffre;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_actualite", type="integer", nullable=true)
     */
    private $idActualite;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_formulaire", type="integer", nullable=true)
     */
    private $idFormulaire;

    /**
     * @var boolean
     *
     * @ORM\Column(name="vue", type="boolean")
     */
    private $vue;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="posted_date", type="datetime")
     */
    private $postedDate;

    function __construct(){
        $this->vue = 0;
        $this->postedDate = new \DateTime();
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
     * Set action
     *
     * @param string $action
     * @return Notification
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set descriptif
     *
     * @param string $descriptif
     * @return Notification
     */
    public function setDescriptif($descriptif)
    {
        $this->descriptif = $descriptif;
    
        return $this;
    }

    /**
     * Get descriptif
     *
     * @return string 
     */
    public function getDescriptif()
    {
        return $this->descriptif;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     * @return Notification
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    
        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idOffre
     *
     * @param integer $idOffre
     * @return Notification
     */
    public function setIdOffre($idOffre)
    {
        $this->idOffre = $idOffre;
    
        return $this;
    }

    /**
     * Get idOffre
     *
     * @return integer 
     */
    public function getIdOffre()
    {
        return $this->idOffre;
    }

    /**
     * Set idActualite
     *
     * @param integer $idActualite
     * @return Notification
     */
    public function setIdActualite($idActualite)
    {
        $this->idActualite = $idActualite;

        return $this;
    }

    /**
     * Get idActualite
     *
     * @return integer
     */
    public function getIdActualite()
    {
        return $this->idActualite;
    }

    /**
     * Set idFormulaire
     *
     * @param integer $idFormulaire
     * @return Notification
     */
    public function setIdFormulaire($idFormulaire)
    {
        $this->idFormulaire = $idFormulaire;

        return $this;
    }

    /**
     * Get idFormulaire
     *
     * @return integer
     */
    public function getIdFormulaire()
    {
        return $this->idFormulaire;
    }

    /**
     * Set vue
     *
     * @param boolean $vue
     * @return Notification
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
     * Set postedDate
     *
     * @param \DateTime $postedDate
     * @return Notification
     */
    public function setPostedDate($postedDate)
    {
        $this->postedDate = $postedDate;
    
        return $this;
    }

    /**
     * Get postedDate
     *
     * @return \DateTime 
     */
    public function getPostedDate()
    {
        return $this->postedDate;
    }
}
