<?php
namespace Application\Entity;
use Zend\Form\Element\Text;
use Application\Entity\Assethistory;
use IntranetUtils\Common;
use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Application\Entity\DomainObject;

/**
 * Asset History
 *
 * @ORM\Entity 
 * @ORM\Table(name="asset_history")
 * @property int $asset_history_id
 * @property int $asset_id
 * @property string $asset_activity_description
 * @property int $created_date_time
 * @property int $activity_by_id
 */
class Assethistory extends DomainObject {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $asset_history_id;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $asset_id;
	
	/**
	 * @ORM\Column(type="text");
	 */
	protected $asset_activity_description;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $created_date_time;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected $activity_by_id;
	
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
	 * @param int $asset_history_id
	 * @access public
	 * @return Assethistory
	 */
	public function setAssetHistoryId($asset_history_id)
	{
		$this->asset_history_id = $asset_history_id;
		return $this;
	}
	/**
	 * Returns the id
	 *
	 * @access public
	 * @return int
	 */
	public function getAssetHistoryId()
	{
		return $this->asset_history_id;
	}
	
	/**
	 * Sets the asset_id
	 *
	 * @param int $asset_id
	 * @access public
	 * @return Assethistory
	 */
	public function setAssetId($asset_id)
	{
		$this->asset_id = $asset_id;
		return $this;
	}
	/**
	 * Returns the asset_id
	 *
	 * @access public
	 * @return int
	 */
	public function getAssetId()
	{
		return $this->asset_id;
	}
	
	/**
	 * Sets the $asset_activity_description
	 *
	 * @param Text $asset_activity_description
	 * @access public
	 * @return Assethistory
	 */
	public function setAssetActivityDescription($asset_activity_description)
	{
		$this->asset_activity_description = $asset_activity_description;
		return $this;
	}
	/**
	 * Returns the asset_activity_description
	 *
	 * @access public
	 * @return Text
	 */
	public function getAssetActivityDescription()
	{
		return $this->asset_activity_description;
	}
	
	/**
	 * Sets the $created_date_time
	 *
	 * @param int $created_date_time
	 * @access public
	 * @return Assethistory
	 */
	public function setCreatedDateTime($created_date_time)
	{
		$this->created_date_time = $created_date_time;
		return $this;
	}
	/**
	 * Returns the created_date_time
	 *
	 * @access public
	 * @return int
	 */
	public function getCreatedDateTime()
	{
		return $this->created_date_time;
	}
	
	/**
	 * Sets the $activity_by_id
	 *
	 * @param int $activity_by_id
	 * @access public
	 * @return Assethistory
	 */
	public function setActivityById($activity_by_id)
	{
		$this->activity_by_id = $activity_by_id;
		return $this;
	}
	/**
	 * Returns the activity_by_id
	 *
	 * @access public
	 * @return int
	 */
	public function getActivityById()
	{
		return $this->activity_by_id;
	}
	
}
