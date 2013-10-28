<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Zend\Authentication\AuthenticationService;

/**
 * milestones_assignee
 *
 * @ORM\Entity
 * @ORM\Entity @ORM\HasLifecycleCallbacks 
 * @ORM\Table(name="milestones_assignee")
 * @property int $id
 * @property int $project_id 
 * @property int $milestone_id
 * @property int $user_id
 */


class MilestonesAssignee 
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
	protected $project_id;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $milestone_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $user_id;
	
	protected $loggedin_user_id;
	
	protected $em;	
	
	
	
	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return MilestonesAssignee
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
	 * Sets the Project
	 *
	 * @param int $project_id
	 * @access public
	 * @return MilestonesAssignee
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
	 * Sets the Milestone
	 *
	 * @param int $milestone_id
	 * @access public
	 * @return MilestonesAssignee
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
	 * Sets the User
	 *
	 * @param int $user_id
	 * @access public
	 * @return MilestonesAssignee
	 */
	public function setUser_id($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * Returns the user_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getUser_id()
	{
		return $this->user_id;
	}
	
	
}