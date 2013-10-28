<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\User;

/**
 * A login table.
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\LoginRepository")
 * @ORM\Table(name="login")
 * @property int $id
 * @property int $user_id
 * @property integer $logintime
 * @property integer $logouttime
 * @property string $ipaddress
 * @property integer $created_date
 * @property integer $created_time
 * @property integer $created_time
 * @property integer $loggedinby
 * @property integer $loggedoutby
 */

class Login  

{
	// ...
		
	/**
	 * @ORM\ManyToOne(targetEntity="User",inversedBy="login")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	private $user;
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $user_id;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $logintime;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $logouttime;
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $ipaddress;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $created_date;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $created_time;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */

	protected  $loggedinby;
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $loggedoutby;
	
	
	
	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return login
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
	 * @return login
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
	 * Sets the logintime
	 *
	 * @param integer $logintime
	 * @access public
	 * @return login
	 */
	public function setLogintime($logintime)
	{
		$this->logintime = $logintime;
		return $this;
	}
	
	/**
	 * Returns the logintime
	 *
	 * @access public
	 * @return integer
	 */
	public function getLogintime()
	{
		return $this->logintime;
	}
	/**
	 * Sets the logouttime
	 *
	 * @param integer $logouttime
	 * @access public
	 * @return Post
	 */
	public function setLogouttime($logouttime)
	{
		$this->logouttime = $logouttime;
		return $this;
	}
	
	/**
	 * Returns the logouttime
	 *
	 * @access public
	 * @return integer
	 */
	public function getLogouttime()
	{
		return $this->logouttime;
	}
	/**
	 * Sets the ipaddress
	 *
	 * @param string $ipaddress
	 * @access public
	 * @return Post
	 */
	public function setipaddress($ipaddress)
	{
		$this->ipaddress = $ipaddress;
		return $this;
	}
	
	/**
	 * Returns the ipaddress
	 *
	 * @access public
	 * @return string
	 */
	public function getipaddress()
	{
		return $this->ipaddress;
	}
	
	/**
	 * Sets the created_date
	 *
	 * @param integer $created_date
	 * @access public
	 * @return Post
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
	 * Sets the loggedoutby
	 *
	 * @param integer $loggedoutby
	 * @access public
	 * @return Post
	 */
	public function setLoggedoutby($loggedoutby)
	{
		$this->loggedoutby = $loggedoutby;
		return $this;
	}
	
	/**
	 * Returns the loggedoutby
	 *
	 * @access public
	 * @return integer
	 */
	public function getLoggedoutby()
	{
		return $this->loggedoutby;
	}
	/**
	 * Sets the loggedinby
	 *
	 * @param integer $loggedinby
	 * @access public
	 * @return Post
	 */
	public function setLoggedinby($loggedinby)
	{
		$this->loggedinby = $loggedinby;
		return $this;
	}
	
	/**
	 * Returns the loggedinby
	 *
	 * @access public
	 * @return integer
	 */
	public function getLoggedinby()
	{
		return $this->loggedinby;
	}
	
	/**
	 * Sets the created_time
	 *
	 * @param integer $created_time
	 * @access public
	 * @return Post
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
	
	public function setUser(User $user)
	{
		$this->user=$user;
	}
	
	public function getUser()
	{
		return $this->user;
	}
}
	
	
	
	
	
