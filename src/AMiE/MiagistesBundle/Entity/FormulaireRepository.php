<?php

namespace AMiE\MiagistesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class FormulaireRepository extends EntityRepository
{
	public function searchAll()
	{
	    $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\MiagistesBundle\Entity\Formulaire', 'f');

        $query = 'SELECT f.* FROM Formulaire f WHERE 1 ';
		
		$request = $this->getEntityManager()->createNativeQuery($query, $rsm); 

        return $request;
	}

    public function search(FormulaireSearch $search)
    {
	
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\MiagistesBundle\Entity\Formulaire', 'f');

        $query = 'SELECT f.* FROM Formulaire f WHERE 1 ';
        if ($search->getNom() != null) {
            $query .= 'AND nom LIKE :nom';
        }
        if ($search->getPrenom() != null) {
            $query .= 'AND prenom LIKE :prenom';
        }
        if ($search->getEmbauche() != null) {
            $query .= 'AND embauche LIKE :embauche';
        }
        if ($search->getTypeContrat() != null) {
            $query .= 'AND typeContrat LIKE :typeContrat';
        }
        if ($search->getEntAccueil() != null) {
            $query .= 'AND entAccueil LIKE :entAccueil';
        }
        if ($search->getNiveauSalaireMin() != null and $search->getNiveauSalaireMax() != null) {
            $query .= 'AND niveauSalaire BETWEEN :niveauSalaireMin AND :niveauSalaireMax';
        } else if ($search->getNiveauSalaireMin() != null) {
            $query .= 'AND niveauSalaire >= :niveauSalaireMin';
        } else if ($search->getNiveauSalaireMax() != null) {
            $query .= 'AND niveauSalaire <= :niveauSalaireMax';
        }
        if ($search->getSecteur() != null) {
            $query .= 'AND secteur = :secteur ';
        }
        if ($search->getMetier() != null) {
            $query .= 'AND metier = :metier ';
        }

        $request = $this->getEntityManager()->createNativeQuery($query, $rsm);

        $request->setParameters(array('nom' => '%'.$search->getNom().'%', 'prenom' => '%'.$search->getPrenom().'%', 'embauche' => '%'.$search->getEmbauche().'%', 'typeContrat' => '%'.$search->getTypeContrat().'%', 'entAccueil' => '%'.$search->getEntAccueil().'%', 'niveauSalaireMin' => $search->getNiveauSalaireMin(), 'niveauSalaireMax' => $search->getNiveauSalaireMax(), 'secteur' => $search->getSecteur(), 'metier' => $search->getMetier()));

        return $request;
    }

    public function searchTypeContrat($type)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\MiagistesBundle\Entity\Formulaire', 'f');

        //   $query = 'SELECT count(*) FROM Formulaire f WHERE typeContrat = :type';
        //   $request = $this->getEntityManager()->createNativeQuery($query, $rsm);
        $query = 'SELECT count(f.typeContrat) FROM AMiE\MiagistesBundle\Entity\Formulaire f WHERE f.typeContrat = :type';
        $request = $this->getEntityManager()->createQuery($query);

        $request->setParameters(array('type' => $type));

        return $request;
    }

    public function searchEmbauche($reponse)
    {
        $query = 'SELECT count(f.embauche) FROM AMiE\MiagistesBundle\Entity\Formulaire f WHERE f.embauche = :reponse';
        $request = $this->getEntityManager()->createQuery($query);

        $request->setParameters(array('reponse' => $reponse));

        return $request;
    }

    public function searchEntrepriseAccueil($entreprise, $embauche)
    {
        $query = 'SELECT count(f.entAccueil) FROM AMiE\MiagistesBundle\Entity\Formulaire f WHERE f.entAccueil = :entreprise AND ';
        if ($embauche == 'Non') {
            $query .= '(f.embauche = :embauche OR (f.embauche = \'Oui\' AND f.entActuelle = :embauche))';
        } else {
            $query .= 'f.entActuelle = :embauche AND f.embauche = :embauche';
        }
        $request = $this->getEntityManager()->createQuery($query);

        $request->setParameters(array('embauche' => $embauche, 'entreprise' => $entreprise));

        return $request;
    }

    public function searchAllEntAccueil()
    {
        $query = 'SELECT distinct f.entAccueil FROM AMiE\MiagistesBundle\Entity\Formulaire f';
        $request = $this->getEntityManager()->createQuery($query);

        return $request;
    }

    public function searchSalaire($annee)
    {
        $query = 'SELECT avg(f.niveauSalaire) FROM AMiE\MiagistesBundle\Entity\Formulaire f WHERE f.anneePromo = :annee';
        $request = $this->getEntityManager()->createQuery($query);
        $request->setParameters(array('annee' => $annee));

        return $request;
    }

}