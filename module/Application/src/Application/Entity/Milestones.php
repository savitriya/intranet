<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A milestone.
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\MilestonesRepository")
 * @ORM\Table(name="milestones")
 * @property int $id
 * @property int $project_id
 * @property int $estimated_startdate
 * @property int $estimated_enddate
 * @property int $actual_startdate
 * @property int $actual_enddate
 * @property int $status_id
 * @property int $name
 * @property int $isdefault
 * @property int $estimated_hours
 * @property string $description
 */

class Milestones  
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $name;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $project_id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
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
   protected  $isdefault;
   
   /**
    * @ORM\Column(type="text",length=65532,nullable=true)
    */
   protected  $description;
   
	
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
	 * Sets the name
	 *
	 * @param string $name
	 * @access public
	 * @return Milestones
	 */
	public function setName($name)
	{
		$this->name = $name;
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
	 * Sets the project_id
	 *
	 * @param string $project_id
	 * @access public
	 * @return Milestones
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
	 * @return string
	 */
	public function getProject_id()
	{
		return $this->project_id;
	}
	
	/**
	 * Sets the estimated_startdate
	 *
	 * @param int $estimated_startdate
	 * @access public
	 * @return Milestones
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
	 * @param int $estimated_enddate
	 * @access public
	 * @return Post
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
	 * Sets the status_id
	 *
	 * @param int $status_id
	 * @access public
	 * @return Post
	 */
	public function setStatus_id($status_id)
	{
		$this->status_id = $status_id;
		return $this;
	}
	
	/**
	 * Returns the status_id
	 *
	 * @access public
	 * @return int
	 */
	public function getStatus_id()
	{
		return $this->status_id;
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
		$this->estimated_hours = $estimated_hours;
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
	 * Sets the isdefault
	 *
	 * @param int $isdefault
	 * @access public
	 * @return Post
	 */
	
	public function setIsdefault($isdefault)
	{
		$this->isdefault = $isdefault;
		return $this;
	}
	
	/**
	 * Returns the isdefault
	 *
	 * @access public
	 * @return int
	 */
	public function getIsdefault()
	{
		return $this->isdefault;
	}
	
	/**
	 * Sets the description
	 *
	 * @param int $description
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
	
}
	
	
	
	
	
