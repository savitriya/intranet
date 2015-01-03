<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="resource_allocation")
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property int $duration
 * @property int $allocation_date
 */

class ResourceAllocation extends DomainObject 
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $user_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $project_id;
	
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $duration;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $allocation_date;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
		parent::setEntityManager($this->em);
	}
	
	public function getEntityManager()
	{
		return $this->em;
	}
	

	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return ResourceAllocation
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Returns the id
	 *
	 * @access public
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Sets the User_id
	 *
	 * @param int $user_id
	 * @access public
	 * @return ResourceAllocation
	 */
	public function setUser_id($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * Returns the User_id
	 *
	 * @access public
	 * @return int
	 */
	public function getUser_id()
	{
		return $this->user_id;
	}
	
	/**
	 * Sets the Project_id
	 *
	 * @param int $project_id
	 * @access public
	 * @return ResourceAllocation
	 */
	public function setProject_id($project_id)
	{
		$this->project_id = $project_id;
		return $this;
	}
	
	/**
	 * Returns the Project_id
	 *
	 * @access public
	 * @return int
	 */
	public function getProject_id()
	{
		return $this->project_id;
	}
	
	
	/**
	 * Sets the Duration
	 *
	 * @param int $duration
	 * @access public
	 * @return ResourceAllocation
	 */
	public function setDuration($duration)
	{
		$this->duration = $duration;
		return $this;
	}
	
	/**
	 * Returns the Duration
	 *
	 * @access public
	 * @return int
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	
	/**
	 * Sets the Allocation Date
	 *
	 * @param int $allocation_date
	 * @access public
	 * @return ResourceAllocation
	 */
	public function setAllocation_date($allocation_date)
	{
		$this->allocation_date = $allocation_date;
		return $this;
	}
	
	/**
	 * Returns the Allocation Date
	 *
	 * @access public
	 * @return int
	 */
	public function getAllocation_date()
	{
		return $this->allocation_date;
	}
	
}