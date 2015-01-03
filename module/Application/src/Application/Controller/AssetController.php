<?php
namespace Application\Controller;

use Doctrine\ORM\Query\Expr\OrderBy;

use Symfony\Component\Console\Application;
use Doctrine\DBAL\Schema\View;
use Doctrine\ORM\Query\Expr\Select;
use IntranetUtils\Common;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use	Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\Query\Expr;
use Application\Entity;
use Application\Entity\Asset;
use Application\Entity\Assetmaster;
use Application\Entity\Assethistory;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

class AssetController extends AbstractActionController {
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
	
	public function indexAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	
	public function gridassetAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		$page = $this->getRequest()->getPost('page');
		$limit =$this->getRequest()->getPost('rows');
		$sidx = $this->getRequest()->getPost('sidx');
		$sord = $this->getRequest()->getPost('sord');
		if($page > 0){
			$start = ($limit * $page) - $limit; // do not put $limit*($page - 1)
		}
		else{
			$start = 0;
		}
		if($sidx=="" ||$sidx=="code"){
			$sidx="a.asset_code";
		}
		else if($sidx=="name"){
			$sidx="a.asset_name";
		}
		else if($sidx=="dop"){
			$sidx="a.purchase_date";
		}
		else if($sidx=="type"){
			$sidx="a.asset_type_id";
		}
		else if($sidx=="company"){
			$sidx="a.company_id";
		}
		else if($sidx=="location"){
			$sidx="a.location_id";
		}
		$em = $this->getEntityManager();
		// SELECT concat( `firstname` , ' ', `middlename` , ' ', `lastname` ) AS name FROM `users`;
		try {
			$assetCountQuery = $em->createQuery('SELECT a.asset_id as id, a.asset_code as code, 
				a.asset_name as name,a.purchase_date as dop,a.is_alive as isalive,a.is_primary as isprimary, 
				a.current_user_id as userid, amloc.asset_attribute as location, c.name as company, 
				amtype.asset_attribute as type, u.fname as fname, u.lname AS lname 
				FROM Application\Entity\Asset a 
				JOIN Application\Entity\Company c WITH a.company_id=c.id 
				Left JOIN Application\Entity\User u WITH a.current_user_id=u.id 
				LEFT JOIN Application\Entity\Assetmaster amtype WITH a.asset_type_id=amtype.asset_master_id 
				LEFT JOIN Application\Entity\Assetmaster amloc WITH a.asset_location_id=amloc.asset_master_id 
				ORDER BY ' . $sidx . ' ' . $sord);
			  
			//echo $assetQuery->getSQL();exit;
		} catch(Exception $e) {
			echo $e->getMessage() . "<br/>" . $e->getTraceAsString();
			exit;
		}
		$allCountAssets = $assetCountQuery->getResult();
		//print_r($allAssets);exit();
		
		$totalPages = 0;
		if (count($allCountAssets)>0){
			$totalPages = ceil(count($allCountAssets)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($allCountAssets);
		//print_r($response);exit;
		
		try {
			$assetQuery = $em->createQuery('SELECT a.asset_id as id, a.asset_code as code,
					a.asset_name as name,a.purchase_date as dop,a.is_alive as isalive,a.is_primary as isprimary,
					a.current_user_id as userid, amloc.asset_attribute as location, c.name as company,
					amtype.asset_attribute as type, u.fname as fname, u.lname AS lname
					FROM Application\Entity\Asset a
					JOIN Application\Entity\Company c WITH a.company_id=c.id
					Left JOIN Application\Entity\User u WITH a.current_user_id=u.id
					LEFT JOIN Application\Entity\Assetmaster amtype WITH a.asset_type_id=amtype.asset_master_id
					LEFT JOIN Application\Entity\Assetmaster amloc WITH a.asset_location_id=amloc.asset_master_id
					ORDER BY ' . $sidx . ' ' . $sord)
					->setFirstResult( $start )
					->setMaxResults( $limit );
			//echo $assetQuery->getSQL();exit;
		} catch(Exception $e) {
			echo $e->getMessage() . "<br/>" . $e->getTraceAsString();
			exit;
		}
		
		$allAssets =$assetQuery->getResult();
		$i=0;
		foreach($allAssets as $asset){
			if(isset($asset['dop']) && $asset['dop'] > 0){
				$asset['dop'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$asset['dop']),'Asia/Calcutta','d/m/Y');
			}
			if(isset($asset['isalive'])){
				if($asset['isalive'] == 0){
					$asset['isalive'] = 'No';
				}
				elseif($asset['isalive'] == 1){
					$asset['isalive'] = 'Yes';
				}
				else{
					$asset['isalive'] = '';
				}
			}
			
			if(isset($asset['isprimary'])){
				if($asset['isprimary'] == 0){
					$asset['isprimary'] = 'No';
				}
				elseif($asset['isprimary'] == 1){
					$asset['isprimary'] = 'Yes';
				}
				else{
					$asset['isprimary'] = '';
				}
			}
			
			$action="<a href='/addasset/".$asset['id']."'><i class='icon-edit'></i></a>&nbsp;<a href='javascript:deleteAsset(".$asset['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $asset['id'];
			$response['rows'][$i]['cell']=array(
				$asset['code'],
				$asset['name'],
				$asset['dop'],
				$asset['isalive'],
				$asset['isprimary'],
				ucwords($asset['fname'] . " " . $asset['lname']),
				$asset['type'],
				$asset['company'],
				$asset['location'],
				$action);
			$i++;
		}
		//print_r($response);exit;
		header("Content-type:application/json");
		echo json_encode($response);
		exit;
	}
	
	public function deleteassetAction(){
		$auth = new AuthenticationService();
		$em=$this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$response=array();
		$id=$this->getRequest()->getPost('id');
		$assetQuery = $em->createQuery("SELECT a.asset_id as assetid, a.is_primary as isprimary FROM Application\Entity\Asset a where a.asset_id=$id")->getResult();
		if(count($assetQuery)>0){
			if($auth->getIdentity()->isadmin!=1){
				$response['returnvalue']="invalid";
				$response['data']['user']="null";
				//return $this->redirect()->toRoute('activities');
			}
			if($assetQuery[0]['isprimary'] == 1){
				$secondaryAssets = $em->createQuery('SELECT a.asset_id as sec_aid, a.asset_name as sec_aname FROM Application\Entity\Asset a WHERE a.primary_asset_id='.$assetQuery[0]['assetid'])->getArrayResult();
			}
			if(isset($secondaryAssets) && sizeof($secondaryAssets) > 0){
				$response['returnvalue']="invalid";
				$response['data']['is_primary']="null";
			}
		}
		if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
			$response['returnvalue']="invalid";
		}
		else{
			$response=array();
		}
		if(count($response)==0){
			$delete = $em->find('Application\Entity\Asset', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue']="valid";
			}
		}
		echo json_encode($response);
		exit;
	}
	
	public function addassetAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}	
		$common = new Common();
		$em = $this->getEntityManager();
		$id = $this->params('id');
		if($this->getRequest()->isPost()){
			$response = array();
			$name = $this->getRequest()->getPost('name');
			$make = $this->getRequest()->getPost('make');
			$model = $this->getRequest()->getPost('model');
			$serialNumber = $this->getRequest()->getPost('serialnumber');
			$code = $this->getRequest()->getPost('code');
			$manufactureDate = $this->getRequest()->getPost('alternate_manufacturedate');
			$purchaseDate = $this->getRequest()->getPost('alternate_purchasedate');
			$warrantyExpiryDate = $this->getRequest()->getPost('alternate_warrantyexpirydate');
			$scrapDate = $this->getRequest()->getPost('alternate_scrapdate');
			$purchasePrice = $this->getRequest()->getPost('purchaseprice');
			$currentUser = $this->getRequest()->getPost('currentuser');
			$isAlive = $this->getRequest()->getPost('isalive');
			$isPrimary = $this->getRequest()->getPost('isprimary');
			$primaryAsset = $this->getRequest()->getPost('primaryasset');
			$assetType = $this->getRequest()->getPost('assettype');
			$assetLocation = $this->getRequest()->getPost('assetlocation');
			$company = $this->getRequest()->getPost('company');
			
			if($primaryAsset == ''){
				$primaryAsset = NULL;
			}
			if($name == ""){
				$response['data']['name']="null";
			}
			else{
				$response['data']['name']="valid";
			}
			if($serialNumber == ""){
				$response['data']['serialnumber']="null";
			}
			else{
				$response['data']['serialnumber']="valid";
			}
			if($purchasePrice=="" ){
				$response['data']['purchaseprice']="null";
			}
			elseif ($purchasePrice == 0){
				$response['data']['purchaseprice']="invalid";
			}
			else {
				$response['data']['purchaseprice']="valid";
			}
			if($company == ""){
				$response['data']['company']="null";
			}
			else{
				$response['data']['company']="valid";
			}
			if($isAlive == ""){
				$response['data']['isalive']="null";
			}
			else{
				$response['data']['isalive']="valid";
			}
			if($isPrimary == ""){
				$response['data']['isprimary']="null";
			}
			else{
				$response['data']['isprimary']="valid";
			}
			if($assetType == ""){
				$response['data']['assettype']="null";
			}
			else{
				$response['data']['assettype']="valid";
			}
			if($assetLocation == ""){
				$response['data']['assetlocation']="null";
			}
			else{
				$response['data']['assetlocation']="valid";
			}
			
			if(isset($manufactureDate) && $manufactureDate != '1970-01-01' && isset($purchaseDate) && $purchaseDate != '1970-01-01'){
				$diff = $common->getTimeDiff($purchaseDate, $manufactureDate, "Y/m/d H:i:s");
				if(intval(strtotime($diff))<0){
					$response['data']['purchasedate']="invalid";
					$response['returnvalue']="invalid";
				}
				else{
					$response['data']['purchasedate']="valid";
				}
			}
		
			if(isset($warrantyExpiryDate) && $warrantyExpiryDate != '1970-01-01' && isset($purchaseDate) && $purchaseDate != '1970-01-01'){
				$diff = $common->getTimeDiff($warrantyExpiryDate, $purchaseDate, "Y/m/d H:i:s");
				if(intval(strtotime($diff))<0){
					$response['data']['warrantyexpirydate']="invalid";
					$response['returnvalue']="invalid";
				}
				else{
					$response['data']['warrantyexpirydate']="valid";
				}
			}
			
/*			if(isset($scrapDate) && $scrapDate != '1970-01-01' && isset($purchaseDate) && $purchaseDate != '1970-01-01'){
				$diff = $common->getTimeDiff($scrapDate, $purchaseDate, "Y/m/d H:i:s");
				if(intval(strtotime($diff))<0){
					$response['data']['scrapdate']="invalid";
					$response['returnvalue']="invalid";
				}
				else{
					$response['data']['scrapdate']="valid";
				}
			}
*/			
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']) || in_array("milestonemissmatch", $response['data']))){
				$response['returnvalue']="invalid";
				echo json_encode($response);
				exit;
			}
			else{
				$response=array();
			}
			
			if(count($response)==0){
				$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
				$manufactureDateInYmd = date("Y-m-d H:i:s",strtotime($manufactureDate));
				$manufactureDateOffset = strtotime($manufactureDateInYmd)+$offset;
				$purchaseDateInYmd = date("Y-m-d H:i:s",strtotime($purchaseDate));
				$purchaseDateOffset = strtotime($purchaseDateInYmd)+$offset;
				$warrantyExpiryDateInYmd = date("Y-m-d H:i:s",strtotime($warrantyExpiryDate));
				$warrantyExpiryDateOffset = strtotime($warrantyExpiryDateInYmd)+$offset;
				$scrapDateInYmd = date("Y-m-d H:i:s",strtotime($scrapDate));
				$scrapDateOffset = strtotime($scrapDateInYmd)+$offset;
			}
			
			$newRecord = new \stdClass();
			$isNewRecord = false;
			if($id != "" && $id > 0){
				$newRecord = $em->find('Application\Entity\Asset', $id);
			}
			else{
				$newRecord = new Asset();
				$isNewRecord = true;
			}
			$newRecord->setEntityManager($em);
			$newRecord->setAssetName($name);
			$newRecord->setAssetMake($make);
			$newRecord->setAssetModel($model);
			$newRecord->setSerialNumber($serialNumber);
			$newRecord->setAssetCode($code);
			if(isset($manufactureDateOffset) && $manufactureDateOffset > 0){
				$newRecord->setManufactureDate($manufactureDateOffset);
			}
			if(isset($purchaseDateOffset) && $purchaseDateOffset > 0){
				$newRecord->setPurchaseDate($purchaseDateOffset);
			}
			if(isset($warrantyExpiryDateOffset) && $warrantyExpiryDateOffset > 0){
				$newRecord->setWarrantyExpiryDate($warrantyExpiryDateOffset);
			}
			if(isset($scrapDateOffset) && $scrapDateOffset > 0){
				$newRecord->setScrapDate($scrapDateOffset);
			}
			if(isset($purchasePrice) && $purchasePrice > 0){
				$newRecord->setPurchasePrice($purchasePrice);
			}
			
			$newRecord->setCompanyId($company);
			$newRecord->setCurrentUserId($currentUser);
			$newRecord->setIsAlive($isAlive);
			$newRecord->setIsPrimary($isPrimary);
			$newRecord->setPrimaryAssetId($primaryAsset);
			$newRecord->setAssetTypeId($assetType);
			$newRecord->setAssetLocationId($assetLocation);
			
			try{
				$em->persist($newRecord);
				$em->flush();
				$response['returnvalue']="valid";
			}
			catch (Exception $e){
				echo $e->getMessage();
				$response['returnvalue']="exception";
				exit;
			}
			echo json_encode($response);
			exit;
		}
		else {
			$where = '';
			if(isset($id) && $id > 0){
				$assetRecord = $em->find('Application\Entity\Asset', $id);
				$assetId = $assetRecord->getAssetId();
				$where = 'a.asset_id='.$assetId;

				$assetRecord = $em->find('Application\Entity\Asset', $id);
				$checkPrimary = $assetRecord->getIsPrimary();
				if(isset($checkPrimary) && ($checkPrimary == NULL || $checkPrimary == 0)){
					$primaryAssets = $em->createQuery('SELECT a.asset_id as a_id, a.asset_name as a_name FROM Application\Entity\Asset a WHERE a.is_primary=1')->getArrayResult();
					foreach ($primaryAssets as $pa){
						$primaryAssetsArray[$pa['a_id']] = $pa['a_name'];
					}
				}
				elseif(isset($checkPrimary) && $checkPrimary == 1){
					$secondaryAssets = $em->createQuery('SELECT a.asset_id as sec_aid, a.asset_name as sec_aname FROM Application\Entity\Asset a WHERE a.primary_asset_id='.$id)->getArrayResult();
					foreach($secondaryAssets as $sa){
						$secondaryAssetsArray[$sa['sec_aid']] = $sa['sec_aname'];
					}
				}
				
				$assetHistory = $em->createQuery('SELECT ah.asset_id as ah_id, 
				ah.asset_activity_description as ah_desc, ah.created_date_time as ah_date, 
				ah.activity_by_id as ah_actby, u.fname as fname, u.lname as lname 
				FROM Application\Entity\Assethistory ah  
				JOIN Application\Entity\User u
				WITH ah.activity_by_id=u.id 
				WHERE ah.asset_id='.$id)->getArrayResult();
				
				for($h=0;$h<sizeof($assetHistory);$h++){
					$assetHistory[$h]['ah_date'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $assetHistory[$h]['ah_date']),'Asia/Calcutta','Y/m/d');
					$assetHistory[$h]['act_by_name'] = $assetHistory[$h]['fname'].' '.$assetHistory[$h]['lname'];
				}
				$valuesToSend['assetHistory'] = $assetHistory;
				$valuesToSend['assetRecord'] = $assetRecord;
			}
			else {
				$where = "1=1";
				$primaryAssets = $em->createQuery('SELECT a.asset_id as a_id, a.asset_name as a_name FROM Application\Entity\Asset a WHERE a.is_primary=1')->getArrayResult();
				foreach ($primaryAssets as $pa){
					$primaryAssetsArray[$pa['a_id']] = $pa['a_name'];
				}
			}
			$users = $em->createQuery('SELECT u.id as uid, u.fname as first_name, u.lname as last_name FROM Application\Entity\User u')->getArrayResult();
			foreach ($users as $u){
				$userArray[$u['uid']] = ucwords($u['first_name'].' '.$u['last_name']);
			}
			$companies = $em->createQuery('SELECT c.id as cid, c.name as company_name FROM Application\Entity\Company c')->getArrayResult();
			foreach($companies as $c){
				$companyArray[$c['cid']] = ucwords($c['company_name']);
			}
			$whrType = "t.asset_attribute_type='category'";
			$assetType = $em->createQuery('SELECT t.asset_master_id as tid, t.asset_attribute as asset_type FROM Application\Entity\Assetmaster t WHERE '.$whrType)->getArrayResult();
			$typeArray=array();
			foreach($assetType as $t){
				$typeArray[$t['tid']] = ucwords($t['asset_type']);
			}
			$whrLoc = "l.asset_attribute_type='location'";
			$assetLocation = $em->createQuery('SELECT l.asset_master_id as lid, l.asset_attribute as asset_location FROM Application\Entity\Assetmaster l WHERE '.$whrLoc)->getArrayResult();
			$locationArray=array();
			foreach($assetLocation as $l){
				$locationArray[$l['lid']] = ucwords($l['asset_location']);
			}
			
			$valuesToSend['currentUser'] = $userArray;
			$valuesToSend['company'] = $companyArray;
			$valuesToSend['assetType'] = $typeArray;
			$valuesToSend['assetLocation'] = $locationArray;
			if(isset($primaryAssets) && sizeof($primaryAssets) > 0){
				$valuesToSend['primaryAssets'] = $primaryAssetsArray;
			}
			if(isset($secondaryAssetsArray) && sizeof($secondaryAssetsArray) > 0){
				$valuesToSend['secondaryAssets'] = $secondaryAssetsArray;
			}
			
			$viewModel=new ViewModel($valuesToSend);
			return $viewModel;
			//$valuesToSend=array('status' =>$status,'project'=>$project,'category'=>$category,'milestone'=>$milestone);
		}
	}
	
	public function assetcategoriesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}	
		$em = $this->getEntityManager();
		$id = $this->getRequest()->getPost('id');
		$newValue = $this->getRequest()->getPost('value');
		if($this->getRequest()->isPost()){
			$response = array();
			$newRecord = new \stdClass();
			if(isset($id) && $id > 0 && isset($newValue) && $newValue != ''){
				//UPDATING ASSET CATEGORY
				$newRecord = $em->find('Application\Entity\Assetmaster', $id);
			}
			elseif((!isset($id) || $id == '') && isset($newValue) && $newValue != ''){
				//ADDING ASSET CATEGORY
				$newRecord = new Assetmaster();
			}
			elseif(isset($id) && $id > 0 && (!isset($newValue) || $newValue == '')){
				$response=array();
				if(count($response)==0){
					$delete = $em->find('Application\Entity\Assetmaster', $id);
					if ($delete) {
						$this->getEntityManager()->remove($delete);
						$this->getEntityManager()->flush();
						$response['returnvalue']="valid";
					}
				}
				echo json_encode($response);
				exit;
			}
			$newRecord->setAssetAttribute($newValue);
			$newRecord->setAssetAttributeType('category');
			try{
				$em->persist($newRecord);
				$em->flush();
				$response['returnvalue']="valid";
			}
			catch (Exception $e){
				echo $e->getMessage();
				$response['returnvalue']="exception";
				exit;
			}
			echo json_encode($response);
			exit;
		}
		else{
			try {
				$categoryQuery = $em->createQuery("SELECT am.asset_master_id as catgid, 
					am.asset_attribute as asset_category 
					FROM Application\Entity\Assetmaster am 
					WHERE am.asset_attribute_type='category'");
			} catch(Exception $e) {
				echo $e->getMessage() . "<br/>" . $e->getTraceAsString();
				exit;
			}
			$categoryQuery = $categoryQuery->getResult();
			$categories = array();
			foreach($categoryQuery as $catg){
				$categories[$catg['catgid']] = $catg['asset_category'];
			}
			$categoryQuery['catg'] = $categories;
			$viewModel=new ViewModel($categoryQuery);
			return $viewModel;
		}
	}
	
	public function assetlocationsAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$em = $this->getEntityManager();
		$id = $this->getRequest()->getPost('id');
		$newValue = $this->getRequest()->getPost('value');
		if($this->getRequest()->isPost()){
			$response = array();
			$newRecord = new \stdClass();
			if(isset($id) && $id > 0 && isset($newValue) && $newValue != ''){
				//UPDATING ASSET LOCATION
				$newRecord = $em->find('Application\Entity\Assetmaster', $id);
			}
			elseif((!isset($id) || $id == '') && isset($newValue) && $newValue != ''){
				//ADDING ASSET LOCATION
				$newRecord = new Assetmaster();
			}
			elseif(isset($id) && $id > 0 && (!isset($newValue) || $newValue == '')){
				$response=array();
				if(count($response)==0){
					$delete = $em->find('Application\Entity\Assetmaster', $id);
					if ($delete) {
						$this->getEntityManager()->remove($delete);
						$this->getEntityManager()->flush();
						$response['returnvalue']="valid";
					}
				}
				echo json_encode($response);
				exit;
			}
			$newRecord->setAssetAttribute($newValue);
			$newRecord->setAssetAttributeType('location');
			try{
				$em->persist($newRecord);
				$em->flush();
				$response['returnvalue']="valid";
			}
			catch (Exception $e){
				echo $e->getMessage();
				$response['returnvalue']="exception";
				exit;
			}
			echo json_encode($response);
			exit;
		}
		else{
			try {
				$locationQuery = $em->createQuery("SELECT am.asset_master_id as locid, 
					am.asset_attribute as asset_location 
					FROM Application\Entity\Assetmaster am 
					WHERE am.asset_attribute_type='location'");
			} catch(Exception $e) {
				echo $e->getMessage() . "<br/>" . $e->getTraceAsString();
				exit;
			}
			$locationQuery = $locationQuery->getResult();
			$locations = array();
			foreach($locationQuery as $loc){
				$locations[$loc['locid']] = $loc['asset_location'];
			}
			$locationQuery['loc'] = $locations;
			$viewModel=new ViewModel($locationQuery);
			return $viewModel;
		}
	}
}















