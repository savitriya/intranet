<?php 

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A  preferences
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\PreferencesRepository")
 * @ORM\Table(name="preferences")
 * @property string $tomail
 * @property string $cc
 * @property int $user_id
 * @property int $id
 * @property string $leave_contact
 */
class Preferences  
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
	 * @ORM\Column(type="text")
	 */
	protected $cc;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected $tomail;
	
	/**
	 * @ORM\Column(type="text",nullable=true)
	 */
	protected $leave_contact;
	
	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return preferences
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
	 * @param string $user_id
	 * @access public
	 * @return preferences
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
	 * @return string
	 */
	public function getUserid()
	{
		return $this->user_id;
	}
	
	/**
	 * Sets the cc
	 *
	 * @param string $cc
	 * @access public
	 * @return preferences
	 */
	public function setCc($cc)
	{
		$this->cc = $cc;
		return $this;
	}
	
	/**
	 * Returns the cc
	 *
	 * @access public
	 * @return string
	 */
	public function getCc()
	{
		return $this->cc;
	}
	/**
	 * Sets the tomail
	 *
	 * @param string $tomail
	 * @access public
	 * @return preferences
	 */
	public function setTomail($tomail)
	{
		$this->tomail = $tomail;
		return $this;
	}
	
	/**
	 * Returns the tomail
	 *
	 * @access public
	 * @return string
	 */
	public function getTomail()
	{
		return $this->tomail;
	}
	
	/**
	 * Sets the leave_contact
	 *
	 * @param string $leave_contact
	 * @access public
	 * @return preferences
	 */
	public function setLeaveContact($leave_contact)
	{
		$this->leave_contact = $leave_contact;
		return $this;
	}
	
	/**
	 * Returns the leave_contact
	 *
	 * @access public
	 * @return string
	 */
	public function getLeaveContact()
	{
		return $this->leave_contact;
	}
	
}