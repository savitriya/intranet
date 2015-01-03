<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\AllocationRepository")
 * @ORM\Table(name="allocation_master")
 * @property int $id
 * @property string $title
 * @property int $project_id
 * @property integer $start_date
 * @property integer $end_date
 * @property integer $created_by
 * @property integer $created_date
 * @property integer $updated_by
 * @property integer $updated_date
 */

class Allocation extends DomainObject 
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $title;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $project_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $start_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $end_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_by;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $created_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $updated_by;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $updated_date;
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
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
	 * @return Allocation
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
	 * Sets the title
	 *
	 * @param string $title
	 * @access public
	 * @return Allocation
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
	 * Sets the Project_id
	 *
	 * @param int $project_id
	 * @access public
	 * @return Allocation
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
	 * Sets the Start_date
	 *
	 * @param int $start_date
	 * @access public
	 * @return Allocation
	 */
	public function setStart_date($start_date)
	{
		$this->start_date = $start_date;
		return $this;
	}
	
	/**
	 * Returns the Start_date
	 *
	 * @access public
	 * @return int
	 */
	public function getStart_date()
	{
		return $this->start_date;
	}
	
	/**
	 * Sets the End_date
	 *
	 * @param int $end_date
	 * @access public
	 * @return Allocation
	 */
	public function setEnd_date($end_date)
	{
		$this->end_date = $end_date;
		return $this;
	}
	
	/**
	 * Returns the End_date
	 *
	 * @access public
	 * @return int
	 */
	public function getEnd_date()
	{
		return $this->end_date;
	}
	
	/**
	 * Sets the Created_by
	 *
	 * @param int $created_by
	 * @access public
	 * @return Allocation
	 */
	public function setCreated_by($created_by)
	{
		$this->created_by = $created_by;
		return $this;
	}
	
	/**
	 * Returns the Created_by
	 *
	 * @access public
	 * @return int
	 */
	public function getCreated_by()
	{
		return $this->created_by;
	}
	
	/**
	 * Sets the Created_date
	 *
	 * @param int $created_date
	 * @access public
	 * @return Allocation
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
	 * Sets the Updated_by
	 *
	 * @param int $updated_by
	 * @access public
	 * @return Allocation
	 */
	public function setUpdated_by($updated_by)
	{
		$this->updated_by = $updated_by;
		return $this;
	}
	
	/**
	 * Returns the Updated_by
	 *
	 * @access public
	 * @return int
	 */
	public function getUpdated_by()
	{
		return $this->updated_by;
	}
	
	/**
	 * Sets the Updated_date
	 *
	 * @param int $updated_date
	 * @access public
	 * @return Allocation
	 */
	public function setUpdated_date($updated_date)
	{
		$this->updated_date = $updated_date;
		return $this;
	}
	
	/**
	 * Returns the Updated_date
	 *
	 * @access public
	 * @return int
	 */
	public function getUpdated_date()
	{
		return $this->updated_date;
	}
}