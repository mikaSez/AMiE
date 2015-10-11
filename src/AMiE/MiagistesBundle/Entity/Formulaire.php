<?php
namespace AMiE\MiagistesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formulaire
 * @ORM\Entity(repositoryClass="AMiE\MiagistesBundle\Entity\FormulaireRepository")
 * @ORM\Table(name="formulaire")
 */
class Formulaire
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $prenom;
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nom;    /**
     * @ORM\Column(type="date")
     */
    protected $dateNaissance;
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
     * @ORM\Column(type="string", length=120)
     */
    protected $mail;
    /**
     * @ORM\Column(type="string", length=60)
     */
    protected $entAccueil;
    /**
     * @ORM\Column(type="string", length=4)
     */
    protected $anneePromo;
    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    protected $entrepriseAutre;
    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $typeContrat;
    /**
     * @ORM\Column(type="string", length=65, nullable=true)
     */
    protected $typeContratAutre;
    /**
     * @ORM\Column(type="string", length=3)
     */
    protected $embauche;
    /**
     * @ORM\Column(type="string", length=3, nullable=true)
     */
    protected $entActuelle;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $raisonNonEmbauche;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $niveauSalaire;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $situationNonEmbauche;
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
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $raisonAutre;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $situationAutre;
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId()
    {

        return $this->id;
    }

    public function getPrenom()
    {

        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getMail()
    {

        return $this->mail;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function getNom()
    {

        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }
	
	public function getDateNaissance()
    {

        return $this->dateNaissance;
    }

    public function setDateNaissance($date)
    {
        $this->dateNaissance = $date;
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

    public function getAnneePromo()
    {

        return $this->anneePromo;
    }

    public function setAnneePromo($anneePromo)
    {
        $this->anneePromo = $anneePromo;
    }

    public function getEntrepriseAutre()
    {

        return $this->entrepriseAutre;
    }

    public function setEntrepriseAutre($entrepriseAutre)
    {
        $this->entrepriseAutre = $entrepriseAutre;
    }

    public function getTypeContrat()
    {

        return $this->typeContrat;
    }

    public function setTypeContrat($typeContrat)
    {
        $this->typeContrat = $typeContrat;
    }

    public function getTypeContratAutre()
    {

        return $this->typeContratAutre;
    }

    public function setTypeContratAutre($typeContratAutre)
    {
        $this->typeContratAutre = $typeContratAutre;
    }

    public function getEntActuelle()
    {

        return $this->entActuelle;
    }

    public function setEntActuelle($entActuelle)
    {
        $this->entActuelle = $entActuelle;
    }

    public function getEntAccueil()
    {

        return $this->entAccueil;
    }

    public function setEntAccueil($entAccueil)
    {
        $this->entAccueil = $entAccueil;
    }

    public function getRaisonNonEmbauche()
    {

        return $this->raisonNonEmbauche;
    }

    public function setRaisonNonEmbauche($raisonNonEmbauche)
    {
        $this->raisonNonEmbauche = $raisonNonEmbauche;
    }

    public function getNiveauSalaire()
    {

        return $this->niveauSalaire;
    }

    public function setNiveauSalaire($niveauSalaire)
    {
        $this->niveauSalaire = $niveauSalaire;
    }

    public function getSituationNonEmbauche()
    {

        return $this->situationNonEmbauche;
    }

    public function setSituationNonEmbauche($situationNonEmbauche)
    {
        $this->situationNonEmbauche = $situationNonEmbauche;
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

    public function getEmbauche()
    {

        return $this->embauche;
    }

    public function setEmbauche($embauche)
    {
        $this->embauche = $embauche;
    }

    public function getRaisonAutre()
    {

        return $this->raisonAutre;
    }

    public function setRaisonAutre($raisonAutre)
    {
        $this->raisonAutre = $raisonAutre;
    }

    public function getSituationAutre()
    {

        return $this->situationAutre;
    }

    public function setSituationAutre($situationAutre)
    {
        $this->situationAutre = $situationAutre;
    }
}
