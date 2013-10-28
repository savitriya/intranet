<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;

/**
 * activityhistory
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\ActivityhistoryRepository")
 * @ORM\Table(name="activityhistory")
 * @property integer $id
 * @property integer $activity_id
 * @property integer $project_id
 * @property integer $milestone_id
 * @property text $description
 * @property integer $created_date
 * @property integer $created_time
 * @property integer $activity_by_id
 */

class Activityhistory extends DomainObject
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
	protected $activity_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $project_id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $milestone_id;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_time;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $activity_by_id;
	
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
	 * Sets the Activity_id
	 *
	 * @param int $activity_id
	 * @access public
	 * @return Activityhistory
	 */
	public function setActivity_id($activity_id)
	{
		$this->activity_id = $activity_id;
		return $this;
	}
	
	/**
	 * Returns the Activity_id
	 *
	 * @access public
	 * @return int
	 */
	public function getActivity_id()
	{
		return $this->activity_id;
	}
	
	/**
	 * Sets the Project_id
	 *
	 * @param int $project_id
	 * @access public
	 * @return Activityhistory
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
	 * Sets the Milestone_id
	 *
	 * @param int $milestone_id
	 * @access public
	 * @return Activityhistory
	 */
	public function setMilestone_id($milestone_id)
	{
		$this->milestone_id = $milestone_id;
		return $this;
	}
	
	/**
	 * Returns the Milestone_id
	 *
	 * @access public
	 * @return int
	 */
	public function getMilestone_id()
	{
		return $this->milestone_id;
	}
	
	/**
	 * Sets the Description
	 *
	 * @param string $description
	 * @access public
	 * @return Activityhistory
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Returns the Description
	 *
	 * @access public
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	/**
	 * Sets the Created_date
	 *
	 * @param int $created_date
	 * @access public
	 * @return Activityhistory
	 */
	public function setCreated_date($created_date)
	{
		$this->created_date = $created_date;
		return $this;
	}
	
	/**
	 * Returns the Created_date
	 *
	 * @access public
	 * @return int
	 */
	public function getCreated_date()
	{
		return $this->created_date;
	}
	
	/**
	 * Sets the Created_time
	 *
	 * @param int $created_time
	 * @access public
	 * @return Activityhistory
	 */
	public function setCreated_time($created_time)
	{
		$this->created_time = $created_time;
		return $this;
	}
	
	/**
	 * Returns the Created_time
	 *
	 * @access public
	 * @return int
	 */
	public function getCreated_time()
	{
		return $this->created_time;
	}
	
	/**
	 * Sets the Assigned_to_id
	 *
	 * @param int $assigned_to_id
	 * @access public
	 * @return Activityhistory
	 */
	public function setAssigned_to_id($assigned_to_id)
	{
		$this->assigned_to_id = $assigned_to_id;
		return $this;
	}
	
	/**
	 * Returns the Assigned_to_id
	 *
	 * @access public
	 * @return int
	 */
	public function getAssigned_to_id()
	{
		return $this->assigned_to_id;
	}
	
	/**
	 * Sets the Activity_by_id
	 *
	 * @param int $activity_by_id
	 * @access public
	 * @return Activityhistory
	 */
	public function setActivity_by_id($activity_by_id)
	{
		$this->activity_by_id = $activity_by_id;
		return $this;
	}
	
	/**
	 * Returns the Activity_by_id
	 *
	 * @access public
	 * @return int
	 */
	public function getActivity_by_id()
	{
		return $this->activity_by_id;
	}
}