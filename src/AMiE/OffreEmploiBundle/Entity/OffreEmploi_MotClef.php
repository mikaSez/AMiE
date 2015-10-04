<?php

namespace AMiE\OffreEmploiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OffreEmploi_MotClef
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\OffreEmploiBundle\Entity\OffreEmploi_MotClefRepository")
 */
class OffreEmploi_MotClef
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
     * @ORM\Column(name="id_offreEmploi", type="integer")
     */
    private $idOffreEmploi;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_motClef", type="integer")
     */
    private $idMotClef;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="added_date", type="datetime")
     */
    private $addedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="added_by", type="string", length=30)
     */
    private $addedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_date", type="datetime")
     */
    private $updatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="updated_by", type="string", length=30)
     */
    private $updatedBy;


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
     * Set idOffreEmploi
     *
     * @param integer $idOffreEmploi
     * @return OffreEmploi_MotClef
     */
    public function setIdOffreEmploi($idOffreEmploi)
    {
        $this->idOffreEmploi = $idOffreEmploi;
    
        return $this;
    }

    /**
     * Get idOffreEmploi
     *
     * @return integer 
     */
    public function getIdOffreEmploi()
    {
        return $this->idOffreEmploi;
    }

    /**
     * Set idMotClef
     *
     * @param integer $idMotClef
     * @return OffreEmploi_MotClef
     */
    public function setIdMotClef($idMotClef)
    {
        $this->idMotClef = $idMotClef;
    
        return $this;
    }

    /**
     * Get idMotClef
     *
     * @return integer 
     */
    public function getIdMotClef()
    {
        return $this->idMotClef;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return OffreEmploi_MotClef
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return OffreEmploi_MotClef
     */
    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
    
        return $this;
    }

    /**
     * Get addedDate
     *
     * @return \DateTime 
     */
    public function getAddedDate()
    {
        return $this->addedDate;
    }

    /**
     * Set addedBy
     *
     * @param string $addedBy
     * @return OffreEmploi_MotClef
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    
        return $this;
    }

    /**
     * Get addedBy
     *
     * @return string 
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     * @return OffreEmploi_MotClef
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    
        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime 
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set updatedBy
     *
     * @param string $updatedBy
     * @return OffreEmploi_MotClef
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    
        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return string 
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
