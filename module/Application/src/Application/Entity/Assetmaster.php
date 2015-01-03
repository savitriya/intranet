<?php
namespace Application\Entity;
use Zend\Form\Element\Text;
use Application\Entity\Assetmaster;
use IntranetUtils\Common;
use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Application\Entity\DomainObject;

/**
 * Asset Master
 *
 * @ORM\Entity 
 * @ORM\Table(name="asset_master")
 * @property int $asset_master_id
 * @property string $asset_attribute
 * @property string $asset_attribute_type
 */

class Assetmaster extends DomainObject {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $asset_master_id;
	
	/**
	 * @ORM\Column(type="string");
	 */
	protected $asset_attribute;
	
	/**
	 * @ORM\Column(type="string");
	 */
	protected $asset_attribute_type;
	
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
	 * @param int $asset_master_id
	 * @access public
	 * @return Assetmaster
	 */
	public function setAssetMasterId($asset_master_id)
	{
		$this->asset_master_id = $asset_master_id;
		return $this;
	}
	/**
	 * Returns the asset_master_id
	 *
	 * @access public
	 * @return int
	 */
	public function getAssetMasterId()
	{
		return $this->asset_master_id;
	}

	/**
	 * Sets the asset_attribute
	 *
	 * @param string $asset_attribute
	 * @access public
	 * @return Assetmaster
	 */
	public function setAssetAttribute($asset_attribute)
	{
		$this->asset_attribute = $asset_attribute;
		return $this;
	}
	/**
	 * Returns the asset_attribute
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetAttribute()
	{
		return $this->asset_attribute;
	}
	
	/**
	 * Sets the asset_attribute_type
	 *
	 * @param string $asset_attribute_type
	 * @access public
	 * @return Assetmaster
	 */
	public function setAssetAttributeType($asset_attribute_type)
	{
		$this->asset_attribute_type = $asset_attribute_type;
		return $this;
	}
	/**
	 * Returns the asset_attribute_type
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetAttributeType()
	{
		return $this->asset_attribute_type;
	}
}
