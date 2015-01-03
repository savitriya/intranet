<?php
namespace Application\Entity;

//require getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/NotifyPropertyChanged.php';
//require getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/PropertyChangedListener.php';
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Application\Entity\Activitycategories;
use Application\Entity\User;
use Application\Entity\Activitystatuses;
use Zend\Authentication\AuthenticationService;
use Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;
use IntranetUtils\Common;

abstract class DomainObject implements NotifyPropertyChanged
{
	/**
	 * @ORM\Column(type="PropertyChangedListener")
	 */
    private $_listeners = array();
    
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $ahDescription = "";

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getEntityManager()
	{
		
		return $this->em;
	}
	
	public function getDescription()
	{
		return $this->ahDescription;
	}

    /**
	 * Sets the Listner
	 *
	 * @param PropertyChangedListener $listener
	 * @access public
	 * @return void
	 */
    public function addPropertyChangedListener(PropertyChangedListener $listener) {
        $this->_listeners[] = $listener;
    }

    /** Notifies listeners of a change
	 *
	 * @param string $propName, string $oldValue, string $newValue
	 * @access public
	 * @return void
	 */
    protected function _onPropertyChanged($propName, $oldValue, $newValue) {
//     	echo  "propName==$propName //oldValue==$oldValue//newValue==$newValue <br/>";
        if ($this->_listeners) {
        	$em = $this->getEntityManager();
        	$common = new Common();
            foreach ($this->_listeners as $listener) {
            	if($propName == 'category_id')
            	{
					$oldCatgQuery = $em->createQuery("SELECT ac.name FROM Application\Entity\Activitycategories ac where ac.id=$oldValue");
					$oldCatgName = $oldCatgQuery->getResult();
					$newCatgQuery = $em->createQuery("SELECT ac.name FROM Application\Entity\Activitycategories ac where ac.id=$newValue");
					$newCatgName = $newCatgQuery->getResult();
					$this->ahDescription .= "Category is changed from - ".$oldCatgName[0]['name']." to ".$newCatgName[0]['name'].".<br/>";
					
            	}
            	elseif($propName == 'assigned_to_id')
            	{
					$oldAtiQuery = $em->createQuery("SELECT u.fname, u.lname FROM Application\Entity\User u where u.id=$oldValue");
					$oldAtiName = $oldAtiQuery->getResult();
					$newAtiQuery = $em->createQuery("SELECT u.fname, u.lname FROM Application\Entity\User u where u.id=$newValue");
					$newAtiName = $newAtiQuery->getResult();
					$this->ahDescription .= " Assigned User is changed from - ".$oldAtiName[0]['fname']." ".$oldAtiName[0]['lname']." to ".$newAtiName[0]['fname']." ".$newAtiName[0]['lname'].".<br/>";
            	}
            	elseif($propName == 'status_id')
            	{
					$oldSidQuery = $em->createQuery("SELECT ast.name FROM Application\Entity\Activitystatuses ast where ast.id=$oldValue");
					$oldSid = $oldSidQuery->getResult();
					$newSidQuery = $em->createQuery("SELECT ast.name FROM Application\Entity\Activitystatuses ast where ast.id=$newValue");
					$newSid = $newSidQuery->getResult();
					$this->ahDescription .= " Status is changed from - ".$oldSid[0]['name']." to ".$newSid[0]['name'].".<br/>";
            	}
            	elseif($propName == 'subject')
            	{
            		$this->ahDescription .= " Subject is changed from - $oldValue to $newValue.<br/>";
            	}
            	elseif($propName == 'priority')
            	{
            		$this->ahDescription .= " Priority is changed from - $oldValue to $newValue.<br/>";
            	}
            	elseif($propName == 'due_date')
            	{
            		$this->ahDescription .= " Due Date is changed from - ".date("d/m/y",$oldValue)." to ".date("d/m/y",$newValue).".<br/>";
            	}
            	elseif($propName == 'estimated_hours')
            	{
          
            		$oldValue=$common->convertSpentTime($oldValue);
            		$newValue=$common->convertSpentTime($newValue);
            		
            		$this->ahDescription .= " Estimated Hours is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'name')
            	{
            		$this->ahDescription .= " Project Name is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'typeId')
            	{
            		$this->ahDescription .= " Type is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'project_estimated_hours')
            	{
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= " Estimated Hours is changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	elseif($propName == 'asset_name'){
            		$this->ahDescription .= "Asset Name changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'asset_make'){
            		$this->ahDescription .= "Asset Make changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'asset_model'){
            		$this->ahDescription .= "Asset Model changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'serial_number'){
            		$this->ahDescription .= "Asset Serial Number changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'asset_code'){
            		$this->ahDescription .= "Asset Code changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'manufacture_date'){
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= "Asset Manufacture Date changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	elseif($propName == 'purchase_date'){
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= "Asset Purchase Date changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	elseif($propName == 'warranty_expiry_date'){
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= "Asset Warranty Expiry Date changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	elseif($propName == 'scrap_date'){
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= "Asset Scrap Date changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	elseif($propName == 'purchase_price'){
            		$this->ahDescription .= "Asset Purchase Price from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'company_id'){
            		if(isset($oldValue) && $oldValue > 0){
            			$oldCompany = $em->createQuery("SELECT c.name FROM Application\Entity\Company c where c.id=$oldValue")->getResult();
            		}
            		if(isset($newValue) && $newValue > 0){
						$newCompany = $em->createQuery("SELECT c.name FROM Application\Entity\Company c where c.id=$newValue")->getResult();
            		}
            		$this->ahDescription .= "Company changed from - ".$oldCompany[0]['name']." to ".$newCompany[0]['name'].".<br/>";
            	}
            	elseif($propName == 'current_user_id'){
            	
            		if(isset($oldValue) && $oldValue > 0){
            			
            			$oldUser = $em->createQuery("SELECT u.fname as fname, u.lname as lname FROM Application\Entity\User u where u.id=$oldValue")->getResult();
            		}
            		if(isset($newValue) && $newValue > 0){
						$newUser = $em->createQuery("SELECT u.fname as fname, u.lname as lname FROM Application\Entity\User u where u.id=$newValue")->getResult();
            		}
            		if(isset($oldUser)){
            		$this->ahDescription .= "User changed from - ".$oldUser[0]['fname']." ".$oldUser[0]['lname']." to ".$newUser[0]['fname']." ".$newUser[0]['lname'].".<br/>";
            	}else{
            		$this->ahDescription .= "User changed to ".$newUser[0]['fname']." ".$newUser[0]['lname'].".<br/>";
            	}
            	}
            	elseif($propName == 'is_alive'){
            		if($oldValue == 0)
            			$oldValueText = 'No';
            		elseif($oldValue == 1)
            			$oldValueText = 'Yes';
            		if($newValue == 0)
            			$newValueText = 'No';
            		elseif($newValue == 1)
            			$newValueText = 'Yes';
            		$this->ahDescription .= "Is Asset Alive changed from - ".$oldValueText." to ".$newValueText.".<br/>";
            	}
            	elseif($propName == 'is_primary'){
            		if($oldValue == 0)
            			$oldValueText = 'No';
            		elseif($oldValue == 1)
            			$oldValueText = 'Yes';
            		if($newValue == 0)
            			$newValueText = 'No';
            		elseif($newValue == 1)
            			$newValueText = 'Yes';
            		$this->ahDescription .= "Is Asset Primary changed from - ".$oldValueText." to ".$newValueText.".<br/>";
            	}
            	elseif($propName == 'primary_asset_id'){
            		if(isset($oldValue) && $oldValue > 0){
            			$oldPriAsset = $em->createQuery("SELECT a.asset_name as name FROM Application\Entity\Asset a where a.asset_id=$oldValue")->getResult();
            		}
            		if(isset($newValue) && $newValue > 0){
						$newPriAsset = $em->createQuery("SELECT a.asset_name as name FROM Application\Entity\Asset a where a.asset_id=$newValue")->getResult();
            		}
					$this->ahDescription .= "Primary Asset changed from - ".$oldPriAsset[0]['name']." to ".$newPriAsset[0]['name'].".<br/>";
            	}
                elseif($propName == 'asset_type_id'){
            		if(isset($oldValue) && $oldValue > 0){
            			$oldType = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$oldValue")->getResult();
            		}
            		if(isset($oldValue) && $oldValue > 0){
						$newType = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$newValue")->getResult();
            		}
					$this->ahDescription .= "Asset Type changed from - ".$oldType[0]['name']." to ".$newType[0]['name'].".<br/>";
            	}
            	elseif($propName == 'asset_location_id'){
            		if(isset($oldValue) && $oldValue > 0){
            			$oldLocation = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$oldValue")->getResult();
            		}
            		if(isset($oldValue) && $oldValue > 0){
						$newLocation = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$newValue")->getResult();
            		}
					$this->ahDescription .= "Asset Location changed from - ".$oldLocation[0]['name']." to ".$newLocation[0]['name'].".<br/>";
            	}
            	elseif($propName == 'asset_location_id'){
            		if(isset($oldValue) && $oldValue > 0){
            			$oldLocation = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$oldValue")->getResult();
            		}
            		if(isset($oldValue) && $oldValue > 0){
						$newLocation = $em->createQuery("SELECT am.asset_attribute as name FROM Application\Entity\Assetmaster am where am.asset_master_id=$newValue")->getResult();
            		}
					$this->ahDescription .= "Asset Location changed from - ".$oldLocation[0]['name']." to ".$newLocation[0]['name'].".<br/>";
            	}
            	$listener->propertyChanged($this, $propName, $oldValue, $newValue);
            }
        }
    }
}