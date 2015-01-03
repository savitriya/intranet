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
use Application\Entity\UserSkills;
use Application\Entity\Skills;
use Application\Entity\Allocation;
use Application\Entity\ResourceAllocation;
use Application\Entity\User;
use Zend\Db\Adapter\Adapter as Adapter;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ResourceplanningController extends AbstractActionController
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
		$em = $this->getEntityManager();
		$auth = new AuthenticationService();
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$common =new Misc();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
// 		if($auth->getIdentity()->isadmin!=1){
// 			return $this->redirect()->toRoute('home');
// 		}
		$em= $this->getEntityManager();
// 		$query = $em->createQuery("SELECT us.user_id,us.duration,us.allocation_date,u.id,u.fname FROM Application\Entity\ResourceAllocation us LEFT JOIN Application\Entity\User u  WITH u.id=us.user_id where u.id NOT IN us.user_id ORDER BY us.allocation_date,us.user_id")->getResult();
		$allocationQuery = $em->createQuery("SELECT ra.id as id,ra.allocation_date as date,ra.duration as duration,ra.project_id as pid,p.name as pname,u.id as userid,u.fname as fname,u.lname as lname FROM Application\Entity\ResourceAllocation ra LEFT JOIN Application\Entity\Projects p WITH ra.project_id=p.id LEFT JOIN Application\Entity\User u WITH ra.user_id=u.id  WHERE ra.allocation_date> 19800 ORDER BY ra.allocation_date ASC");
// 		->setFirstResult( $start )
// 		->setMaxResults( $limit );
		$totalrows = $allocationQuery->getResult();
		
		$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		$userslist=array();
		foreach ($users as $rw){
			if(!in_array($rw->__get('id'),$userslist)){
				array_push($userslist, $rw->__get('id'));
			}
		}
		$usersArray=array();
		$allocationarray = array();
		$datearray=array();
		$freeuser=array();
		if(count($totalrows)>0){
			foreach ($totalrows as $row){
			if(!in_array($row['date'],$datearray)){
				array_push($datearray, $row['date']);
				$dateRecordIndex=array_search($row['date'],$datearray);
				$allocationarray[$dateRecordIndex]['date']=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$row['date']), "Asia/Calcutta","Y-m-d");       //date("Y-m-d",$row['date']);
				$allocationarray[$dateRecordIndex]['id']=$row['id'];
			}
			$dateRecordIndex=array_search($row['date'],$datearray);
			
			if(!isset($allocationarray[$dateRecordIndex]['userarray'])){
				$allocationarray[$dateRecordIndex]['userarray']=array();
			}
	
			if(!isset($allocationarray[$dateRecordIndex]['freeuser'])){
				$allocationarray[$dateRecordIndex]['freeuser']=array();
			}
// 			user array
			if(!isset($allocationarray[$dateRecordIndex]['user'])){
				$allocationarray[$dateRecordIndex]['user']=array();
			}
			$data=(array)$allocationarray[$dateRecordIndex]['user'];
			$userrRecordIndex=-1;
			for($i=0;$i<count($data);$i++){
				if($data[$i]['id']==$row['userid']){
					$userrRecordIndex=$i;
					break;
				}
			}
			
			if($userrRecordIndex==-1){
				$userrRecordIndex=count($data);
				$userid=$row['userid'];
				
				$result = $em->createQuery("SELECT s.name as skill,us.user_id as uid FROM Application\Entity\Skills s  LEFT JOIN Application\Entity\UserSkills us WITH us.skill_id=s.id where us.user_id= $userid")->getResult();
				$skills="";
				foreach ($result as $rw){
					if ($skills == "") {
					$skills =$rw['skill'];
					}else {
						$skills .=",".$rw['skill'];
					}
				}	
				$allocationarray[$dateRecordIndex]['userarray'][$userrRecordIndex]=$row['userid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['userskills']=$skills;
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['name']=$row['fname']." ".$row['lname'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['id']=$row['userid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['duration'] = $row['duration'];
				$allocationarray[$dateRecordIndex]['userduration']="";
			}
			$allocationarray[$dateRecordIndex]['userduration']+=$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['duration'];

			
// 			project array sub array of user array
			if(!isset($allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'])){
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project']=array();
			}
				
			$projectdata=(array)$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'];
			$projectRecordIndex=-1;
			for($i=0;$i<count($projectdata);$i++){
				if($projectdata[$i]['id']==$row['userid']){
					$projectRecordIndex=$i;
					break;
				}
			}
			if($projectRecordIndex==-1){
				$projectRecordIndex=count($projectdata);
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['resourceallocationid']=$row['id'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['name']=$row['pname'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['id']=$row['pid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['duration']=$row['duration'];
			}

			}
		
		}
		$i=0;
		$freeusertemp=array();
		$freeuserskill=array();
		
		foreach ($allocationarray as $rw){
			$freeuserid="";
			$freeusertemp=array_diff($userslist,$rw['userarray']);
			foreach ($freeusertemp as $frw){
				
				if($freeuserid == ""){
					$freeuserid =$frw;
				}else {
					$freeuserid .= ",".$frw;
				}
			}
			$result = $dbAdapter->query("SELECT u.id as id,u.fname as fname,u.lname as lname,GROUP_CONCAT(s.name) as skills,GROUP_CONCAT(s.id) as skills_id,us.user_id as uid FROM  user u  LEFT JOIN user_skills_mapper us ON us.user_id=u.id  LEFT JOIN  skills s ON s.id=us.skill_id Where u.isactive=1  AND u.id IN($freeuserid) GROUP BY u.id",Adapter::QUERY_MODE_EXECUTE);
			$j=0;
			foreach ($result as $result){
				
				
				 $allocationarray[$i]['freeuser'][$j]['id']=$result['id'];
				 $allocationarray[$i]['freeuser'][$j]['fname']=$result['fname'];
				 $allocationarray[$i]['freeuser'][$j]['lname']=$result['lname'];
				 $skillTitle=explode(",",$result['skills']);
				 $skillId=explode(",",$result['skills_id']);
				 $s=0;
				 $allocationarray[$i]['freeuser'][$j]['skills']=array();
				 $skilldata=$allocationarray[$i]['freeuser'][$j]['skills'];
				 foreach ($skillId as $rw){
				 	if(isset($skillId[$s])  && !in_array($skillId[$s],$skilldata) && $skillId[$s]>0) {
				 		array_push($skilldata, $skillId[$s]);
				 		$skillRecordIndex=array_search($skillId[$s],$skilldata);
				 $allocationarray[$i]['freeuser'][$j]['skills'][$skillRecordIndex]['id']=$skillId[$s];
				 $allocationarray[$i]['freeuser'][$j]['skills'][$skillRecordIndex]['title']=$skillTitle[$s];
				 
				 	}
				 	$s++;
				 }
				 $j++;
			}
			$i++;
		}
		$skills=$em->getRepository('Application\Entity\Skills')->getSkills('ASC');
		$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		return new ViewModel(array('totalrows'=>$totalrows, 'allocationarray' => $allocationarray,'project' =>$project,'skills'=>$skills));
		exit;	
	}
	
	
	public function addskillsAction(){
		$auth = new AuthenticationService();
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
			if($auth->getIdentity()->isadmin!=1){
				return $this->redirect()->toRoute('home');
			}
			$auth = new AuthenticationService();
		
			$response =array();
			$em=$this->getEntityManager();
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
			if($auth->getIdentity()->isadmin ==1){
			if($this->getRequest()->isXmlHttpRequest()){
		
				$skills =$this->getRequest()->getPost('skills');
				$Obj = new Skills();
				$Obj->setName($skills);
				try{
					$em->persist($Obj);
					$em->flush();
					$response['returnvalue']="valid";
					echo json_encode($response);
					exit;
				}
				catch (Exception $e)
				{
					$response['returnvalue']="exception";
					echo $e->getMessage();exit;
				}
				
			}
			
		}
		
	}
	
	public function skillssetsAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$em=$this->getEntityManager();
		$usersskills =array();
		$result = $dbAdapter->query("SELECT u.id as id,u.fname as fname,u.lname as lname,s.name as skill,s.id as skill_id, us.user_id as uid FROM  user u  LEFT JOIN user_skills_mapper us ON us.user_id=u.id  LEFT JOIN  skills s ON s.id=us.skill_id Where u.isactive=1 ",Adapter::QUERY_MODE_EXECUTE);
		$userArray=array();
	foreach ($result as  $result){
	
	if(!in_array($result['id'],$userArray)){
		array_push($userArray, $result['id']);
		$userRecordIndex=array_search($result['id'],$userArray);
		$usersskills[$userRecordIndex]['id']=$result['id'];
		$usersskills[$userRecordIndex]['name']=ucwords($result['fname']." ".$result['lname']);
	}
	$userRecordIndex=array_search($result['id'],$userArray);
		
	if(!isset($usersskills[$userRecordIndex]['skills'])){
		$usersskills[$userRecordIndex]['skills']=array();
	}
	
	
	
	$data=(array)$usersskills[$userRecordIndex]['skills'];
	$skillsRecordIndex=-1;
	for($i=0;$i<count($data);$i++){
		if($data[$i]['id']==$result['skill_id']){
			$skillsRecordIndex=$i;
			break;
		}
	}
	if($skillsRecordIndex==-1){
		$skillsRecordIndex=count($data);
		
		$usersskills[$userRecordIndex]['skills'][$skillsRecordIndex]['skill']=$result['skill'];
		$usersskills[$userRecordIndex]['skills'][$skillsRecordIndex]['id']=$result['skill_id'];
	}
	
}

	
		$skills=$em->getRepository('Application\Entity\Skills')->getSkills('ASC');
		return new ViewModel(array('userskills' =>$usersskills,'skills' =>$skills));
	}
	
	
	public function adduserskillsAction(){
		$auth = new AuthenticationService();
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
			if($auth->getIdentity()->isadmin!=1){
				return $this->redirect()->toRoute('home');
			}
	
		$response =array();
		$em=$this->getEntityManager();
		
		if($this->getRequest()->isXmlHttpRequest()){

					$skills =$this->getRequest()->getPost('skills');
					$userId =$this->getRequest()->getPost('userid');
					$delete ="";
					if(isset($userId) && $userId != ""){
					$delete =$em->createQuery("SELECT us FROM Application\Entity\UserSkills us where us.user_id=$userId")->getResult();
					
					if(isset($delete)){
					foreach ($delete as $delete)
						$this->getEntityManager()->remove($delete);
						$this->getEntityManager()->flush();
					}
					}
					if(isset($skills)){
					foreach($skills as $row){
						$Obj = new UserSkills();
						$Obj->setUserId($userId);
						$Obj->setSkillId($row);
						try{
							$em->persist($Obj);
							$em->flush();
							$response['returnvalue']="valid";
						}
						catch (Exception $e)
						{
							$response['returnvalue']="exception";
							echo $e->getMessage();exit;
						}
					}
					}
					echo json_encode($response);
					exit;
					
		}
					
				$skills=$em->getRepository('Application\Entity\Skills')->getSkills('ASC');
				$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
				return new ViewModel(array('users' =>$users, 'skills' =>$skills));
			}
		
	
	
	public function getskillsAction(){
		
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
			$q = $_GET['term'];
			$data=array();
			$em=$this->getEntityManager();
			$query=trim($q);
		
			$skills=$em->createQuery("Select s.id as id,s.name as name from Application\Entity\Skills s Where s.name like '".$query."%' ");
			//echo $user->getSQL();exit;
			$serchuser = $skills->getResult();
			$i=0;
			foreach($serchuser as $rws){
				$data[$i]['name'] = ucwords($rws['name']);
				$data[$i]['value'] = '"'. ucwords($rws['name']);
				$i++;
		
			}
			echo json_encode($data);exit;
		
		
	
	}
	
	
	
	public function resourceallocationAction(){
		$auth = new AuthenticationService();
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
// 			if($auth->getIdentity()->isadmin!=1){
// 				return $this->redirect()->toRoute('home');
// 			}
		$em = $this->getEntityManager();
		$comanys = $em->createQuery("SELECT c.id as id,c.name as name FROM Application\Entity\Company c order by c.name ASC")->getArrayResult();
		$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		return new ViewModel(array('comanys'=>$comanys,'project' =>$project,'users' =>$users));
	}
	
	public function gridresourceallocationAction(){
		$common =new Misc();
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
// 		if($auth->getIdentity()->isadmin!=1){
// 			return $this->redirect()->toRoute('home');
// 		}
		$page = $this->getRequest()->getPost('page');
		$limit =$this->getRequest()->getPost('rows');
		$sidx = $this->getRequest()->getPost('sidx');
		$sord = $this->getRequest()->getPost('sord');
		
		
		if($page > 0){
			$start = ($limit * $page) - $limit;
		}
		else{
			$start = 0;
		}
		
		if($sidx==""){
			$sidx="ra.id";
		}
		else if($sidx=="user"){
			$sidx="u.fname";
		}
		else if($sidx=="project"){
			$sidx="ra.project_id";
		}
		else if($sidx=="allocation_date"){
			$sidx="ra.allocation_date";
		}
		else if($sidx=="duration"){
			$sidx="ra.duration";
		}
		

// 		Filter by company , Project,User & date
		$companyid=$this->getRequest()->getPost('companyid');
		$projectid=$this->getRequest()->getPost('projectid');
		$userid = $this->getRequest()->getPost('userid');
		$date = $this->getRequest()->getPost('altdate');
		if(isset($date) && trim($date)!=""){
			$date =$common->ConvertLocalTimezoneToGMT($date." 00:00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$date= strtotime($date);
		}
		
		$where="";
		if(isset($companyid) && trim($companyid) !=""){
			if($where==""){
				$where="p.company_id=$companyid";
			}
			else{
				$where.=" AND p.company_id=$companyid";
			}
		}
		if (isset($projectid) && trim($projectid)!=""){
			if($where==""){
				$where="ra.project_id=$projectid";
			}
			else{
				$where.=" AND ra.project_id=$projectid";
			}
		}
		
		if (isset($userid) && trim($userid)!=""){
			if($where==""){
				$where="ra.user_id=$userid";
			}
			else{
				$where.=" AND ra.user_id=$userid";
			}
		}
		if (isset($date) && trim($date)!=""){
			if($where==""){
					
				$where="ra.allocation_date=$date";
			}
			else{
				$where.=" AND ra.allocation_date=$date";
			}
		}
		
		if($where==""){
			$where="1=1";
		}
		$em = $this->getEntityManager();
		$allocationQuery = $em->createQuery("SELECT ra.id as id FROM Application\Entity\ResourceAllocation ra LEFT JOIN Application\Entity\Projects p WITH ra.project_id=p.id  WHERE  $where");
		$totalRecords = $allocationQuery->getResult();
		
		$totalPages = 0;
		if (count($totalRecords) > 0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$allocationQuery = $em->createQuery("SELECT ra.id as id,ra.duration as duration,ra.allocation_date as date,p.name as pname,u.fname as fname FROM Application\Entity\ResourceAllocation ra LEFT JOIN Application\Entity\Projects p WITH ra.project_id=p.id LEFT JOIN Application\Entity\User u WITH ra.user_id=u.id WHERE $where order by $sidx $sord")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$totalrows = $allocationQuery->getResult();
		$i=0;
		$common =new Misc();
		foreach ($totalrows as $rws){
			$allocation_date=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['date']), "Asia/Calcutta","d/m/Y");
			//"<a href='javascript:addResourceAllocation(".$rws['id'].')'."'><i class='icon-edit'></i></a>
			$action="&nbsp;&nbsp&nbsp;<a href='javascript:deleteResourceAllocation(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['fname']),ucwords($rws['pname']),$allocation_date,$rws['duration'],$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	
	public function addresourceallocationAction(){
		$auth = new AuthenticationService();
		$common =new Misc();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
			
			$em = $this->getEntityManager();
			$response = array();
		
			if($this->getRequest()->isPost())
			{
				$project_id=$this->getRequest()->getPost('projectid');
				if($project_id==""){
					$response['data']['projectid']="null";
				}
				else{
					$response['data']['projectid']="valid";
				}
				$user_id = $this->getRequest()->getPost('userid');
				if($user_id==""){
					$response['data']['userid']="null";
				}
				else{
					$response['data']['userid']="valid";
				}
				$duration= $this->getRequest()->getPost('duration');
// 				if($duration==""){
// 					$response['data']['duration']="null";
// 				}
// 				else{
// 					$response['data']['duration']="valid";
// 				}
				
				$perdayduration=$this->getRequest()->getPost('perdayduration');
				if($perdayduration==""){
					$response['data']['perdayduration']="null";
				}
				else{
					$response['data']['perdayduration']="valid";
				}
				$startdate=$this->getRequest()->getPost('startdate');
				if($startdate==""){
					$response['data']['startdate']="null";
				}
				else{
					$response['data']['startdate']="valid";
				}
				$enddate=$this->getRequest()->getPost('enddate');
// 				if($enddate==""){
// 					$response['data']['enddate']="null";
// 				}
// 				else{
// 					$response['data']['enddate']="valid";
// 				}
		
				
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']) )){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				if(count($response)==0){
					
					$startdateObj = date_create($startdate);

					if($duration>0 && $perdayduration>0)
					$day=(int)($duration/$perdayduration);
					$remainigDay=$duration%$perdayduration;
					if (isset($remainigDay) && $remainigDay>0){
						$day=$day;
					}else{
					$day=$day-1;
					}
					
					$calEnddate= date('Y-m-d', strtotime($startdate. " + $day days"));
					
					$startdateStr=strtotime($startdate) + $offset;
					$enddateStr=strtotime($calEnddate) + $offset;
					
					$holiday = $em->createQuery("SELECT h.id as id,h.holidayname as holiday,h.date as date FROM Application\Entity\Holiday h where  h.date BETWEEN $startdateStr AND $enddateStr");
					$holiday = $holiday->getArrayResult();
					
					$holiday=count($holiday);
					$calEnddate = date('Y-m-d', strtotime($calEnddate. " + $holiday days"));
					$holidayarray=array();
					$whileholiday=0;
					while ($whileholiday != $holiday){
						$enddateStr=strtotime($calEnddate) + $offset;
						$holidayarray = $em->createQuery("SELECT h.id as id,h.holidayname as holiday,h.date as date FROM Application\Entity\Holiday h where  h.date BETWEEN $startdateStr AND $enddateStr");
						$holidayarray = $holidayarray->getArrayResult();
						$whileholiday=count($holidayarray);
						if ($whileholiday!=$holiday && $whileholiday>$holiday){
							$add=$whileholiday-$holiday;
							$holiday=$whileholiday;
							$calEnddate= date('Y-m-d', strtotime($calEnddate. " + $add days"));
						}else{
							$whileholiday=0;
							$holiday=0;
						}
					}
					
					$enddateObj = date_create($calEnddate);
					$enddateStr= strtotime($calEnddate) + $offset;
					$diff = date_diff($startdateObj, $enddateObj);
					$datediff = $diff->format("%a") ;
					
					$dateArray = array();
					$i=0;
					
					for($i=0;$i<= $datediff;$i++){
							
						$perday= $startdateStr + $i*60*60*24;
						if(!in_array($perday,$dateArray)){
							array_push($dateArray, $perday);
							
						}
					}
					
					if (count($holidayarray)==0) {
						$holidayarray = $em->createQuery("SELECT h.id as id,h.holidayname as holiday,h.date as date FROM Application\Entity\Holiday h where  h.date BETWEEN $startdateStr AND $enddateStr");
						$holidayarray = $holidayarray->getArrayResult();
					}
					$leaveArray=array();
					foreach ($holidayarray as $leaveRow){
						if(!in_array($leaveRow['date'],$leaveArray)){
							array_push($leaveArray, $leaveRow['date']);
						}
					}
					
					$allocationdayarray=array();
					$allocationdayarray = array_diff($dateArray,$leaveArray);
					$p=0;
					
				foreach ($allocationdayarray as $add){
					$resourceallocatioTable =new ResourceAllocation();
				$resourceallocatioTable->setProject_id($project_id);
				$resourceallocatioTable->setUser_id($user_id);
				if (isset($remainigDay) && $remainigDay >0 && (count($allocationdayarray)-1) == $p){
					$resourceallocatioTable->setDuration($remainigDay);
				}else{
				$resourceallocatioTable->setDuration($perdayduration);
				}
				$resourceallocatioTable->setAllocation_date($add);
				try{
					$em->persist($resourceallocatioTable);
					$em->flush();
					$p++;
					$response['returnvalue']="valid";
				}
				catch (Exception $e)
				{
					$response['returnvalue']="exception";
					echo $e->getMessage();exit;
				}
				
				}
			}
		}
		
			echo json_encode($response);
			exit;
		
	}
	
	public function deleteresourceallocationAction(){
		$auth = new AuthenticationService();
			if(!$auth->hasIdentity()){
				return $this->redirect()->toRoute('home');
			}
// 			if($auth->getIdentity()->isadmin!=1){
// 				return $this->redirect()->toRoute('home');
// 			}
		$em=$this->getEntityManager();
		$response=array();
		$id=$this->getRequest()->getPost('id');
		if(!isset($id) && $id ==""){
			$response['data']['id']="null";
		}
		if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
			$response['returnvalue']="invalid";
		}
		else{
			$response=array();
		}
		if(count($response)==0){
			$delete =$em->find('Application\Entity\ResourceAllocation', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue']="valid";
			}
		}
		echo json_encode($response);
		exit;
		
	}
	
	
	
	
	public function filterallocationAction(){
		$date=$this->getRequest()->getPost('date');
		$type=$this->getRequest()->getPost('userid');
		 echo $date;exit;
		
		$allocation = $em->createQuery("SELECT ra.duration as duration,ra.project_id as project_id,p.name as project_name from Application\Entity\ResourceAllocation ra JOIN Application\Entity\Projects p WITH p.id=ra.project_id where ra.user_id=$userId AND ra.allocation_date=$createdDate ")->getArrayResult();

		
	}
	
	
	public function allocationAction(){
		$date=$this->getRequest()->getPost('date');
	 	$type=$this->getRequest()->getPost('type');
// 	 	$date="2014-06-24";
// 	 	$type="free";
		$em = $this->getEntityManager();
		$auth = new AuthenticationService();
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$common =new Misc();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em= $this->getEntityManager();
		$date =strtotime($date);
		$date=$offset+$date;
		$totalrows =array();
// 		$query = $em->createQuery("SELECT us.user_id,us.duration,us.allocation_date,u.id,u.fname FROM Application\Entity\ResourceAllocation us LEFT JOIN Application\Entity\User u  WITH u.id=us.user_id where u.id NOT IN us.user_id ORDER BY us.allocation_date,us.user_id")->getResult();
		if(isset($date) && $date > 0){
		$allocationQuery = $em->createQuery("SELECT ra.id as id,ra.allocation_date as date,ra.duration as duration,ra.project_id as pid,p.name as pname,u.id as userid,u.fname as fname,u.lname as lname FROM Application\Entity\ResourceAllocation ra LEFT JOIN Application\Entity\Projects p WITH ra.project_id=p.id LEFT JOIN Application\Entity\User u WITH ra.user_id=u.id  WHERE ra.allocation_date =$date ORDER BY ra.allocation_date ASC")
// 		->setFirstResult( $start )
		->setMaxResults(10 );
		$totalrows = $allocationQuery->getResult();
		}
		$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		$userslist=array();
		foreach ($users as $rw){
			if(!in_array($rw->__get('id'),$userslist)){
				array_push($userslist, $rw->__get('id'));
			}
		}
		
		$usersArray=array();
		$allocationarray = array();
		$datearray=array();
		$freeuser=array();
		if(count($totalrows)>0){
			foreach ($totalrows as $row){
			if(!in_array($row['date'],$datearray)){
				array_push($datearray, $row['date']);
				$dateRecordIndex=array_search($row['date'],$datearray);
				$allocationarray[$dateRecordIndex]['date']=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$row['date']), "Asia/Calcutta","Y-m-d");       //date("Y-m-d",$row['date']);
				$allocationarray[$dateRecordIndex]['id']=$row['id'];
			}
			$dateRecordIndex=array_search($row['date'],$datearray);
			
			if(!isset($allocationarray[$dateRecordIndex]['userarray'])){
				$allocationarray[$dateRecordIndex]['userarray']=array();
			}
	
			if(!isset($allocationarray[$dateRecordIndex]['freeuser'])){
				$allocationarray[$dateRecordIndex]['freeuser']=array();
			}
// 			user array
			if(!isset($allocationarray[$dateRecordIndex]['user'])){
				$allocationarray[$dateRecordIndex]['user']=array();
			}
			$data=(array)$allocationarray[$dateRecordIndex]['user'];
			$userrRecordIndex=-1;
			for($i=0;$i<count($data);$i++){
				if($data[$i]['id']==$row['userid']){
					$userrRecordIndex=$i;
					break;
				}
			}
			if($userrRecordIndex==-1 ){
				$userrRecordIndex=count($data);
				$userid=$row['userid'];
				$result = $em->createQuery("SELECT s.name as skill,s.id as id FROM Application\Entity\Skills s  LEFT JOIN Application\Entity\UserSkills us WITH us.skill_id=s.id where us.user_id= $userid")->getResult();
				$skills="";
				foreach ($result as $rw){
					if ($skills == "") {
					$skills =$rw['skill'];
					}else {
						$skills .=",".$rw['skill'];
					}
				}	
				$allocationarray[$dateRecordIndex]['userarray'][$userrRecordIndex]=$row['userid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['skills']=$result;
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['name']=$row['fname']." ".$row['lname'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['id']=$row['userid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['duration'] = $row['duration'];
				$allocationarray[$dateRecordIndex]['userduration']="";
			}
			$allocationarray[$dateRecordIndex]['userduration']+=$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['duration'];

			
// 			project array sub array of user array
			if(!isset($allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'])){
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project']=array();
			}
				
			$projectdata=(array)$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'];
			$projectRecordIndex=-1;
			for($i=0;$i<count($projectdata);$i++){
				if($projectdata[$i]['id']==$row['userid']){
					$projectRecordIndex=$i;
					break;
				}
			}
			if($projectRecordIndex==-1){
				$projectRecordIndex=count($projectdata);
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['resourceallocationid']=$row['id'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['name']=$row['pname'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['id']=$row['pid'];
				$allocationarray[$dateRecordIndex]['user'][$userrRecordIndex]['project'][$projectRecordIndex]['duration']=$row['duration'];
			}

			}
		
		}
		$i=0;
		$freeusertemp=array();
		$freeuserskill=array();
		
		foreach ($allocationarray as $rw){
			$freeuserid="";
			$freeusertemp=array_diff($userslist,$rw['userarray']);
// 			print_r($freeusertemp);
			foreach ($freeusertemp as $frw){
				
				if($freeuserid == ""){
					$freeuserid =$frw;
				}else {
					$freeuserid .= ",".$frw;
				}
			}
			$result = $dbAdapter->query("SELECT u.id as id,u.fname as fname,u.lname as lname,GROUP_CONCAT(s.name) as skills,GROUP_CONCAT(s.id) as skills_id,us.user_id as uid FROM  user u  LEFT JOIN user_skills_mapper us ON us.user_id=u.id  LEFT JOIN  skills s ON s.id=us.skill_id Where u.isactive=1  AND u.id IN($freeuserid) GROUP BY u.id",Adapter::QUERY_MODE_EXECUTE);
			$j=0;
			foreach ($result as $result){
				
				
				 $allocationarray[$i]['freeuser'][$j]['id']=$result['id'];
				 $allocationarray[$i]['freeuser'][$j]['fname']=$result['fname'];
				 $allocationarray[$i]['freeuser'][$j]['lname']=$result['lname'];
				 $skillTitle=explode(",",$result['skills']);
				 $skillId=explode(",",$result['skills_id']);
				 $s=0;
				 $allocationarray[$i]['freeuser'][$j]['skills']=array();
				 $skilldata=$allocationarray[$i]['freeuser'][$j]['skills'];
				 foreach ($skillId as $rw){
				 	if(isset($skillId[$s])  && !in_array($skillId[$s],$skilldata) && $skillId[$s]>0) {
				 		array_push($skilldata, $skillId[$s]);
				 		$skillRecordIndex=array_search($skillId[$s],$skilldata);
				 $allocationarray[$i]['freeuser'][$j]['skills'][$skillRecordIndex]['id']=$skillId[$s];
				 $allocationarray[$i]['freeuser'][$j]['skills'][$skillRecordIndex]['title']=$skillTitle[$s];
				 
				 	}
				 	$s++;
				 }
				 $j++;
			}
			$i++;
		}
// 		print_r($allocationarray);exit;
			if($type=="allfree"){
		$result = $dbAdapter->query("SELECT u.id as id,u.fname as fname,u.lname as lname,GROUP_CONCAT(s.name) as skills,GROUP_CONCAT(s.id) as skills_id,us.user_id as uid FROM  user u  LEFT JOIN user_skills_mapper us ON us.user_id=u.id  LEFT JOIN  skills s ON s.id=us.skill_id Where u.isactive=1  GROUP BY u.id",Adapter::QUERY_MODE_EXECUTE);
		$j=0;
		$freeuser =array();
		foreach ($result as $result){
		
		
			$freeuser[$j]['id']=$result['id'];
			$freeuser[$j]['fname']=$result['fname'];
			$freeuser[$j]['lname']=$result['lname'];
			$skillTitle=explode(",",$result['skills']);
			$skillId=explode(",",$result['skills_id']);
			$s=0;
			$freeuser[$j]['skills']=array();
			$skilldata=$freeuser[$j]['skills'];
			foreach ($skillId as $rw){
				if(isset($skillId[$s])  && !in_array($skillId[$s],$skilldata) && $skillId[$s]>0) {
					array_push($skilldata, $skillId[$s]);
					$skillRecordIndex=array_search($skillId[$s],$skilldata);
					$freeuser[$j]['skills'][$skillRecordIndex]['id']=$skillId[$s];
					$freeuser[$j]['skills'][$skillRecordIndex]['title']=$skillTitle[$s];
						
				}
				$s++;
			}
			$j++;
		}
	}
		
		
		
		
// 		echo $type;exit;
		
		$skills=$em->getRepository('Application\Entity\Skills')->getSkills('ASC');
		$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		$viewModel=new ViewModel(array('type'=>$type, 'totalrows'=>$totalrows, 'allocationarray' => $allocationarray,'project' =>$project,'freeuser' =>$freeuser,'skills'=>$skills,'planningdate'=>$date));
		$viewModel->setTerminal(true);
		return $viewModel;
		exit;	
			
	}
	
	public function freeallocationalert(){
		$common = new Common();
				
		
		$common->sendEmail("Testing Purpose Status Report For $templateActivityDate",html_entity_decode($finalContent),$mail,'',null,null,null);
	}
	
}