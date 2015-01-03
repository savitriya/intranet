<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A music dailyquote
 *
 * @ORM\Entity 
 * @ORM\Table(name="dailyquote")
 * @property int $id
 * @property string $date
 * @property string $heading
 * @property string $description
 */
/**
 * 
 * @ORM\Entity @ORM\HasLifecycleCallbacks
 *
 */

class Dailyquote extends DomainObject 
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
	protected $date;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $heading;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $description;
	
	
	
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
	 * @return holiday
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
	 * Sets the date
	 *
	 * @param integer $date
	 * @access public
	 * @return Holiday
	 */
	public function setDate($date)
	{
		$this->date = $date;
		return $this;
	}
	
	/**
	 * Returns the date
	 *
	 * @access public
	 * @return integer
	 */
	public function getDate()
	{
		return $this->date;
	}
	
	/**
	 * Sets the heading
	 *
	 * @param string $heading
	 * @access public
	 * @return Dailyquote
	 */
	public function setHeading($heading)
	{
		$this->heading = $heading;
		return $this;
	}
	
	/**
	 * Returns the heading
	 *
	 * @access public
	 * @return string
	 */
	public function getHeading()
	{
		return $this->heading;
	}
	
	
	/**
	 * Sets the description
	 *
	 * @param string $description
	 * @access public
	 * @return Dailyquote
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