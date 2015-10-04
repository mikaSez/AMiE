<?php
/**
 * Created by PhpStorm.
 * User: Alison
 * Date: 19/06/2015
 * Time: 23:05
 */

namespace AMiE\UserBundle\Entity;


class UserSearch {

    protected $nom;
    protected $siret;
    protected $ville;
    protected $secteur;

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getSiret()
    {
        return $this->siret;
    }

    public function setSiret($siret)
    {
        $this->siret = $siret;
    }

    public function getVille()
    {
        return $this->ville;
    }

    public function setVille($ville)
    {
        $this->ville = $ville;
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