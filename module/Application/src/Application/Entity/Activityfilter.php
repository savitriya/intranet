<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A music activityfilter
 *
 * @ORM\Entity 
 * @ORM\Table(name="activityfilter")
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property string $string
 * 
 */

class Activityfilter extends DomainObject 
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
	protected $name;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $user_id;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $string;
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
	 * @return ActivityFilter
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
	 * @return Activityfilter
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
	 * Sets the user_id
	 *
	 * @param int $user_id
	 * @access public
	 * @return ActivityFilter
	 */
	public function setUser_id($userId)
	{
		$this->user_id = $userId;
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
	 * Sets the string
	 *
	 * @param string $string
	 * @access public
	 * @return ActivityFilter
	 */
	public function setString($string)
	{
		$this->string = $string;
		return $this;
	}
	
	/**
	 * Returns the string
	 *
	 * @access public
	 * @return string
	 */
	public function getString()
	{
		return $this->string;
	}
	
	
}