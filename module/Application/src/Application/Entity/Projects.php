<?php

namespace Application\Entity;

use IntranetUtils\Common;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Authentication\AuthenticationService;
use Application\Repository\ProjectsRepository;
use Application\Entity\DomainObject;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * A projects.
 *
 * @ORM\Entity 
 * @ORM\ChangeTrackingPolicy("NOTIFY")
 * @ORM\Table(name="projects")
 * @property int $id
 * @property int $companys
 * @property string $name
 * @property int $estimated_startdate
 * @property int $estimated_enddate
 * @property int $actual_startdate
 * @property int $actual_enddate
 * @property int $status
 * @property int $estimated_hours
 * @property int $bd
 * @property int $manager
 * @property int $coordinator
 * @property int $companys
 */
/** @ORM\Entity @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Application\Repository\ProjectsRepository")
 **/

class Projects extends DomainObject
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	
	/**
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $company_id;
	
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $name;
	
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $type_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $estimated_startdate;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
   protected  $estimated_enddate;
   /**
	 * @ORM\Column(type="integer",nullable=true)
	 */
   protected  $actual_startdate;
   
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
   protected  $actual_enddate;
   
   
	/**
	 * @ORM\Column(type="integer")
	 */
   protected  $status_id;
   /**
    * @ORM\Column(type="integer",nullable=true)
    */
   protected  $estimated_hours;
   
   /**
    * @ORM\Column(type="integer",nullable=true)
    */
   protected  $bd;
    
   /**
    * @ORM\Column(type="integer",nullable=true)
    */
   protected  $manager;
    
   /**
    * @ORM\Column(type="integer",nullable=true)
    */
   protected  $coordinator;
    
   
   
   
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
	 * @return projects
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
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return projects
	 */
	public function setCompany_id($CompanyId)
	{
		$this->company_id = $CompanyId;
		return $this;
	}
	/**
	 * Returns the id
	 *
	 * @access public
	 * @return int
	 */
	public function getCompany_id()
	{
		return $this->company_id;
	}
	
	
	
	
	/**
	 * Sets the name
	 *
	 * @param string $name
	 * @access public
	 * @return Projects
	 */
	public function setName($name)
	{
		if ($name != $this->name) {
            $this->_onPropertyChanged('name', $this->name, $name);
			$this->name = $name;
		}
		return $this;
	}
	/**
	 * Returns the name
	 *
	 * @access public
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}
	/**
	 * Sets the estimated_startdate
	 *
	 * @param int $estimated_startdate
	 * @access public
	 * @return login
	 */
	public function setEstimated_startdate($estimated_startdate)
	{
		$this->estimated_startdate = $estimated_startdate;
		return $this;
	}
	/**
	 * Returns the estimated_startdate
	 *
	 * @access public
	 * @return int
	 */
	public function getEstimated_startdate()
	{
		return $this->estimated_startdate;
	}
	/**
	 * Sets the estimated_enddate
	 *
	 * @param int $estimated_enddate
	 * @access public
	 * @return login
	 */
	public function setEstimated_enddate($estimated_enddate)
	{
		$this->estimated_enddate = $estimated_enddate;
		return $this;
	}
	/**
	 * Returns the estimated_enddate
	 *
	 * @access public
	 * @return int
	 */
	public function getEstimated_enddate()
	{
		return $this->estimated_enddate;
	}
	/**
	 * Sets the actual_startdate
	 *
	 * @param int $actual_startdate
	 * @access public
	 * @return Post
	 */
	public function setActual_startdate($actual_startdate)
	{
		$this->actual_startdate = $actual_startdate;
		return $this;
	}
	/**
	 * Returns the actual_startdate
	 *
	 * @access public
	 * @return int
	 */
	public function getActual_startdate()
	{
		return $this->actual_startdate;
	}
     /**
	 * Sets the actual_enddate
	 *
	 * @param int $actual_enddate
	 * @access public
	 * @return Post
	 */
	public function setActual_enddate($actual_enddate)
	{
		$this->actual_enddate = $actual_enddate;
		return $this;
	}
	/**
	 * Returns the actual_enddate
	 *
	 * @access public
	 * @return int
	 */
	public function getActual_enddate()
	{
		return $this->actual_enddate;
	}
	/**
	 * Sets the status
	 *
	 * @param int $status_id
	 * @access public
	 * @return Post
	 */
	public function setStatus_id($statusId)
	{
		$this->status_id = $statusId;
		return $this;
	}
	/**
	 * Returns the status
	 *
	 * @access public
	 * @return int
	 */
	public function getStatus_id()
	{
		return $this->status_id;
	}
	/**
	 * Sets the type
	 *
	 * @param int $type_id
	 * @access public
	 * @return Post
	 */
	public function setType_id($typeId)
	{
		if ($typeId != $this->type_id) {
            $this->_onPropertyChanged('typeId', $this->type_id, $typeId);
			$this->type_id = $typeId;
		}
		return $this;
	}
	/**
	 * Returns the type
	 *
	 * @access public
	 * @return int
	 */
	public function getType_id()
	{
		return $this->type_id;
	}
	/**
	 * Sets the estimated_hours
	 *
	 * @param int $estimated_hours
	 * @access public
	 * @return Projects
	 */
	
	public function setEstimated_hours($estimated_hours)
	{
		if ($estimated_hours != $this->estimated_hours) {
            $this->_onPropertyChanged('project_estimated_hours', $this->estimated_hours, $estimated_hours);
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
	 * Sets the bd
	 *
	 * @param int $bd
	 * @access public
	 * @return Post
	 */
	public function setBd($bd)
	{
		$this->bd = $bd;
		return $this;
	}
	/**
	 * Returns the bd
	 *
	 * @access public
	 * @return int
	 */
	public function getBd()
	{
		return $this->bd;
	}
	
	
	/**
	 * Sets the Escalation Manager
	 *
	 * @param int $manager
	 * @access public
	 * @return Post
	 */
	public function setManager($manager)
	{
		$this->manager = $manager;
		return $this;
	}
	/**
	 * Returns the mentor
	 *
	 * @access public
	 * @return int
	 */
	public function getManager()
	{
		return $this->manager;
	}

	/**
	 * Sets the coordinator
	 *
	 * @param int $cooredinator
	 * @access public
	 * @return Post
	 */
	public function setCoordinator($coordinator)
	{
		$this->coordinator = $coordinator;
		return $this;
	}
	/**
	 * Returns the coordinator
	 *
	 * @access public
	 * @return int
	 */
	public function getCoordinator()
	{
		return $this->coordinator;
	}
	
	/**
	 * Sets the Current Logged-In User Id
	 *
	 * @param int $loggedin_user_id
	 * @access public
	 * @return Projects
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
    	$ph_desc = parent::getDescription();
    	if(strlen($ph_desc) > 1)
    	{
	        $em = $this->getEntityManager();
	        $newRecord = new Projecthistory();
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
			$newRecord->setProject_id($this->getId());
			$newRecord->setDescription($ph_desc);
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