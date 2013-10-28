<?php 
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A music test.
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\TestRepository")
 * @ORM\Table(name="test")
 * @property string $name
 * @property string $value
 * @property int $id
 */
class Test
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
	protected $value;

	/**
	 * Magic getter to expose protected properties.
	 *
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 */
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

	/**
	 * Convert the object to an array.
	 *
	 * @return array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	/**
	 * Populate from an array.
	 *
	 * @param array $data
	 */
	public function populate($data = array())
	{
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->value = $data['value'];
	}
	function hashPassword($identity, $plaintext)
	{
		echo "sdsd";exit;
	}

}