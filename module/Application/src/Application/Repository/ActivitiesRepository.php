<?php

namespace Application\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActivitiesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActivitiesRepository extends EntityRepository
{
	function getActivityById($activityid){
		$em=$this->getEntityManager();
		$activity=$em->createQuery("Select a from Application\Entity\Activities a where a.id= $activityid")->getArrayResult();
	return $activity;
	}
}
