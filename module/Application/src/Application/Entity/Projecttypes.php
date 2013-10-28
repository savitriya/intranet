<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * A music projecttypes
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\ProjecttypesRepository")
 * @ORM\Table(name="projecttypes")
 * @property int $is_default
 * @property string $name
 * @property string $color
 * @property int $id
 */
class Projecttypes
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
	 * @ORM\Column(type="string")
	 */
	protected $color;
	/**
	 *
	 * @ORM\Column(type="integer");
	 */
	protected $is_default;

	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return Projecttypes
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
	 * @return Projecttypes
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
	 * Sets the color
	 *
	 * @param string $color
	 * @access public
	 * @return Projecttypes
	 */
	public function setColor($color)
	{
		$this->color = $color;
		return $this;
	}
	
	/**
	 * Returns the color
	 *
	 * @access public
	 * @return string
	 */
	public function getColor()
	{
		return $this->color;
	}
	/**
	 * Sets theis_default
	 *
	 * @param int $is_default
	 * @access public
	 * @return Projecttypes
	 */
	public function setIs_default($is_default)
	{
		$this->is_default = $is_default;
		return $this;
	}
	
	/**
	 * Returns the is_default
	 *
	 * @access public
	 * @return int
	 */
	public function getIs_default()
	{
		return $this->is_default;
	}
	
	
}