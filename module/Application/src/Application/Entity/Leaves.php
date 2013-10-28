<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;

/**
 * 
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\LeavesRepository")
 * @ORM\Table(name="`leave`")
 * @property int $id
 * @property string $title
 * @property int $start_date
 * @property int $end_date
 * @property int $no_of_days
 * @property string $description
 * @property int $is_sanctioned
 * @property int $created_date
 * @property int $created_time
 */

class Leaves //extends DomainObject
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
	/**
	 * @ORM\Column(type="text");
	 */
	protected $title;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $start_date;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $end_date;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $no_of_days;
	
	/**
	 * @ORM\Column(type="text");
	 */
	protected $description;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $is_sanctioned;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $created_date;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $created_time;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $user_id;
	
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
	 * Sets the title
	 *
	 * @param string $title
	 * @access public
	 * @return Leaves
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * Returns the title
	 *
	 * @access public
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	
	/**
	 * Sets the start_date
	 *
	 * @param int $start_date
	 * @access public
	 * @return Leaves
	 */
	public function setStartDate($start_date)
	{
		$this->start_date = $start_date;
		return $this;
	}
	
	/**
	 * Returns the start_date
	 *
	 * @access public
	 * @return int
	 */
	public function getStartDate()
	{
		return $this->start_date;
	}

	
	/**
	 * Sets the end_date
	 *
	 * @param int $end_date
	 * @access public
	 * @return Leaves
	 */
	public function setEndDate($end_date)
	{
		$this->end_date = $end_date;
		return $this;
	}
	
	/**
	 * Returns the end_date
	 *
	 * @access public
	 * @return int
	 */
	public function getEndDate()
	{
		return $this->end_date;
	}

	
	/**
	 * Sets the no_of_days
	 *
	 * @param int $no_of_days
	 * @access public
	 * @return Leaves
	 */
	public function setNoOfDays($no_of_days)
	{
		$this->no_of_days = $no_of_days;
		return $this;
	}
	
	/**
	 * Returns the no_of_days
	 *
	 * @access public
	 * @return int
	 */
	public function getNoOfDays()
	{
		return $this->no_of_days;
	}

	
	/**
	 * Sets the description
	 *
	 * @param int $description
	 * @access public
	 * @return Leaves
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
	 * Sets the is_sanctioned
	 *
	 * @param int $is_sanctioned
	 * @access public
	 * @return Leaves
	 */
	public function setIsSanctioned($is_sanctioned)
	{
		$this->is_sanctioned = $is_sanctioned;
		return $this;
	}
	
	/**
	 * Returns the is_sanctioned
	 *
	 * @access public
	 * @return int
	 */
	public function getIsSanctioned()
	{
		return $this->is_sanctioned;
	}
	
	/**
	 * Sets the created_date
	 *
	 * @param int $created_date
	 * @access public
	 * @return Leaves
	 */
	public function setCreatedDate($created_date)
	{
		$this->created_date = $created_date;
		return $this;
	}
	
	/**
	 * Returns the created_date
	 *
	 * @access public
	 * @return int
	 */
	public function getCreatedDate()
	{
		return $this->created_date;
	}
	
	/**
	 * Sets the created_time
	 *
	 * @param int $created_time
	 * @access public
	 * @return Leaves
	 */
	public function setCreatedTime($created_time)
	{
		$this->created_time = $created_time;
		return $this;
	}
	
	/**
	 * Returns the created_time
	 *
	 * @access public
	 * @return int
	 */
	public function getCreatedTime()
	{
		return $this->created_time;
	}
	
	/**
	 * Sets the user_id
	 *
	 * @param int $user_id
	 * @access public
	 * @return Leaves
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * Returns the user_id
	 *
	 * @access public
	 * @return int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
}