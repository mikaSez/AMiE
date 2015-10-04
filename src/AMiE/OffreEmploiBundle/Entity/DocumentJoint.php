<?php
namespace AMiE\OffreEmploiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * DocumentJoint
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AMiE\OffreEmploiBundle\Entity\DocumentJointRepository")
 * @ORM\HasLifecycleCallbacks
 */
class DocumentJoint
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=120, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="descriptif", type="string", length=160, nullable=true)
     */
    private $descriptif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $file;

    private $filename;

    // propriété utilisé temporairement pour la suppression
    private $filenameForRemove;

    function __construct(){}

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // Génération d'un nom unique
            //$this->path = sha1(uniqid(mt_rand(), true)).'.'.$this->file->guessExtension();
            //$this->filenameForRemove = $this->getAbsolutePath();
            $datetime = new \DateTime();

            $this->filename = $this->remove_accents(trim(htmlspecialchars($this->file->getClientOriginalName())));
            $this->filename = $datetime->format('Y-m-d').'_'.str_replace(' ', '_', $this->filename); // $this->id.'_'. à rajouter
            $this->path = $this->filename;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->file) {
            return;
        }
        $filename = $this->id.'_'.$this->filename;

        // « move » prend comme arguments le répertoire cible et le nom de fichier cible où le fichier doit être déplacé
        $this->file->move($this->getUploadRootDir(), $filename);

        // définit la propriété « path » comme étant le nom de fichier où est stocké le fichier
        $this->path = $filename;

        // nettoie la propriété « file »
        unset($this->file);
    }

    public function remove_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);

        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
        return $str;
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->filenameForRemove = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        /*if ($file == $this->getAbsolutePath()) {
            unlink($file);
        }*/
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
     * Set idOffreEmploi
     *
     * @param integer $idOffreEmploi
     * @return DocumentJoint
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
     * Set titre
     *
     * @param string $titre
     * @return DocumentJoint
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
     * @return DocumentJoint
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
     * Set path
     *
     * @param string $path
     * @return DocumentJoint
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string 
     */
    public function getFileName()
    {
        return $this->id.'_'.$this->filename;
    }

    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->id.'_'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->id.'_'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return 'uploads/documents';
    }
}
