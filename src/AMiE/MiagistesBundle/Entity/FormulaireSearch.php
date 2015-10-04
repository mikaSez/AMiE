<?php
namespace AMiE\MiagistesBundle\Entity;

class FormulaireSearch
{
    protected $nom;
    protected $prenom;
    protected $embauche;
    protected $typeContrat;
    protected $entAccueil;
    protected $niveauSalaireMin;
    protected $niveauSalaireMax;
    protected $secteur;
    protected $metier;

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

    public function getEmbauche()
    {
        return $this->embauche;
    }

    public function setEmbauche($emb)
    {
        $this->embauche = $emb;
    }

    public function getTypeContrat()
    {
        return $this->typeContrat;
    }

    public function setTypeContrat($type)
    {
        $this->typeContrat = $type;
    }

    public function getEntAccueil()
    {

        return $this->entAccueil;
    }

    public function setEntAccueil($entAccueil)
    {
        $this->entAccueil = $entAccueil;
    }

    public function getNiveauSalaireMin()
    {

        return $this->niveauSalaireMin;
    }

    public function setNiveauSalaireMin($niveauSalaireMin)
    {
        $this->niveauSalaireMin = $niveauSalaireMin;
    }

    public function getNiveauSalaireMax()
    {

        return $this->niveauSalaireMax;
    }

    public function setNiveauSalaireMax($niveauSalaireMax)
    {
        $this->niveauSalaireMax = $niveauSalaireMax;
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

}