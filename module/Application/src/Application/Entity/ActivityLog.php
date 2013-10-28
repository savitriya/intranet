<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Application\Entity\DomainObject;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * A Activity_log table.
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\ActivityLogRepository")
 * @ORM\Table(name="activity_log")
 * @property int $id
 * @property int $user_id
 * @property int $project_id
 * @property int $milestone_id
 * @property int $activity_id
 * @property int $category_id
 * @property string $description
 * @property integer $created_datetime
 * @property integer $activity_date
 * @property integer $second_spent
 
 */

class ActivityLog extends DomainObject  

{
	// ...
		
	
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $project_id;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $milestone_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $user_id;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $activity_id;
	/**
	 * @ORM\Column(type="text")
	 */
	protected  $description;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $created_datetime;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $activity_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $seconds_spent;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $category_id;
	
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
	 * @return activity_log
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
	 * Sets the user_id
	 *
	 * @param integer $user_id
	 * @access public
	 * @return Activity_log
	 */
	public function setUser_id($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * Returns the $user_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getUser_id()
	{
		return $this->user_id;
	}
	/**
	 * Sets the project_id
	 *
	 * @param integer $project_id
	 * @access public
	 * @return Activity_log
	 */
	public function setProject_id($project_id)
	{
		$this->project_id = $project_id;
		return $this;
	}
	
	/**
	 * Returns the project_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getProject_id()
	{
		return $this->project_id;
	}
	/**
	 * Sets the milestone_id
	 *
	 * @param integer $milestone_id
	 * @access public
	 * @return Activity_log
	 */
	public function setMilestone_id($milestone_id)
	{
		$this->milestone_id = $milestone_id;
		return $this;
	}
	
	/**
	 * Returns the milestone_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getMilestone_id()
	{
		return $this->milestone_id;
	}
	
	/**
	 * Sets the activity_id
	 *
	 * @param integer $activity_id
	 * @access public
	 * @return Activity_log
	 */
	public function setActivity_id($activity_id)
	{
		$this->activity_id = $activity_id;
		return $this;
	}
	
	/**
	 * Returns the activity_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getActivity_id()
	{
		return $this->activity_id;
	}
	/**
	 * Sets the category_id
	 *
	 * @param integer $category_id
	 * @access public
	 * @return Activity_log
	 */
	public function setCategory_id($category_id)
	{
		$this->category_id = $category_id;
		return $this;
	}
	
	/**
	 * Returns the category_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getCategory_id()
	{
		return $this->category_id;
	}
	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @access public
	 * @return Post
	 */
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}
	
	/**
	 * Returns the description
	 *
	 * @access public
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}
	/**
	 * Sets the created_datetime
	 *
	 * @param integer $created_datetime
	 * @access public
	 * @return Post
	 */
	public function setCreated_datetime($created_datetime)
	{
		$this->created_datetime = $created_datetime;
		return $this;
	}
	
	/**
	 * Returns the created_datetime
	 *
	 * @access public
	 * @return integer
	 */
	public function getCreated_datetime()
	{
		return $this->created_datetime;
	}
	
	/**
	 * Sets the activity_date
	 *
	 * @param integer $activity_date
	 * @access public
	 * @return Post
	 */
	public function setActivity_date($activity_date)
	{
		$this->activity_date = $activity_date;
		return $this;
	}
	
	/**
	 * Returns the activity_date
	 *
	 * @access public
	 * @return integer
	 */
	public function getActivity_date()
	{
		return $this->activity_date;
	}
	
	/**
	 * Sets the seconds_spent
	 *
	 * @param integer $seconds_spent
	 * @access public
	 * @return Post
	 */
	public function setSeconds_spent($seconds_spent)
	{
		$this->seconds_spent = $seconds_spent;
		return $this;
	}
	
	/**
	 * Returns the seconds_spent
	 *
	 * @access public
	 * @return integer
	 */
	public function getSeconds_spent()
	{
		return $this->seconds_spent;
	}
	
	
}
	
	
	
	
	
