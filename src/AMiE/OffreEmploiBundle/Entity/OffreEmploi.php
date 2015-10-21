<?php
namespace AMiE\OffreEmploiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * OffreEmploi
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\OffreEmploiBundle\Entity\OffreEmploiRepository")
 */
class OffreEmploi
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
     * @ORM\Column(name="id_entreprise", type="integer")
     */
    private $idEntreprise;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEntreprise", type="string", length=160)
     */
    private $nomEntreprise;
	
	/**
     * @var string
     *
     * @ORM\Column(name="entreprise", type="string", length=255)
     */
    private $entreprise;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=40)
     * @Assert\NotBlank()
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="text")
     * @Assert\NotBlank()
     */
    private $descriptif;

    /**
     * @var string
     *
     * @ORM\Column(name="lieuTravail", type="string", length=150)
     * @Assert\NotBlank()
     */
    private $lieuTravail;

    /**
     * @var string
     *
     * @ORM\Column(name="departement", type="string", length=40)
     * @Assert\NotBlank()
     */
    private $departement;

    /**
     * @var string
     *
     * @ORM\Column(name="type_poste", type="string", length=30)
     * @Assert\NotBlank()
     */
    private $typePoste;

    /**
     * @var string
     *
     * @ORM\Column(name="type_contrat", type="string", length=30)
     * @Assert\NotBlank()
     */
    private $typeContrat;

    /**
     * @var string
     *
     * @ORM\Column(name="xpExigee", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $xpExigee;

    /**
     * @var string
     *
     * @ORM\Column(name="urgence", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $urgence;

    /**
     * @var integer
     *
     * @ORM\Column(name="salaire", type="integer", nullable=true)
     */
    private $salaire;

    /**
     * @var string
     *
     * @ORM\Column(name="contactNom", type="string", length=60)
     * @Assert\NotBlank()
     */
    private $contactNom;

    /**
     * @var string
     *
     * @ORM\Column(name="contactMail", type="string", length=160)
     * @Assert\Email()
     */
    private $contactMail;

    /**
     * @var string
     *
     * @ORM\Column(name="contactTel", type="string", length=15, nullable=true)
     */
    private $contactTel;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1)
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

    function __construct(){
        $this->actif = 'A';
        $this->addedDate = new \DateTime();
        $this->updatedDate = new \DateTime();
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
     * Set idEntreprise
     *
     * @param integer $idEntreprise
     * @return OffreEmploi
     */
    public function setIdEntreprise($idEntreprise)
    {
        $this->idEntreprise = $idEntreprise;
    
        return $this;
    }

    /**
     * Get idEntreprise
     *
     * @return integer 
     */
    public function getIdEntreprise()
    {
        return $this->idEntreprise;
    }

    /**
     * Set nomEntreprise
     *
     * @param string $nomEntreprise
     * @return OffreEmploi
     */
    public function setNomEntreprise($nomEntreprise)
    {
        $this->nomEntreprise = $nomEntreprise;
    
        return $this;
    }

    /**
     * Get entreprise
     *
     * @return string 
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }
	
	    /**
     * Set entreprise
     *
     * @param string $nomEntreprise
     * @return OffreEmploi
     */
    public function setEntreprise($entreprise)
    {
        $this->entreprise = $entreprise;
    
        return $this;
    }

    /**
     * Get nomEntreprise
     *
     * @return string 
     */
    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return OffreEmploi
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
     * Set descriptif
     *
     * @param string $descriptif
     * @return OffreEmploi
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
     * Set lieuTravail
     *
     * @param string $lieuTravail
     * @return OffreEmploi
     */
    public function setLieuTravail($lieuTravail)
    {
        $this->lieuTravail = $lieuTravail;
    
        return $this;
    }

    /**
     * Get lieuTravail
     *
     * @return string 
     */
    public function getLieuTravail()
    {
        return $this->lieuTravail;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return OffreEmploi
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;
    
        return $this;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set typePoste
     *
     * @param string $typePoste
     * @return OffreEmploi
     */
    public function setTypePoste($typePoste)
    {
        $this->typePoste = $typePoste;
    
        return $this;
    }

    /**
     * Get typePoste
     *
     * @return string 
     */
    public function getTypePoste()
    {
        return $this->typePoste;
    }

    /**
     * Set typeContrat
     *
     * @param string $typeContrat
     * @return OffreEmploi
     */
    public function setTypeContrat($typeContrat)
    {
        $this->typeContrat = $typeContrat;
    
        return $this;
    }

    /**
     * Get typeContrat
     *
     * @return string 
     */
    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    /**
     * Set xpExigee
     *
     * @param string $xpExigee
     * @return OffreEmploi
     */
    public function setXpExigee($xpExigee)
    {
        $this->xpExigee = $xpExigee;
    
        return $this;
    }

    /**
     * Get xpExigee
     *
     * @return string 
     */
    public function getXpExigee()
    {
        return $this->xpExigee;
    }

    /**
     * Set urgence
     *
     * @param string $urgence
     * @return OffreEmploi
     */
    public function setUrgence($urgence)
    {
        $this->urgence = $urgence;
    
        return $this;
    }

    /**
     * Get urgence
     *
     * @return string 
     */
    public function getUrgence()
    {
        return $this->urgence;
    }

    /**
     * Set salaire
     *
     * @param integer $salaire
     * @return OffreEmploi
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;
    
        return $this;
    }

    /**
     * Get salaire
     *
     * @return integer 
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set contactNom
     *
     * @param string $contactNom
     * @return OffreEmploi
     */
    public function setContactNom($contactNom)
    {
        $this->contactNom = $contactNom;
    
        return $this;
    }

    /**
     * Get contactNom
     *
     * @return string 
     */
    public function getContactNom()
    {
        return $this->contactNom;
    }

    /**
     * Set contactMail
     *
     * @param string $contactMail
     * @return OffreEmploi
     */
    public function setContactMail($contactMail)
    {
        $this->contactMail = $contactMail;
    
        return $this;
    }

    /**
     * Get contactMail
     *
     * @return string 
     */
    public function getContactMail()
    {
        return $this->contactMail;
    }

    /**
     * Set contactTel
     *
     * @param string $contactTel
     * @return OffreEmploi
     */
    public function setContactTel($contactTel)
    {
        $this->contactTel = $contactTel;
    
        return $this;
    }

    /**
     * Get contactTel
     *
     * @return string 
     */
    public function getContactTel()
    {
        return $this->contactTel;
    }

    /**
     * Set actif
     *
     * @param string $actif
     * @return OffreEmploi
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return string 
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * Set addedDate
     *
     * @param \DateTime $addedDate
     * @return OffreEmploi
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
     * @return OffreEmploi
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
     * @return OffreEmploi
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
     * @return OffreEmploi
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
