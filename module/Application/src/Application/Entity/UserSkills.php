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
 * @ORM\Table(name="user_skills_mapper")
 * @property int $skill_id
 * @property int $user_id
 * @property int $id
 */

class UserSkills extends DomainObject 
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
	protected $user_id;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $skill_id;

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
	 * @param int $id
	 * @access public
	 * @return UserSkills
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Returns the id
	 * @access public
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	
	/**
	 * Sets the Identifier
	 * @param int $id
	 * @access public
	 * @return UserSkills
	 */
	public function setUserId($userId)
	{
		$this->user_id = $userId;
		return $this;
	}
	
	/**
	 * Returns the user_id
	 * @access public
	 * @return int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
	
	/**
	 * Sets the Identifier
	 * @param int $skillId
	 * @access public
	 * @return UserSkills
	 */
	public function setSkillId($skillId)
	{
		$this->skill_id = $skillId;
		return $this;
	}
	
	/**
	 * Returns the skill_id
	 * @access public
	 * @return int
	 */
	public function getSkillId()
	{
		return $this->skill_id;
	}	
}