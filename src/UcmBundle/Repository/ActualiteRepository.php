<?php

namespace UcmBundle\Repository;

use UcmBundle\Entity\Actualite;

/**
 * ActualiteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActualiteRepository extends \Doctrine\ORM\EntityRepository
{
	public function getActualities(){
		$query = $this->createQueryBuilder('a')
			->select('a')
			->where('a.estactif = 1')
			->orderBy('a.id', 'DESC')
			->setMaxresults(2)
			;
			
			return $query->getQuery()->getResult();
	}
	
	public function rechercheActualites($param){
		
		$qb = $this->createQueryBuilder('a')
		         ->Where('a.titre = :titre')
				 ->setParameter('titre', $param)
				 ->getQuery();
				 
				 return $qb->execute();
	}
}