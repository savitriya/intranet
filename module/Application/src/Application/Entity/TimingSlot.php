<?php
namespace Application\Entity;
use Application\Entity\Timingslot;
use IntranetUtils\Common;
use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;

/**
 * timing slot
 *
 * @ORM\Entity
 * @ORM\Table(name="timing_slot")
 * @ORM\Entity(repositoryClass="Application\Repository\TimingslotRepository") 
 * @property int $slot_id
 * @property int $slot_name
 * @property int $slot_login_time
 */

class Timingslot{
	/**	 
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $slot_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $slot_name;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $slot_login_time;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	/**
	 * Sets the Identifier
	 *
	 * @param int $slot_id
	 * @access public
	 * @return Timingslot
	 */
	public function setSlotId($id)
	{
		$this->slot_id = $id;
		return $this;
	}
	
	/**
	 * Returns the slot_id
	 *
	 * @access public
	 * @return int
	 */
	public function getSlotId()
	{
		return $this->slot_id;
	}
	
	/**
	 * Sets the slot_name
	 *
	 * @param string $slot_name
	 * @access public
	 * @return Timingslot
	 */
	public function setSlotName($name)
	{
		$this->slot_name = $name;
		return $this;
	}
	
	/**
	 * Returns the slot_name
	 *
	 * @access public
	 * @return string
	 */
	public function getSlotName()
	{
		return $this->slot_name;
	}
	
	/**
	 * Sets the slot_login_time
	 *
	 * @param string $slot_login_time
	 * @access public
	 * @return Timingslot
	 */
	public function setSlotLoginTime($slot_login_time)
	{
		$this->slot_login_time = $slot_login_time;
		return $this;
	}
	
	/**
	 * Returns the slot_login_time
	 *
	 * @access public
	 * @return int
	 */
	public function getSlotLoginTime()
	{
		return $this->slot_login_time;
	}
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
		parent::setEntityManager($this->em);
	}
	
	public function getEntityManager()
	{
		return $this->em;
	}
}