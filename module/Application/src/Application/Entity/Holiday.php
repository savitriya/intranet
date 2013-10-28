<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A music holiday
 *
 * @ORM\Entity
 *  
 * @ORM\Table(name="holiday")
 * @property string $holidayname
 * @property string $date
 * @property int $id
 */
/** @ORM\Entity @ORM\HasLifecycleCallbacks
 * 
 * */


class Holiday extends DomainObject 
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
	protected $holidayname;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $date;
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
	 * Sets the holidayname
	 *
	 * @param string $holidayname
	 * @access public
	 * @return Holiday
	 */
	public function setHolidayname($holidayname)
	{
		$this->holidayname = $holidayname;
		return $this;
	}
	
	/**
	 * Returns the holidayname
	 *
	 * @access public
	 * @return string
	 */
	public function getHolidayname()
	{
		return $this->holidayname;
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
	
	
}