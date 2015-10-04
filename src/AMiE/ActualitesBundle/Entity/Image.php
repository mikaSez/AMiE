<?php
namespace AMiE\ActualitesBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * @ORM\Entity(repositoryClass="AMiE\ActualitesBundle\Entity\ImageRepository")
 * @ORM\Table(name="image")
 * @ORM\HasLifecycleCallbacks
 */

class Image {

     /**
      * @ORM\Column(name="id", type="integer")
      * @ORM\Id
      * @ORM\GeneratedValue(strategy="AUTO")
      */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_actualite", type="integer")
     */
    private $idActualite;

    /**
     * @ORM\Column(name="titre", type="string", length=160, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
    * @ORM\Column(name="alt", type="string", length=60, nullable=true)
    */
    private $alt;

    /**
     * @Assert\File(maxSize="6000000", mimeTypes={"image/png", "image/jpeg", "image/gif"})
     */
    public $file;
  
    protected $filename;

    // propriété utilisé temporairement pour la suppression
    private $filenameForRemove;

    function __construct(){}

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (isset($this->file)){//(null !== $this->file) {
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
        if (empty($this->file)){//(null === $this->file) {
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
     * Set idActualite
     *
     * @param integer $idActualite
     * @return Image
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
     * Set titre
     *
     * @param string $titre
     * @return Image
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
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Image
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
     * Set file
     *
     * @param string $file
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
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
        return 'uploads/news';
    }

}