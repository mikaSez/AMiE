<?php
namespace AMiE\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AMiE\UserBundle\Entity\UserRepository")
 * @ORM\Table(name="utilisateurs")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $prenom;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     */
    protected $siret;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $typeUt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $rue;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $codePostal;

    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $ville;

    /**
     * @ORM\Column(type="string", length=15)
     */
    protected $numero;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    protected $metier;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    protected $metAutre;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    protected $secteur;

    /**
     * @ORM\Column(type="string", length=120, nullable=true)
     */
    protected $sectAutre;

    public function __construct()
    {
        parent::__construct();

    }

    public function getId()
    {
        return $this->id;
    }

    public function getNom()
    {

        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {

        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getTypeUt()
    {

        return $this->typeUt;
    }

    public function setTypeUt($type)
    {
        $this->typeUt = $type;
        if($type == 'Etudiant')
        {
            $this->roles = array('ROLE_ADMIN_ETU');
        }
        elseif($type == 'Entreprise')
        {
            $this->roles = array('ROLE_ADMIN_ENT');
        }
        elseif($type == 'Responsable')
        {
            $this->roles = array('ROLE_SUPER_ADMIN');
        }
    }

    public function getSiret()
    {

        return $this->siret;
    }

    public function setSiret($siret)
    {
        $this->siret = $siret;
    }

    public function getRue()
    {

        return $this->rue;
    }

    public function setRue($rue)
    {
        $this->rue = $rue;
    }

    public function getCodePostal()
    {

        return $this->codePostal;
    }

    public function setCodePostal($cp)
    {
        $this->codePostal = $cp;
    }

    public function getVille()
    {

        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    public function getNumero()
    {

        return $this->numero;
    }

    public function setNumero($num)
    {
        $this->numero = $num;
    }

    public function getMetier()
    {

        return $this->metier;
    }

    public function setMetier($metier)
    {
        $this->metier = $metier;
    }

    public function getSecteur()
    {

        return $this->secteur;
    }

    public function setSecteur($secteur)
    {
        $this->secteur = $secteur;
    }

    public function getSectAutre()
    {

        return $this->sectAutre;
    }

    public function setSectAutre($sectAutre)
    {
        $this->sectAutre = $sectAutre;
    }

    public function getMetAutre()
    {

        return $this->metAutre;
    }

    public function setMetAutre($metAutre)
    {
        $this->metAutre = $metAutre;
    }
}

?>