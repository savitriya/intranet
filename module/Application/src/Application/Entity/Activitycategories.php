<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A music activitycategories
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\ActivitycategoriesRepository")
 * @ORM\Table(name="activitycategories")
 * @property string $name
 * @property string $color
 * @property int $id
 */
class Activitycategories
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string",unique=true)
	 */
	protected $name;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $color;

	/**
	 * Sets the Identifier
	 *
	 * @param int $id
	 * @access public
	 * @return Activitycategories
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
	 * @return Activitycategories
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
		return $this->Name;
	}
	
	/**
	 * Sets the color
	 *
	 * @param string $color
	 * @access public
	 * @return Activitycategories
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
	
	
}