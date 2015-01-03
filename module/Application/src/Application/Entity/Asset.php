<?php
namespace Application\Entity;


use IntranetUtils\Common;
use Doctrine\ORM\Mapping as ORM;
use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Events;
use Zend\Authentication\AuthenticationService;




/**
 * asset.
 *
 *  @ORM\Entity 
 *  @ORM\Table(name="asset")
 *  @ORM\Entity @ORM\HasLifecycleCallbacks
 * 
 * @property int $asset_id
 * @property string $asset_name
 * @property string $asset_make
 * @property string $asset_model
 * @property string $serial_number
 * @property string $asset_code
 * @property int $manufacture_date
 * @property int $purchase_date
 * @property int $warranty_expiry_date
 * @property int $scrap_date
 * @property int $purchase_price
 * @property int $company_id
 * @property int $current_user_id
 * @property int $is_alive
 * @property smallint $is_primary
 * @property int $primary_asset_id
 * @property int $asset_type_id
 * @property int $asset_location_id
 */



class Asset extends DomainObject {
	
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $asset_id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $asset_name;
	
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $asset_make;
	
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $asset_model;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $serial_number;
	
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected $asset_code;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $manufacture_date;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $purchase_date;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $warranty_expiry_date;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $scrap_date;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $purchase_price;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $company_id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $current_user_id;
	
	/**
	 * @ORM\Column(type="integer",length=1)
	 */
	protected $is_alive;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $is_primary;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected $primary_asset_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $asset_type_id;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $asset_location_id;
	
	
	
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $loggedin_user_id;
	
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
	 * @param int $asset_id
	 * @access public
	 * @return Asset
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
	 * Sets the asset_name
	 *
	 * @param string $asset_name
	 * @access public
	 * @return Asset
	 */
	public function setAssetName($asset_name)
	{
	
		if ($asset_name != $this->asset_name) {
            $this->_onPropertyChanged('asset_name', $this->asset_name, $asset_name);
			$this->asset_name = $asset_name;
		}
		return $this;
	}
	/**
	 * Returns the asset_name
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetName()
	{
		return $this->asset_name;
	}
	
	/**
	 * Sets the asset_make
	 *
	 * @param string $asset_make
	 * @access public
	 * @return Asset
	 */
	public function setAssetMake($asset_make)
	{
		
		if ($asset_make != $this->asset_make) {
            $this->_onPropertyChanged('asset_make', $this->asset_make, $asset_make);
			$this->asset_make = $asset_make;
		}
		return $this;
	}
	/**
	 * Returns the asset_make
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetMake()
	{
		return $this->asset_make;
	}
	
	/**
	 * Sets the asset_model
	 *
	 * @param string $asset_model
	 * @access public
	 * @return Asset
	 */
	public function setAssetModel($asset_model)
	{
		if ($asset_model != $this->asset_model) {
            $this->_onPropertyChanged('asset_model', $this->asset_model, $asset_model);
			$this->asset_model = $asset_model;
		}
		return $this;
	}
	/**
	 * Returns the asset_model
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetModel()
	{
		return $this->asset_model;
	}
	
	/**
	 * Sets the serial number
	 *
	 * @param string $serial_number
	 * @access public
	 * @return Asset
	 */
	public function setSerialNumber($serial_number)
	{
		if ($serial_number != $this->serial_number) {
            $this->_onPropertyChanged('serial_number', $this->serial_number, $serial_number);
			$this->serial_number = $serial_number;
		}
		return $this;
	}
	/**
	 * Returns the serial number
	 *
	 * @access public
	 * @return string
	 */
	public function getSerialNumber()
	{
		return $this->serial_number;
	}
	
	/**
	 * Sets the asset code
	 *
	 * @param string $asset_code
	 * @access public
	 * @return Asset
	 */
	public function setAssetCode($asset_code)
	{
		if ($asset_code != $this->asset_code) {
            $this->_onPropertyChanged('asset_code', $this->asset_code, $asset_code);
			$this->asset_code = $asset_code;
		}
		return $this;
	}
	/**
	 * Returns the asset code
	 *
	 * @access public
	 * @return string
	 */
	public function getAssetCode()
	{
		return $this->asset_code;
	}
	
	/**
	 * Sets the manufacture date
	 *
	 * @param integer $manufacture_date
	 * @access public
	 * @return Asset
	 */
	public function setManufactureDate($manufacture_date)
	{
		if ($manufacture_date != $this->manufacture_date) {
            $this->_onPropertyChanged('manufacture_date', $this->manufacture_date, $manufacture_date);
			$this->manufacture_date = $manufacture_date;
		}
		return $this;
	}
	/**
	 * Returns the manufacture date
	 *
	 * @access public
	 * @return integer
	 */
	public function getManufactureDate()
	{
		return $this->manufacture_date;
	}
	
	/**
	 * Sets the purchase date
	 *
	 * @param integer $purchase_date
	 * @access public
	 * @return Asset
	 */
	public function setPurchaseDate($purchase_date)
	{
		if ($purchase_date != $this->purchase_date) {
            $this->_onPropertyChanged('purchase_date', $this->purchase_date, $purchase_date);
			$this->purchase_date = $purchase_date;
		}
		return $this;
	}
	/**
	 * Returns the purchase date
	 *
	 * @access public
	 * @return integer
	 */
	public function getPurchaseDate()
	{
		return $this->purchase_date;
	}
	
	/**
	 * Sets the warranty expiry date
	 *
	 * @param integer $warranty_expiry_date
	 * @access public
	 * @return Asset
	 */
	public function setWarrantyExpiryDate($warranty_expiry_date)
	{
		if ($warranty_expiry_date != $this->warranty_expiry_date) {
            $this->_onPropertyChanged('warranty_expiry_date', $this->warranty_expiry_date, $warranty_expiry_date);
			$this->warranty_expiry_date = $warranty_expiry_date;
		}
		return $this;
	}
	/**
	 * Returns the warranty expiry date
	 *
	 * @access public
	 * @return integer
	 */
	public function getWarrantyExpiryDate()
	{
		return $this->warranty_expiry_date;
	}
	
	/**
	 * Sets the scrap date
	 *
	 * @param integer $scrap_date
	 * @access public
	 * @return Asset
	 */
	public function setScrapDate($scrap_date)
	{
		if ($scrap_date != $this->scrap_date) {
            $this->_onPropertyChanged('scrap_date', $this->scrap_date, $scrap_date);
			$this->scrap_date = $scrap_date;
		}
		return $this;
	}
	/**
	 * Returns the scrap date
	 *
	 * @access public
	 * @return integer
	 */
	public function getScrapDate()
	{
		return $this->scrap_date;
	}
	
	/**
	 * Sets the warranty purchase price
	 *
	 * @param integer $purchase_price
	 * @access public
	 * @return Asset
	 */
	public function setPurchasePrice($purchase_price)
	{
		if ($purchase_price != $this->purchase_price) {
            $this->_onPropertyChanged('purchase_price', $this->purchase_price, $purchase_price);
			$this->purchase_price = $purchase_price;
		}
		return $this;
	}
	/**
	 * Returns the purchase price
	 *
	 * @access public
	 * @return integer
	 */
	public function getPurchasePrice()
	{
		return $this->purchase_price;
	}
	
	/**
	 * Sets the company_id
	 *
	 * @param integer $company_id
	 * @access public
	 * @return Asset
	 */
	public function setCompanyId($company_id)
	{
		if ($company_id != $this->company_id) {
            $this->_onPropertyChanged('company_id', $this->company_id, $company_id);
			$this->company_id = $company_id;
		}
		return $this;
	}
	/**
	 * Returns the company_id
	 *
	 * @access public
	 * @return int
	 */
	public function getCompanyId()
	{
		return $this->company_id;
	}
	
	/**
	 * Sets the current user id
	 *
	 * @param integer $current_user_id
	 * @access public
	 * @return Asset
	 */
	public function setCurrentUserId($current_user_id)
	{      
		if ($current_user_id != $this->current_user_id) {
            $this->_onPropertyChanged('current_user_id', $this->current_user_id, $current_user_id);
			$this->current_user_id = $current_user_id;
		}
		return $this;
	}
	/**
	 * Returns the current user id
	 *
	 * @access public
	 * @return integer
	 */
	public function getCurrentUserId()
	{
		return $this->current_user_id;
	}
	
	/**
	 * Sets the is_alive
	 *
	 * @param smallinteger $is_alive
	 * @access public
	 * @return Asset
	 */
	public function setIsAlive($is_alive)
	{
		if ($is_alive != $this->is_alive) {
			$this->_onPropertyChanged('is_alive', $this->is_alive, $is_alive);
			$this->is_alive = $is_alive;
		}
		return $this;
	
	}
	/**
	 * Returns the is_alive
	 *
	 * @access public
	 * @return smallinteger
	 */
	public function getIsAlive()
	{
		return $this->is_alive;
	}

	/**
	 * Sets the is_primary
	 *
	 * @param  integer $is_primary
	 * @access public
	 * @return Asset
	 */
	public function setIsPrimary($is_primary)
	{
		if ($is_primary != $this->is_primary) {
            $this->_onPropertyChanged('is_primary', $this->is_primary, $is_primary);
			$this->is_primary = $is_primary;
		}
		return $this;
	}
	/**
	 * Returns the is_primary
	 *
	 * @access public
	 * @return integer
	 */
	public function getIsPrimary()
	{
		return $this->is_primary;
	}
	
	/**
	 * Sets the primary_asset_id
	 *
	 * @param integer $primary_asset_id
	 * @access public
	 * @return Asset
	 */
	public function setPrimaryAssetId($primary_asset_id)
	{
		if ($primary_asset_id != $this->primary_asset_id) {
            $this->_onPropertyChanged('primary_asset_id', $this->primary_asset_id, $primary_asset_id);
			$this->primary_asset_id = $primary_asset_id;
		}
		return $this;
	}
	/**
	 * Returns the primary_asset_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getPrimaryAssetId()
	{
		return $this->primary_asset_id;
	}
	
	/**
	 * Sets the asset_type_id
	 *
	 * @param integer $asset_type_id
	 * @access public
	 * @return Asset
	 */
	public function setAssetTypeId($asset_type_id)
	{
		if ($asset_type_id != $this->asset_type_id) {
            $this->_onPropertyChanged('asset_type_id', $this->asset_type_id, $asset_type_id);
			$this->asset_type_id = $asset_type_id;
		}
		return $this;
	}
	/**
	 * Returns the asset_type_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getAssetTypeId()
	{
		return $this->asset_type_id;
	}
	
	/**
	 * Sets the asset_location_id
	 *
	 * @param integer $asset_location_id
	 * @access public
	 * @return Asset
	 */
	public function setAssetLocationId($asset_location_id)
	{
		if ($asset_location_id != $this->asset_location_id) {
            $this->_onPropertyChanged('asset_location_id', $this->asset_location_id, $asset_location_id);
			$this->asset_location_id = $asset_location_id;
		}
		return $this;
	}
	/**
	 * Returns the asset_location_id
	 *
	 * @access public
	 * @return integer
	 */
	public function getAssetLocationId()
	{
		return $this->asset_location_id;
	}	
	
	
	/**
	 * Sets the Current Logged-In User Id
	 *
	 * @param int $loggedin_user_id
	 * @access public
	 * @return Projects
	 */
	public function setLoginId($loggedin_user_id)
	{
		$this->loggedin_user_id = $loggedin_user_id;
	}
	
	/**
	 * Returns the Current Logged-In User Id
	 *
	 * @access public
	 * @return int
	 */
	public function getLoginId()
	{
		return $this->loggedin_user_id;
	}

	/** @ORM\PostUpdate */	
    public function OnPostUpdate()
    {
    
    	$ahist_desc = parent::getDescription();
    	if(strlen($ahist_desc) > 1)
    	{
    	
	        $em = $this->getEntityManager();
	        $newRecord = new Assethistory();
	        $auth = new AuthenticationService();
	        $this->setLoginId($auth->getIdentity()->id);
	        $loginid = $this->getLoginId();
	        $common = new Common();
	        $offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
	        $duedateInYmd = date("Y-m-d",strtotime(date("Y-m-d H:i:s")));
	        $created_date = strtotime($duedateInYmd)+$offset;
	        $newRecord->setAssetId($this->getAssetId());
	        $newRecord->setCreatedDateTime($created_date);
	        $newRecord->setAssetActivityDescription($ahist_desc);
			$newRecord->setActivityById($loginid);
		
	    	try{
				$em->persist($newRecord);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
			}
    	}
    }
}
