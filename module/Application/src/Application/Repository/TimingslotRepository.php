<?php
namespace Application\Repository;
use Doctrine\ORM\EntityRepository;
use Entities;
use Application\Entity\Timingslot;

/**
 * TimingslotRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TimingslotRepository extends EntityRepository
{
	public function getTimingSlotById($id){
		$em = $this->getEntityManager();
		$timingSlot = $em->createQuery("Select * from Application\Entity\Timingslot ts Where ts.slot_id = $id")->getArrayResult();
		return $timingSlot;
	}
}