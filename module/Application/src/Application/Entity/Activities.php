<?php
namespace Application\Entity;

use IntranetUtils\Common;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Application\Entity\Activityhistory;
use Zend\Authentication\AuthenticationService;

/**
 * activities
 *
 * @ORM\Entity
 * @ORM\Table(name="activities") 
 * @ORM\ChangeTrackingPolicy("NOTIFY") 
 * @property int $created_date
 * @property int $created_time
 * @property string $description
 * @property int $due_time
 * @property int $due_date
 * @property int $status_id
 * @property int $assigned_to_id
 * @property int $user_id
 * @property int $priority_id
 * @property string $subject
 * @property int $category_id
 * @property int $milestone_id
 * @property int $project_id
 * @property int $id
 */
/** @ORM\Entity @ORM\HasLifecycleCallbacks 
  * @ORM\Entity(repositoryClass="Application\Repository\ActivitiesRepository")
 * */
 class Activities extends DomainObject
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
	 * @ORM\Column(type="integer")
	 */
	protected $category_id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $subject;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $priority_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $user_id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $assigned_to_id;
	
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $status_id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $due_date;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $due_time	;
	
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_date	;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_time	;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $estimated_hours;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $milestone_id;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $loggedin_user_id;
	
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
	 * @return Activities
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
	 * @return Activities
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
	 * @return Activities
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
	 * Sets the Category
	 *
	 * @param int $category_id
	 * @access public
	 * @return Activities
	 */
	public function setCategory_id($category_id)
	{
		if ($category_id != $this->category_id) {
            $this->_onPropertyChanged('category_id', $this->category_id, $category_id);
			$this->category_id = $category_id;
		}
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
	 * Sets the Subject
	 *
	 * @param string $subject
	 * @access public
	 * @return Activities
	 */
	public function setSubject($subject)
	{
		if ($subject != $this->subject) {
            $this->_onPropertyChanged('subject', $this->subject, $subject);
			$this->subject = $subject;
		}
		return $this;
	}
	
	/**
	 * Returns the subject
	 *
	 * @access public
	 * @return string
	 */
	public function getSubject()
	{
		return $this->subject;
	}
	
	
	/**
	 * Sets the Priority
	 *
	 * @param int $priority_id
	 * @access public
	 * @return Activities
	 */
	public function setPriority_id($priority_id)
	{
		if ($priority_id != $this->priority_id) {
            $this->_onPropertyChanged('priority_id', $this->priority_id, $priority_id);
			$this->priority_id = $priority_id;
		}
		return $this;
	}
	
	/**
	 * Returns the priority_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getPriority_id()
	{
		return $this->priority_id;
	}
	
	/**
	 * Sets the User
	 *
	 * @param int $user_id
	 * @access public
	 * @return Activities
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
	
	/**
	 * Sets the Assigned User
	 *
	 * @param int $assigned_to_id
	 * @access public
	 * @return Activities
	 */
	public function setAssigned_to_id($assigned_to_id)
	{
		if ($assigned_to_id != $this->assigned_to_id) {
            $this->_onPropertyChanged('assigned_to_id', $this->assigned_to_id, $assigned_to_id);
			$this->assigned_to_id = $assigned_to_id;
		}
		return $this;
	}
	
	/**
	 * Returns the assigned_to_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getAssigned_to_id()
	{
		return $this->assigned_to_id;
	}
	
	/**
	 * Sets the Status
	 *
	 * @param int $status_id
	 * @access public
	 * @return Activities
	 */
	public function setStatus_id($status_id)
	{
		if ($status_id != $this->status_id) {
            $this->_onPropertyChanged('status_id', $this->status_id, $status_id);
			$this->status_id = $status_id;
		}
		return $this;
	}
	
	/**
	 * Returns the status_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getStatus_id()
	{
		return $this->status_id;
	}
	
	/**
	 * Sets the Due Date
	 *
	 * @param int $due_date
	 * @access public
	 * @return Activities
	 */
	public function setDue_date($due_date)
	{
		if ($due_date != $this->due_date) {
            $this->_onPropertyChanged('due_date', $this->due_date, $due_date);
			$this->due_date = $due_date;
		}
		return $this;
	}
	
	/**
	 * Returns the due_date
	 *
	 * @access public
	 * @return integer
	 */
	public function getDue_date()
	{
		return $this->due_date;
	}
	
	
	/**
	 * Sets the Due Time
	 *
	 * @param int $due_time
	 * @access public
	 * @return Activities
	 */
	public function setDue_time($due_time)
	{
		$this->due_time = $due_time;
		return $this;
	}
	
	/**
	 * Returns the due_time
	 *
	 * @access public
	 * @return integer
	 */
	public function getDue_time()
	{
		return $this->due_time;
	}
	
	/**
	 * Sets the Description
	 *
	 * @param int $description
	 * @access public
	 * @return Activities
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
	 * @return integer
	 */
	public function getDescription()
	{
		return $this->description;
	}
	
	
	/**
	 * Sets the Created Date
	 *
	 * @param int $created_date
	 * @access public
	 * @return Activities
	 */
	public function setCreated_date($created_date)
	{
		$this->created_date = $created_date;
		return $this;
	}
	
	/**
	 * Returns the created_date
	 *
	 * @access public
	 * @return integer
	 */
	public function getCreated_date()
	{
		return $this->created_date;
	}
	
	/**
	 * Sets the Created Time
	 *
	 * @param int $created_time
	 * @access public
	 * @return Activities
	 */
	public function setCreated_time($created_time)
	{
		$this->created_time = $created_time;
		return $this;
	}
	
	/**
	 * Returns the created_time
	 *
	 * @access public
	 * @return integer
	 */
	public function getCreated_time()
	{
		return $this->created_time;
	}
	
	/**
	 * Sets the estimated_hours
	 *
	 * @param int $estimated_hours
	 * @access public
	 * @return Post
	 */
	public function setEstimated_hours($estimated_hours)
	{
		if ($estimated_hours != $this->estimated_hours) {
            $this->_onPropertyChanged('estimated_hours', $this->estimated_hours, $estimated_hours);
			$this->estimated_hours = $estimated_hours;
		}
		return $this;
	}
	
	/**
	 * Returns the estimated_hours
	 *
	 * @access public
	 * @return int
	 */
	public function getEstimated_hours()
	{
		return $this->estimated_hours;
	}
	
	/**
	 * Sets the Current Logged-In User Id
	 *
	 * @param int $login_id
	 * @access public
	 * @return Activities
	 */
	public function setLoginId($loggedin_user_id)
	{
		$this->loggedin_user_id = $loggedin_user_id;
	}
	
	/**
	 * Returns the Current Logged-In User Id
	 *
	 * @access public
	 * @return int
	 */
	public function getLoginId()
	{
		return $this->loggedin_user_id;
	}
	
	/** @ORM\PostUpdate */
    public function OnPostUpdate()
    {
    	$ah_desc = parent::getDescription();
    	if(strlen($ah_desc) > 1)
    	{
	        $em = $this->getEntityManager();
	        $newRecord = new Activityhistory();
	        $auth = new AuthenticationService();
	        $this->setLoginId($auth->getIdentity()->id);
	        $loginid = $this->getLoginId();
	        $common=new Common();
	        $offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
	        $duedateInYmd=date("Y-m-d",strtotime(date("Y-m-d H:i:s")));
	        $created_date=strtotime($duedateInYmd)+$offset;
	        $created_time=strtotime(date("Y-m-d H:i:s"))-$created_date;
	        $newRecord->setCreated_date($created_date);
			$newRecord->setCreated_time($created_time);
			$newRecord->setActivity_id($this->getId());
			$newRecord->setProject_id($this->getProject_id());
			$newRecord->setMilestone_id(NULL);
			$newRecord->setDescription($ah_desc);
			$newRecord->setActivity_by_id($loginid);
	    	try{
				$em->persist($newRecord);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
    	}
    }
}