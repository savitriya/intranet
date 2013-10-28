<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use	Doctrine\ORM\EntityManager;
use Application\Entity\DomainObject;

/**
 * A music album.
 * 
 * @ORM\Entity
 * @ORM\Table(name="contact")
 * @property int $id
 * @property string $paddress
 * @property string $raddress
 * @property int $altcontactnumber
 * @property int $user_id

 */
class Contact  
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
	protected  $paddress;
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $raddress;
	
	/**
	 * @ORM\Column(type="bigint",length=20,nullable=true)
	 */
	protected  $altcontactnumber;
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $user_id;
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	
	public function __construct() {
		$this->login = new ArrayCollection();
		$this->tmp=new ArrayCollection();
	}
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
	
	public function getLogin(){
		return $this->login;
	}
	
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
		parent::setEntityManager($this->em);
	}
	
	public function getEntityManager()
	{
		return $this->em;
	}
	
}