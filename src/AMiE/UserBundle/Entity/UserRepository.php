<?php

namespace AMiE\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class UserRepository extends EntityRepository
{
    public function search(UserSearch $search)
    {

        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\UserBundle\Entity\User', 'u');

        $query = 'SELECT u.* FROM Utilisateurs u WHERE 1';
        if ($search->getNom() != null) {
            $query .= ' AND u.nom LIKE :nom';
        }
        if ($search->getSiret() != null) {
            $query .= ' AND u.siret LIKE :siret';
        }
        if ($search->getVille() != null) {
            $query .= ' AND u.ville LIKE :ville';
        }
        if ($search->getSecteur() != null) {
            $query .= ' AND u.secteur IN (:secteur)';
        }

        $query .= ' AND u.typeUt = \'Entreprise\'';

        $request = $this->getEntityManager()->createNativeQuery($query, $rsm);

        $request->setParameters(array('nom' => '%'.$search->getNom().'%', 'siret' => '%'.$search->getSiret().'%', 'ville' => '%'.$search->getVille().'%', 'secteur' => $search->getSecteur()));

        return $request;
    }

    public function searchAllEntreprises()
    {
        $query = 'SELECT u FROM AMiE\UserBundle\Entity\User u WHERE u.typeUt = \'Entreprise\'';
        $request = $this->getEntityManager()->createQuery($query);

        return $request;
    }

    public function searchAllUsers()
    {
        $query = 'SELECT u FROM AMiE\UserBundle\Entity\User u ORDER BY u.typeUt DESC';
        $request = $this->getEntityManager()->createQuery($query);

        return $request;
    }

    public function searchSecteur($secteur)
    {
        $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\UserBundle\Entity\User', 'u');

        $query = 'SELECT count(u.secteur) FROM AMiE\UserBundle\Entity\User u WHERE u.secteur = :secteur AND u.typeUt = \'Entreprise\'';
        $request = $this->getEntityManager()->createQuery($query);

        $request->setParameters(array('secteur' => $secteur));

        return $request;
    }

    public function searchNomEntreprises()
    {
        $query = 'SELECT u.nom FROM AMiE\UserBundle\Entity\User u WHERE u.typeUt = \'Entreprise\'';
        $request = $this->getEntityManager()->createQuery($query);

        return $request;
    }
}
