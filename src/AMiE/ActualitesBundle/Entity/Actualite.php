<?php
namespace AMiE\ActualitesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="actualite")
 */
class Actualite {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $titre;

    /**
     * @ORM\Column(type="text")
     */
    protected $contenu;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1)
     */
    protected $actif;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $addedDate;

    /**
     * @ORM\Column(type="string")
     */
    protected $addedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $updatedBy;

    public function __construct(){
        $this->actif = 'A';
        $this->addedDate = new \DateTime();
        $this->updatedDate = new \DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function setTitre($titre)
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenu()
    {
        return $this->contenu;
    }

    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function getActif()
    {
        return $this->actif;
    }

    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    public function getAddedDate()
    {
        return $this->addedDate;
    }

    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
        return $this;
    }

    public function getAddedBy()
    {
        return $this->addedBy;
    }

    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
        return $this;
    }

    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
        return $this;
    }

    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
        return $this;
    }
}