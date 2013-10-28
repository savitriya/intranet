<?php
namespace Application\Controller;

use Application\Entity\Company;

use DoctrineModule\Options\Authentication;
use Doctrine\ORM\Query\Expr\Select;
use Application\Entity\Holiday;
use Application\Entity\Projects;
use Application\Entity\Activitycategories;
use Application\Entity\Activitystatuses;
use Application\Entity\Projectstatuses;
use Zend\Soap\Client\Common;
use IntranetUtils\AuthAdapter as AuthAdapter;
use IntranetUtils\Common as Misc;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use	Doctrine\ORM\EntityManager;
use DoctrineModule\Authentication\Adapter as DoctrineAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Doctrine\ORM\Query\Expr;
use Application\Entity\Login;
use Zend\Authentication\Storage;
use Zend\Authentication\Storage\Session as SessionStorage;


/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class AdminController extends AbstractActionController
{
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
	public function indexAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
	}
	public function getRepository()
	{
		return  $this->getEntityManager()->getRepository('Application\Entity\Activitycategories');
	}
	//activity_categories
	public function activitycategoriesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function gridactivitycategoriesAction(){
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
		if($sidx==""){
			$sidx="a.name";
		}
		else if($sidx=="name"){
			$sidx="a.name";
		}
		else if($sidx=="color"){
			$sidx="a.color";
		}
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery("SELECT a.id as id,a.name as name,a.color as color FROM Application\Entity\Activitycategories a order by $sidx $sord");
		$totalRecords = $countQuery->getResult();
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$i=0;
		foreach ($totalRecords as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($rws['name'],$rws['color']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function addactivitycategoriesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$name=$this->getRequest()->getPost('name');
			$color=$this->getRequest()->getPost('color');
			$add =new Activitycategories();
			$add->setName($name);
			$add->setColor($color);
			try{
				$em=$this->getEntityManager();
				$em->persist($add);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();exit;
			}exit;
		}
	}
	public function editactivitycategoriesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$name=$this->getRequest()->getPost('name');
			$color=$this->getRequest()->getPost('color');
			$em = $this->getEntityManager();
			$qb = $this->em->createQueryBuilder();
			$q = $qb->update('Application\Entity\Activitycategories ', 'a')
			->set('a.name', $qb->expr()->literal($name))
			->set('a.color', $qb->expr()->literal($color))
			->where('a.id = ?1')
			->setParameter(1, $id)
			->getQuery();
			$p = $q->execute();
		}
		exit;
	}
	public function deleteactivitycategoriesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$delete =$em->find('Application\Entity\Activitycategories', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}
	//activity status
	public function activitystatusesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function gridactivitystatusesAction(){
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
		if($sidx==""){
			$sidx="a.name";
		}
		else if($sidx=="name"){
			$sidx="a.name";
		}
		else if($sidx=="color"){
			$sidx="a.color";
		}
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery("SELECT a.id as id,a.name as name,a.color as color,a.is_default as sd FROM Application\Entity\Activitystatuses a order by $sidx $sord");
		$totalRecords = $countQuery->getResult();
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$i=0;
		foreach ($totalRecords as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($rws['name'],$rws['color'],$rws['sd']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function addactivitystatusesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$name=$this->getRequest()->getPost('name');
			$color=$this->getRequest()->getPost('color');
			$isDefault=$this->getRequest()->getPost('is_default');
			$add =new activitystatuses();
			$add->setName($name);
			$add->setColor($color);
			$add->setIs_default($isDefault);
			$em=$this->getEntityManager();
			Try{
				$em->persist($add);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();exit;
			}
		}exit;
	}
	public function editactivitystatusesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		//echo "edit new data good job ";exit;
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$name=$this->getRequest()->getPost('name');
			$color=$this->getRequest()->getPost('color');
			$isDefault=$this->getRequest()->getPost('is_default');
			$em = $this->getEntityManager();
			$qb = $this->em->createQueryBuilder();
			$q = $qb->update('Application\Entity\Activitystatuses ', 'a')
			->set('a.name', $qb->expr()->literal($name))
			->set('a.color', $qb->expr()->literal($color))
			->set('a.is_default', $qb->expr()->literal($isDefault))
			->where('a.id = ?1')
			->setParameter(1, $id)
			->getQuery();
			$p = $q->execute();
		}exit;
	}
	public function deleteactivitystatusesAction(){
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$delete =$em->find('Application\Entity\Activitystatuses', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}
	//project_statuses
	public function projectstatusesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function gridprojectstatusesAction(){
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
		if($sidx==""){
			$sidx="a.name";
		}
		else if($sidx=="name"){
			$sidx="a.name";
		}
		else if($sidx=="color"){
			$sidx="a.color";
		}
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery("SELECT a.id as id,a.name as name,a.color as color,a.is_default as in_default FROM Application\Entity\Projectstatuses a order by $sidx $sord");
		$totalRecords = $countQuery->getResult();
		//	print_r($totalRecords);exit;
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$i=0;
		foreach ($totalRecords as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($rws['name'],$rws['color'],$rws['in_default']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function addprojectstatusesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$response=array();
			$id=$this->getRequest()->getPost('id');
			$name=$this->getRequest()->getPost('name');
			$color=$this->getRequest()->getPost('color');
			$isDefault=$this->getRequest()->getPost('is_default');
			$em=$this->getEntityManager();
			if($id!="" && $id>0){
				$add=$em->find('Application\Entity\Projectstatuses', $id);
			}
			else{
				$add =new projectstatuses();
			}
			$add->setName($name);
			$add->setColor($color);
			$add->setIs_default($isDefault);

			Try{
				$em->persist($add);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();
				exit;
			}
		}exit;
	}
	public function deleteprojectstatusesAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$delete =$em->find('Application\Entity\Projectstatuses', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}
	public function holidayAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function addholidayAction(){
		$auth = new AuthenticationService();
		$common=new Misc();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost())
		{
			$id=$this->getRequest()->getPost('id');
			$flag=$this->getRequest()->getPost('flag');
			$em=$this->getEntityManager();

			if($flag!="view"){
				$response=array();
				$holiday=$this->getRequest()->getPost('holidayname');
				$holidaydate =$this->getRequest()->getPost('altholidaydate');
				//			$duplicateUser=$em->createQuery("SELECT h.id as id,h.date as date from Application\Entity\Holiday h Where h.holidayname=$holiday ")->getResult();
					
				if($holiday==""){
					$response['data']['holidayname']="null";
				}else {
					$response['data']['holidayname']="valid";
				}

				if($holidaydate==""){
					$response['data']['altholidaydate']="null";
				}else {
					$response['data']['altholidaydate']="valid";
				}
				$altholidaydate=strtotime($holidaydate)+$offset;
				//			if(count($duplicateUser)>0 ){
				//			$response['data']['holidayname']="duplicate";
				//			}
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				if(count($response)==0 ){
					$common=new Misc();
					$leave=new \stdClass();
					$isNewRecord=false;
					if($id!="" && $id>0){
						$leave=$em->find('Application\Entity\Holiday', $id);
					}
					else{
						$leave=new Holiday();
						$isNewRecord=true;
					}
					$leave->setEntityManager($em);
					$leave->setHolidayname($holiday);
					$leave->setDate($altholidaydate);
					try{
						$em->persist($leave);
						$em->flush();
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
						echo $e->getMessage();exit;
					}
				}
				header("Content-type: application/json");
				echo json_encode($response);
				exit;
			}
			else{
				$valuesToSend=array();
				if(isset($id) && $id>0){
					$holiday=$em->find('Application\Entity\Holiday',$id);
					$valuesToSend['holiday']=$holiday;
				}
				$viewModel=new ViewModel($valuesToSend);
				$viewModel->setTerminal(true);
				return $viewModel;
					
			}
		}
	}
	public function gridholidayAction()
	{
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
		if($sidx==""){
			$sidx="h.holidayname";
		}
		else if($sidx=="holiday"){
			$sidx="h.holidayname";
		}
		else if($sidx=="date"){
			$sidx="h.date";
		}
		$em=$this->getEntityManager();
		$query = $em->createQuery("SELECT h.id as id,h.holidayname as holiday,h.date as date FROM Application\Entity\Holiday h order by $sidx $sord");
		$holidayquery= $query->getResult();
		$i=0;
		$response=array();
		foreach ($holidayquery as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$date=$rws['date'];
			$date=date("Y-m-d H:i:s",$date);
			$common=new Misc();
			$date=$common->ConvertGMTToLocalTimezone($date,"Asia/Calcutta","d/m/Y");
			$response['rows'][$i]['cell']=array($rws['holiday'],$date);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function deleteholidayAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$delete =$em->find('Application\Entity\Holiday', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}

	}