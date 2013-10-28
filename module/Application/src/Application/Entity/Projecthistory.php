<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * projecthistory
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\ProjecthistoryRepository")
 * @ORM\Table(name="projecthistory")
 * @property integer $id
 * @property integer $project_id
 * @property text $description
 * @property integer $created_date
 * @property integer $created_time
 * @property integer $activity_by_id
 */

class Projecthistory
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
	 * Sets the Project_id
	 *
	 * @param int $project_id
	 * @access public
	 * @return Projecthistory
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
	 * Sets the Description
	 *
	 * @param string $description
	 * @access public
	 * @return Projecthistory
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
	 * @return Projecthistory
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
	 * @return Projecthistory
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
	 * Sets the Activity_by_id
	 *
	 * @param int $activity_by_id
	 * @access public
	 * @return Projecthistory
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