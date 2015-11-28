<?php
namespace AMiE\EntreprisesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Partenaire
 * @ORM\Entity(repositoryClass="AMiE\EntreprisesBundle\Entity\PartenaireRepository")
 * @ORM\Table(name="partenaire")
 */
class Partenaire
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
     * @ORM\Column(type="string", length=255)
     */
    protected $nom;  
	/**
     * @ORM\Column(type="string", length=255)
     */
    protected $url;


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
	
	public function getUrl()
	{
		return $this->url;
	}
	
	public function setUrl($url)
	{
		$this->url = $url;
	}
}
