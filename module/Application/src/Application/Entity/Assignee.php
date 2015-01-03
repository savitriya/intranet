<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Zend\Authentication\AuthenticationService;

/**
 * assignee
 *
 * @ORM\Entity 
 * @ORM\Table(name="assignee")
 * @property int $id
 * @property int $project_id 
 * @property int $milestone_id
 * @property int $user_id
 * @property int activity_id 
 */
/**
 * @ORM\Entity @ORM\HasLifecycleCallbacks
 */
class Assignee extends DomainObject
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
	protected $activity_id;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $user_id;
	
	protected $loggedin_user_id;
	
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
	 * @return Assignee
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
	 * @return Assignee
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
	 * @return Assignee
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
	 * @return Assignee
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
	 * Sets the Activity
	 *
	 * @param int activity_id
	 * @access public
	 * @return Assignee
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
	 * Sets the Current Logged-In User Id
	 *
	 * @param int $login_id
	 * @access public
	 * @return Assignee;
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
	
	/** @ORM\PostPersist */
    public function OnPostPersist()
    {
        $em = $this->getEntityManager();
        $newRecord = new Activityhistory();
        $auth = new AuthenticationService();
        $this->setLoginId($auth->getIdentity()->id);
        $loginId = $this->getLoginId();
        $userId = $this->getUser_id();
        $newRecord->setEntityManager($em);
        $userNameQuery = $em->createQuery("Select u.fname as fname, u.lname as lname from Application\Entity\User u where u.id = ".$userId);
        $userNameResult = $userNameQuery->getResult();
        $userNameResult = $userNameResult[0]['fname']." ".$userNameResult[0]['lname'];
		$newRecord->setCreated_date(strtotime(date("Y-m-d")));
		$newRecord->setCreated_time(strtotime(date("Y-m-d H:i:s")) - strtotime(date("Y-m-d")));
		$newRecord->setActivity_id($this->getActivity_id());
		$newRecord->setProject_id($this->getProject_id());
		$newRecord->setMilestone_id(NULL);
		$newRecord->setDescription("Assigned to user: ".$userNameResult);
		$newRecord->setActivity_by_id($loginId);
    	try{
			$em->persist($newRecord);
			$em->flush();
			//print_r($newRecord);exit;
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}
    }
    
    /** @ORM\PreRemove */
    public function OnPreRemove()
    {
    	$em = $this->getEntityManager();
        $newRecord = new Activityhistory();
        $auth = new AuthenticationService();
        $this->setLoginId($auth->getIdentity()->id);
        $loginId = $this->getLoginId();
        $userId = $this->getUser_id();
        $newRecord->setEntityManager($em);
        $userNameQuery = $em->createQuery("Select u.fname as fname, u.lname as lname from Application\Entity\User u where u.id = ".$userId);
        $userNameResult = $userNameQuery->getResult();
        $userNameResult = $userNameResult[0]['fname']." ".$userNameResult[0]['lname'];
		$newRecord->setCreated_date(strtotime(date("Y-m-d")));
		$newRecord->setCreated_time(strtotime(date("Y-m-d H:i:s")) - strtotime(date("Y-m-d")));
		$newRecord->setActivity_id($this->getActivity_id());
		$newRecord->setProject_id($this->getProject_id());
		$newRecord->setMilestone_id(NULL);
		$newRecord->setDescription($userNameResult." has been removed from this activity.");
		$newRecord->setActivity_by_id($loginId);
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