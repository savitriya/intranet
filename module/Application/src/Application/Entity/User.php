<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use	Doctrine\ORM\EntityManager;
use Application\Entity\DomainObject;
use Application\Repository\UserRepository;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @property int $id
 * @property string $employeeid
 * @property string $isactive
 * @property string $password
 * @property string $fname
 * @property string $lname
 * @property string $designation
 * @property int $mobile
 * @property string $dob
 * @property string $doy
 * @property int $isadmin
 * @property int $company_id
 * @property int $joiningdate
 * @property int $needdailyreport
 * @property int $needallocation
 * @property int $leavingdate
 * @property int $timing_slot_id
 */
class User  
{
	/**
	*@ORM\OneToMany(targetEntity="Login",mappedBy="user" )
	*/
	protected $login;
	
	protected $inputFilter;
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $employeeid;
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $email;
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $password;
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $fname;
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $lname;
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $designation;
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $mobile;	
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $dob;

	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $isactive;

	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $isadmin;	
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $company_id;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $joiningdate;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $needdailyreport;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $needallocation;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $leavingdate;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $timing_slot_id;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	
	public function __construct() {
		$this->login = new ArrayCollection();
	}
	
	
// 	/**
// 	 * Sets the Identifier
// 	 *
// 	 * @param int $id
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setId($id)
// 	{
// 		$this->id = $id;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the id
// 	 *
// 	 * @access public
// 	 * @return int
// 	 */
// 	public function getId()
// 	{
// 		return $this->id;
// 	}
	
// 	/**
// 	 * Sets the email
// 	 *
// 	 * @param string $email
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setEmail($email)
// 	{
// 		$this->email = $email;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the email
// 	 *
// 	 * @access public
// 	 * @return string
// 	 */
// 	public function getEmail()
// 	{
// 		return $this->email;
// 	}
	
// 	/**
// 	 * Sets the password
// 	 *
// 	 * @param string $password
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setPassword($password)
// 	{
// 		$this->password = $password;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the password
// 	 *
// 	 * @access public
// 	 * @return string
// 	 */
	
// 	public function getPassword()
// 	{
// 		return $this->password;
// 	}
	
// 	/**
// 	 * Sets the fname
// 	 *
// 	 * @param string $fname
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setFname($fname)
// 	{
// 		$this->fname = $fname;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the fname
// 	 *
// 	 * @access public
// 	 * @return string
// 	 */
// 	public function getFname()
// 	{
// 		return $this->fname;
// 	}
	
// 	/**
// 	 * Sets the lname
// 	 *
// 	 * @param string $lname
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setLname($lname)
// 	{
// 		$this->lname = $lname;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the lname
// 	 *
// 	 * @access public
// 	 * @return string
// 	 */
// 	public function getLname()
// 	{
// 		return $this->lname;
// 	}
	
// 	/**
// 	 * Sets the mobile
// 	 *
// 	 * @param int $mobile
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setMobile($mobile)
// 	{
// 		$this->mobile = $email;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the mobile
// 	 *
// 	 * @access public
// 	 * @return int
// 	 */
// 	public function getMobile()
// 	{
// 		return $this->mobile;
// 	}
// 	/**
// 	 * Sets the isactive
// 	 *
// 	 * @param int $isactive
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setIsactive($isactive)
// 	{
// 		$this->isactive = $isactive;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the isactive
// 	 *
// 	 * @access public
// 	 * @return int
// 	 */
// 	public function getIsactive()
// 	{
// 		return $this->isactive;
// 	}
	
// 	/**
// 	 * Sets the isadmin
// 	 *
// 	 * @param int $isadmin
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setIsadmin($isadmin)
// 	{
// 		$this->isadmin = $isadmin;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the isadmin
// 	 *
// 	 * @access public
// 	 * @return int
// 	 */
// 	public function getIsadmin()
// 	{
// 		return $this->isadmin;
// 	}
	
// 	/**
// 	 * Sets the dob
// 	 *
// 	 * @param string $dob
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setDob($dob)
// 	{
// 		$this->dob = $dob;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the dob
// 	 *
// 	 * @access public
// 	 * @return string
// 	 */
// 	public function getDob()
// 	{
// 		return $this->dob;
// 	}
	
// 	/**
// 	 * Sets the doy
// 	 *
// 	 * @param int $doy
// 	 * @access public
// 	 * @return user
// 	 */
// 	public function setDoy($doy)
// 	{
// 		$this->doy = $doy;
// 		return $this;
// 	}
	
// 	/**
// 	 * Returns the doy
// 	 *
// 	 * @access public
// 	 * @return int
// 	 */
// 	public function getDoy()
// 	{
// 		return $this->doy;
// 	}
	
	
	/**
	 * Magic getter to expose protected properties.
	 *
	 * @param string $property
	 * @return mixed
	 */
	
	public function __get($property)
	{
		return $this->$property;
	}
	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set($property, $value)
	{
		$this->$property = $value;
	}
	
	public function getLogin(){
		return $this->login;
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
	
//	/** @ORM\PostPersist */
	//public function OnPostPersist()
	//{
		//echo "IN POST PERSIST LIFE CYCLY CALLBACK";exit;
		//$em=$this->getEntityManager();
		//$newRecord = new Preferences();
		//$newRecord->setUser_id(1); //NOT DYNAMICALLY SETTING
		//$newRecord->setCc("");
		//$newRecord->setTomail("");
		//try{
			//$em->persist($newRecord);
			//$em->flush();
			//$newPrefId = $newRecord->getId();
			//echo $newPrefId;
		//}
		//catch (Exception $e)
		//{
			//echo $e->getMessage();
		//}
		//return $newPrefId;
//	}
	
	
}