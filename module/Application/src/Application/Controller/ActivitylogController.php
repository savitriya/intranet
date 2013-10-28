<?php
namespace Application\Controller;

use	Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Decoder;
use Zend\Authentication\AuthenticationService;
use IntranetUtils\AuthAdapter as AuthAdapter;
use IntranetUtils\Common as Misc;
use Zend\Config\Reader\Ini;
use DoctrineModule\Authentication\Adapter as DoctrineAuthAdapter;
use Zend\Authentication\Result;
use Doctrine\ORM\Query\Expr;
use Application\Entity\User;
use Application\Entity\Login;
use Application\Entity\ActivityLog;
use Application\Entity\Projects;
use Application\Entity\Activities;
use Application\Entity\Milestones;
use Zend\Authentication\Storage;
use Application\Entity\Assignee;
use Zend\Authentication\Storage\Session as SessionStorage;



/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ActivitylogController extends AbstractActionController
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

	public function gridactivitylogAction(){
		$auth = new AuthenticationService();
		$page = $this->getRequest()->getPost('page');
		$limit =$this->getRequest()->getPost('rows');
		$sidx = $this->getRequest()->getPost('sidx');
		$sord = $this->getRequest()->getPost('sord');
		$activityId = $this->params('activityid');
		$userid = $this->getRequest()->getPost('seluser');
		$altdate = $this->getRequest()->getPost('alternate_adate');
		if($page > 0){
			$start = ($limit * $page) - $limit; // do not put $limit*($page - 1)
		}
		else {
			$start = 0;
		}
		if($sidx==""){
			$sidx="al.activity_date";
		}
		else if($sidx=="activity_date"){
			$sidx="al.activity_date";
		}
		else if($sidx=="username"){
			$sidx="u.fname";
		}
		else if($sidx=="category"){
			$sidx="ac.name";
		}
		else if($sidx=="created_datetime"){
			$sidx="al.created_datetime";
		}
		else if($sidx=="seconds_spent"){
			$sidx="al.seconds_spent";
		}
		$where="";
		if(isset($activityId) && $activityId>0){
			$where="al.activity_id=".$activityId;
		}
		if (isset($userid) && trim($userid)!=""){
			if($where==""){
				$where="al.user_id=$userid";
			}
			else{
				$where.=" AND al.user_id=$userid";
			}
		}
		if (isset($altdate) && trim($altdate)!=""){
			$common =new Misc();
			$altdate=$common->ConvertLocalTimezoneToGMT($altdate." 00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$altactivitydate=strtotime($altdate);
			if($where==""){
				$where="al.activity_date=$altactivitydate";
			}
			else{
				$where.=" AND al.activity_date=$altactivitydate";
			}
		}
		if($where==""){
			$where="1=1";
		}
		//echo $where;exit;
		$em = $this->getEntityManager();

		//print_r($TotaSpentTime);exit;
		$countQuery = $em->createQuery('SELECT al.id as id,al.user_id as uid,al.project_id as pid,al.milestone_id as mid,al.activity_id as aid,al.description as detail,al.category_id as acid,al.created_datetime as cdate,al.activity_date as adate,al.seconds_spent as totalseconds FROM Application\Entity\ActivityLog al Where '.$where);
		$totalRecords = $countQuery->getResult();
			

		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();

		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$aa=date("Y-m-d");
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery("SELECT al.id as id,p.name as pname,m.name as mname,u.fname as fname,u.lname as lname,a.subject as subject,al.description as detail,al.created_datetime as cdatetime,al.activity_date as adate,ac.name as categoryname,al.seconds_spent as totalseconds From Application\Entity\ActivityLog al JOIN Application\Entity\User u WITH al.user_id=u.id JOIN Application\Entity\Projects p WITH p.id=al.project_id
				LEFT JOIN Application\Entity\Milestones m WITH m.id=al.milestone_id LEFT JOIN Application\Entity\Activitycategories ac WITH ac.id=al.category_id JOIN Application\Entity\Activities a WITH a.id=al.activity_id  Where $where order by $sidx $sord")
				->setFirstResult( $start )
				->setMaxResults( $limit );
		$totalrows = $countQuery->getResult();
		$common =new Misc();

		$i=0;
		foreach ($totalrows as $rws){
			$action="<a href='javascript:addactivitylog(".$rws['id'].')'."'><i class='icon-edit'></i></a>&nbsp;<a href='javascript:deleteactivitylog(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$cdate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['cdatetime']), "Asia/Calcutta");
			$adate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['adate']), "Asia/Calcutta","d/m/Y");

			$spenttime=$common->convertSpentTime($rws['totalseconds']);
			if(isset($activityId) && $activityId>0){
				$response['rows'][$i]['cell']=array($rws['id'],ucwords($rws['fname'] . " ".$rws['lname']),$rws['detail'],ucwords($rws['categoryname']),$cdate,$adate,$spenttime,$action);
			}
			else{
				$response['rows'][$i]['cell']=array($rws['id'],$rws['pname'],$rws['mname'],ucwords($rws['fname'] . " ".$rws['lname']),$rws['subject'],$rws['detail'],$rws['categoryname'],$cdate,$adate,$spenttime,$action);
			}
			$i++;
		}
		$activityTotalTime = $em->createQuery("SELECT sum(al.seconds_spent) as totaltime FROM Application\Entity\ActivityLog al WHERE $where");
		$totalSpentTime = $activityTotalTime->getResult();
		$totalSpentTime=$common->convertSpentTime($totalSpentTime[0]['totaltime']);
		$response['totalspenttime'] = $totalSpentTime;
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
			
	}
	public function addactivitylogAction(){
		$common =new Misc();
		$auth = new AuthenticationService();
		if($this->getRequest()->isPost()){
			$em=$this->getEntityManager();
			$flag=$this->getRequest()->getPost('flag');
			$id=$this->getRequest()->getPost('id');
			$activityId=$this->getRequest()->getPost('activityid');
			$projectId=$this->getRequest()->getPost('projectid');
			if($flag!="view"){
				$response=array();
				$detail=$this->getRequest()->getPost('desc');
				$cdatetime = strtotime(date('Y-m-d H:i:s'));
				$altdate=$this->getRequest()->getPost('altadate');
				$acategory=$this->getRequest()->getPost('category');
				$adate = strtotime($altdate);
				$spent=$this->getRequest()->getPost('spent');

				if($spent==""){
					$response['data']['spent']="null";
				}
				else if($spent!=""){
					$response['data']['spent']="valid";
					if(!$common->validateSpentTime($spent)){
						$response['data']['spent']="invalid";
					}
				}
				if($adate==""){
					$response['data']['adate']="null";

				}else {
					$response['data']['adate']="valid";
				}
				if($detail==""){
					$response['data']['desc']="null";
				}else {
					$response['data']['desc']="valid";
				}
					
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				if(count($response)==0){
					$activityForMilestone=$em->find('Application\Entity\Activities', $activityId);
					$milestoneId=$activityForMilestone->getMilestone_id();
					$newRecord=new \stdClass();
					if($id!="" && $id>0){
						$newRecord=$em->find('Application\Entity\ActivityLog', $id);
					}
					else{
						$newRecord=new ActivityLog();
					}
					$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
					$newRecord->setProject_id($projectId);
					$newRecord->setUser_id($auth->getIdentity()->id);
					$newRecord->setActivity_id($activityId);
					$newRecord->setDescription($detail);
					$newRecord->setCategory_id($acategory);
					$newRecord->setCreated_datetime($cdatetime);
					$newRecord->setActivity_date($adate+$offset);
					if ($milestoneId>0 && isset($milestoneId)){
						$newRecord->setMilestone_id($milestoneId);
					}
					$spentTime=0;
					$spentTimeInArray=explode(":",$spent);
					$spentTime=$spentTimeInArray[0]*3600;
					$spentTime=$spentTime+$spentTimeInArray[1]*60;
					$newRecord->setSeconds_spent($spentTime);
					try{
						$em->persist($newRecord);
						$em->flush();
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
					}
				}
				echo json_encode($response);
				exit;
			}
			else{

				$project = $em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
				$milestone = $em->getRepository('Application\Entity\Milestones')->getMilestonsOrderByName('ASC');
				//createQuery('SELECT m.id as id,m.name as name FROM Application\Entity\Milestones m');
				//	$milestone = $countQuery->getResult();

				$category=$em->createQuery('Select ac.id as id,ac.name as name from Application\Entity\Activitycategories ac Order by ac.name ASC')->getArrayResult();

				$activity=$em->getRepository("Application\Entity\Activities")->findAll();
				//createQuery('Select ps from Application\Entity\Activities ps')->getArrayResult();

				$user = $em->getRepository("Application\Entity\User")->getUserById($auth->getIdentity()->id);
				//$em->createQuery("SELECT u.id as id,u.fname as fname,u.lname as lname FROM Application\Entity\User u where u.id=".$auth->getIdentity()->id);
				//	$user = $countQuery->getResult();
				$valuesToSend=array('project' =>$project,'user'=>$user,'category'=>$category,'milestone'=>$milestone,'activity'=>$activity);
				if(isset($id) && $id>0){
					$activityLog=$em->find('Application\Entity\ActivityLog', $id);
					$valuesToSend['activityLog']=$activityLog;
				}
				else{

					$valuesToSend['projectId']=$projectId;
					$valuesToSend['activityId']=$activityId;
				}
				$viewModel=new ViewModel($valuesToSend);
				$viewModel->setTerminal(true);
				return $viewModel;
			}
		}

	}
	public function deleteactivitylogAction(){
		$auth = new AuthenticationService();
		$em=$this->getEntityManager();
		$response=array();
		$id=$this->getRequest()->getPost('id');
		$activitiesQuery = $em->createQuery("SELECT a.user_id as userid FROM Application\Entity\ActivityLog a where a.id=$id")->getResult();
		if(count($activitiesQuery)>0){
			$uid=$activitiesQuery[0]['userid'];
			if($auth->getIdentity()->isadmin!=1 && $uid!=$auth->getIdentity()->id){
				$response['returnvalue']="invalid";
				$response['data']['user']="null";
				//return $this->redirect()->toRoute('activities');
			}
		}
		if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
			$response['returnvalue']="invalid";
		}
		else{
			$response=array();
		}
		if(count($response)==0){
			$delete =$em->find('Application\Entity\ActivityLog', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue']="valid";
			}
		}
		echo json_encode($response);
		exit;
	}
	public function logspenttimeAction(){
		$auth = new AuthenticationService();
		$common =new Misc();

		$em = $this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->rHeadersPostResponseHTMLCookiesedirect()->toRoute('home');
		}
		if($this->getRequest()->isXmlHttpRequest()){
			$activityid=$this->getRequest()->getPost('activityid');
			$response=array();
			$activity=$em->getRepository('Application\Entity\Activities')->getActivityById($activityid);
			//createQuery("Select a from Application\Entity\Activities a where a.id= $activityid")->getArrayResult();
			$assigneduser=$em->createQuery("Select u.fname as fname,u.lname as lname from Application\Entity\Assignee a
					JOIN Application\Entity\User u WITH u.id=a.user_id
					where a.activity_id= $activityid")->getArrayResult();
			$user='';
			for($i=0;$i<count($assigneduser);$i++){
				if (isset($user)){
					$user.=ucwords($assigneduser[$i]['fname']." ".$assigneduser[$i]['lname']).",";
				}
			}
			$response['data'][0]['assigned_to_id']=	$user;
			$response['data'][0]['description']=$activity[0]['description'];
			$response['data'][0]['subject']=ucwords($activity[0]['subject']);
			$response['data'][0]['due_date']=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i",$activity[0]['due_date']), "Asia/Calcutta","d/m/Y");
			header("Content-type: application/json");
			echo json_encode($response);
			exit;
		}
		$project =$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		$milestone=$em->getRepository('Application\Entity\Milestones')->getMilestonsOrderByName('ASC');
		//createQuery('Select m from Application\Entity\Milestones m')->getArrayResult();
		$assignee=$em->getRepository("Application\Entity\User")->getUserByName();
		//$em->createQuery('Select u from Application\Entity\User u Where u.isactive=1 order by u.fname ASC')->getArrayResult();
		return new ViewModel(array('milestone'=>$milestone,'project'=>$project,'assignee'=>$assignee));
			
	}
	public function getactivityAction(){
		$common =new Misc();
		$em=$this->getEntityManager();
		$projectid=$this->getRequest()->getPost('projectid');
		$milestone=$this->getRequest()->getPost('milestone');
		$altActivitydate=$this->getRequest()->getPost('altactivitydate');
		$assignedId=$this->getRequest()->getPost('assignee');

		$altduedate=$this->getRequest()->getPost('altduedate');
		$common=new Misc();
		$where ='';
		if(isset($projectid) && $projectid>0){
			$where="a.project_id=$projectid";
		}
		if(isset($milestone) && $milestone>0 && $milestone!=""){
			if($where==""){
				$where ="a.milestone_id=$milestone";
			}else{
				$where.= " AND  a.milestone_id=$milestone";
			}
		}
		$activityIds="";
		$needToAttachActivityCondition=false;
		if(isset($assignedId) && $assignedId!="" && isset($altActivitydate) && trim($altActivitydate)!=""){
			$activitydate =$common->ConvertLocalTimezoneToGMT($altActivitydate." 00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$adate= strtotime($activitydate);
			$filterAssignee=$em->createQuery("SELECT asu.activity_id as activity_id from  Application\Entity\Assignee asu JOIN Application\Entity\Activities a WITH asu.activity_id=a.id JOIN Application\Entity\ActivityLog al WITH asu.activity_id=al.activity_id Where asu.user_id=$assignedId AND al.activity_date =$adate group by asu.activity_id");
			$filterResult=$filterAssignee->getResult();
			if (count($filterResult)>0){
				foreach ($filterResult as $rws){
					if($activityIds==""){
						$activityIds=$rws['activity_id'];
					}
					else{
						$activityIds.=",".$rws['activity_id'];
					}
				}
			}
			else{
				$needToAttachActivityCondition=true;
			}
		}
		else if (isset($altActivitydate) && $altActivitydate!=""){
			$activitydate =$common->ConvertLocalTimezoneToGMT($altActivitydate." 00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$adate= strtotime($activitydate);
			$activityLog = $em->createQuery("SELECT a.activity_id as aid FROM Application\Entity\ActivityLog a Where a.activity_date =$adate  group by aid")
			->getResult();
			if(count($activityLog)>0){
				foreach ($activityLog as $rws){
					if($activityIds==""){
						$activityIds=$rws['aid'];
					}
					else{
						$activityIds.=",".$rws['aid'];
					}
				}
			}
			else{
				$needToAttachActivityCondition=true;
			}
		}
		else if(isset($assignedId) && $assignedId!=""){
			$filterAssignee=$em->createQuery("SELECT asu.activity_id as activity_id from  Application\Entity\Assignee asu JOIN Application\Entity\Activities a WITH asu.activity_id=a.id  Where asu.user_id=$assignedId group by asu.activity_id");
			$filterResult=$filterAssignee->getResult();
			if (count($filterResult)>0){
				foreach ($filterResult as $rws){
					if($activityIds==""){
						$activityIds=$rws['activity_id'];
					}
					else{
						$activityIds.=",".$rws['activity_id'];
					}
				}
			}
			else{
				$needToAttachActivityCondition=true;
			}
		}
		if($activityIds!=""){
			if($where==""){
				$where="a.id in ($activityIds)";
			}
			else{
				$where.=" AND a.id in ($activityIds)";
			}
		}
		if($needToAttachActivityCondition){
			if($activityIds==""){
				if($where==""){
					$where="a.id=0";
				}
				else{
					$where.=" AND a.id=0";
				}
			}
		}

		/*if (isset($altActivitydate) && trim($altActivitydate)!=""){
			$activitydate =$common->ConvertLocalTimezoneToGMT($altActivitydate." 00:00",'Asia/Calcutta','Y-m-d H:i:s');
		$adate= strtotime($activitydate);
		$activityLog = $em->createQuery("SELECT a.activity_id as aid FROM Application\Entity\ActivityLog a Where a.activity_date =$adate  group by aid")
		->getResult();
		//print_r($activityLog);exit;
		$activityIds="";
		foreach ($activityLog as $rws){
		if($activityIds==""){
		$activityIds=$rws['aid'];
		}
		else{
		$activityIds.=",".$rws['aid'];
		}
		}
		//print_r($activityIds);
		if($activityIds!=""){
		if($where==""){
		$where=" a.id in ($activityIds)";
		}
		else{
		$where.=" AND a.id in ($activityIds)";
		}
		}
		else{
		if($where==""){
		$where=" a.id=0";
		}
		else{
		$where.=" AND a.id=0";
		}
		}
		}*/
		if(isset($altduedate) && $altduedate!=""){
			$altduedate=$common->ConvertLocalTimezoneToGMT($altduedate." 00:00","Asia/Calcutta","Y-m-d H:i:s");
			$altduedate=strtotime($altduedate);
			if($where==""){
				$where ="a.due_date=$altduedate";
			}else{
				$where.= " AND  a.due_date=$altduedate";
			}
		}
		if($where==""){
			$where="1=1";
		}
		$activity=$em->createQuery("Select a.id as id,a.subject as subject from Application\Entity\Activities a Where $where Order by a.subject ASC")->getArrayResult();
		$response=array();
		if(count($activity)>0){
			for($i=0;$i<count($activity);$i++){
				$response['data'][$i]['id']=$activity[$i]['id'];
				$response['data'][$i]['subject']=$activity[$i]['subject'];
			}
			$response['returnvalue']="valid";
		}
		else{
			$response['returnvalue']="invalid";
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;

	}

}