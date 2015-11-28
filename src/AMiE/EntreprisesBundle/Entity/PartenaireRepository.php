<?php

namespace AMiE\EntreprisesBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

class PartenaireRepository extends EntityRepository
{
	public function searchAll()
	{
	    $rsm = new ResultSetMappingBuilder($this->getEntityManager());
        $rsm->addRootEntityFromClassMetadata('AMiE\EntreprisesBundle\Entity\Partenaire', 'p');

        $query = 'SELECT p.* FROM Partenaire p WHERE 1 ';
		
		$request = $this->getEntityManager()->createNativeQuery($query, $rsm); 

        return $request;
	}
}