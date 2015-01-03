<?php
namespace Application\Controller;

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
use Application\Entity\ActivityLog;
use Application\Entity\Preferences;
use Zend\Validator\EmailAddress;
use Application\Entity\Activities;
use Application\Entity\Projects;
use Application\Entity\Holiday;
use Application\Entity\Projecttypes;
use Application\Entity\Activitycategories;
use Application\Entity\Activitystatuses;
use Application\Entity\Activityhistory;
use Application\Entity\User;
use Application\Entity\Assignee;
use Application\Entity\Sentmail;
use Application\Entity\Activityfilter;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ActivitiesController extends AbstractActionController
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

	public function handleAction()
	{
		$viewModel = new ViewModel();
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	public function indexAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$saveFilters=$em->createQuery("SELECT af.id as id,af.name as name,af.user_id as userid,u.fname as fname,u.lname as lname FROM Application\Entity\Activityfilter af JOIN Application\Entity\User u WITH u.id=af.user_id order by af.name ASC")->getArrayResult();
		$comanys = $em->createQuery("SELECT c.id as id,c.name as name FROM Application\Entity\Company c order by c.name ASC")->getArrayResult();
		$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		$activityStatuse=$em->getRepository('Application\Entity\Activitystatuses')->getActivitystatuses();
		$currentLoggedInUser=$auth->getIdentity()->id;
		$valuesToSend=array('saveFilters'=>$saveFilters,'comanys'=>$comanys,'users'=>$users,'project'=>$project,'currentLoggedInUser'=>$currentLoggedInUser,'activityStatuse'=>$activityStatuse);
		$viewModel=new ViewModel($valuesToSend);
		return $viewModel;

	}
	
	public function sendmailAction()
	{
		
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$em=$this->getEntityManager();
		$userid = $auth->getIdentity()->id;
		$flag = $this->getRequest()->getPost('flag');
		if($this->getRequest()->isXmlHttpRequest()){
			if($auth->getIdentity()->isadmin==1){
				$userid = $this->getRequest()->getPost('userid');
			}
			if ($userid==0){
				$userid = $auth->getIdentity()->id;
			}
			$response=array();
			if($flag=="getreport"){
				$activityDate = $this->getRequest()->getPost('activitydate');
				$date=explode("-", $activityDate);
			
				if($activityDate==""){
					$response['data']['activitydate']="null";
				}
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				if(count($response)==0){
					$response['returnvalue']="valid";
						
					$where='';
					if(isset($userid) && $userid!=""){
						$where="al.user_id=$userid";
					}

					$templateActivityDate='';
					if(isset($activityDate) && $activityDate!=""){
						$user=ucwords($auth->getIdentity()->fname);
						if($auth->getIdentity()->lname!="")
						{
							$user=ucwords($auth->getIdentity()->fname." ".$auth->getIdentity()->lname);
						}
						$templateActivityDate=$activityDate;
						$activityDate= $common->ConvertLocalTimezoneToGMT($activityDate,'Asia/Calcutta','Y-m-d H:i:s');
						$activityDate= strtotime($activityDate);
						if($where==" "){
							$where=" al.activity_date=$activityDate";
						}else{
							$where.=" AND al.activity_date=$activityDate";
						}
					}

					$sendmail=$em->createQuery("Select pt.name as type,a.subject as subject,al.description as description,p.name as projectname,p.id as projectid,al.seconds_spent as totaltime
							FROM Application\Entity\ActivityLog as al
							JOIN Application\Entity\Projects p WITH p.id=al.project_id
							JOIN Application\Entity\Projecttypes pt WITH pt.id=p.type_id
							JOIN Application\Entity\Activities a WITH al.activity_id=a.id
							where $where")->getResult();
					$month=$date['1'];
					$year=$date['0'];
					$common =new Common();
					$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');

					$aa = date($year."-".$month."-01");
					$strDate = strtotime($aa)+$offset;
					$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
					$bb = date($year."-".$month.'-'.$num);
					//					$bb = date($year."-".$month.'-t');
					$endDate = strtotime($bb)+$offset;

					$templateData=array();
					$projectMapper =array ();
					if(count($sendmail)>0){
						$totalspenttime=array();
						$total=array();
						foreach ($sendmail as $rw){
							if(!in_array($rw['projectid'],$projectMapper)){
								array_push($projectMapper, $rw['projectid']);
								$recordIndex=array_search($rw['projectid'],$projectMapper);
								$templateData[$recordIndex]['ptype']=$rw['type'];
								$projectWhere="p.id=".$rw['projectid'];
								$useresttime="a.project_id=".$rw['projectid'];
								if(strtolower($rw['type'])== "monthly"){
									$projectWhere.=" and al.created_datetime BETWEEN $strDate AND $endDate";
									$useresttime.=" and a.created_date BETWEEN $strDate AND $endDate";
								}

								$projectData =$em->createQuery("Select al.user_id as userid,p.estimated_hours as project_estime,sum(al.seconds_spent) as project_spent
										FROM Application\Entity\ActivityLog as al
										JOIN Application\Entity\Projects p WITH p.id=al.project_id
                                     	where $projectWhere group by al.user_id")->getResult();
								//print_r($projectData);exit;
								$totalProjectSpent=0;
								$totalCurrentUserSpent=0;
								$totalCurrentUserEst=0;
								if(count($projectData)>0){
									$templateData[$recordIndex]['pestime']=$projectData[0]['project_estime'];
									foreach ($projectData as $rwp){
										$totalProjectSpent+=$rwp['project_spent'];
										if($rwp['userid']==$userid){
											$totalCurrentUserSpent=$rwp['project_spent'];
										$useridest=$rwp['userid'];
										$projectid=$rw['projectid'];
                                          $userestimate=$em->createQuery("Select sum(a.estimated_hours) as esttime from Application\Entity\Activities a where  $useresttime and a.user_id=$userid and a.project_id=$projectid")->getResult();
											$totalCurrentUserEst=$userestimate[0]['esttime'];
										}
									}
								}
								$templateData[$recordIndex]['totalProjectSpent']=$totalProjectSpent;
								$templateData[$recordIndex]['totalCurrentUserSpent']=$totalCurrentUserSpent;
								$templateData[$recordIndex]['totalCurrentUserEst']=$totalCurrentUserEst;
								// 								echo $projectData->getSQL();
							}

							$recordIndex=array_search($rw['projectid'], $projectMapper);
							if(!isset($templateData[$recordIndex]['activities'])){
								$templateData[$recordIndex]['activities']=array();
								$templateData[$recordIndex]['todayactivitylogtime']=0;
							}
							$sizeOfCurrentActivitiesByProject=count($templateData[$recordIndex]['activities']);
							$templateData[$recordIndex]['pname']=$rw['projectname'];
							$templateData[$recordIndex]['todayactivitylogtime']=$templateData[$recordIndex]['todayactivitylogtime']+$rw['totaltime'];
							//$templateData[$recordIndex]['activities'][$sizeOfCurrentActivitiesByProject]['subject']=$rw['subject'];
							$templateData[$recordIndex]['activities'][$sizeOfCurrentActivitiesByProject]['description']=$rw['description'];
							$templateData[$recordIndex]['activities'][$sizeOfCurrentActivitiesByProject]['totaltime']=$rw['totaltime'];
							array_push($totalspenttime,$rw['totaltime']);
						}
						$activitySpentTime=array_sum($totalspenttime);

						//	$aa=date("Y-m-d");
						//	$today = strtotime($aa)+$offset;
						$day=$date['2'];
						$year=$date['0'];
						$month=$date['1'];
						$ss = date($year."-".$month."-01");
						$strDate = strtotime($ss)+$offset;
						$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
						if ($year==date("Y") && $month==date("m")){
							$num=date("d");
						}
						$ee = date($year."-".$month.'-'.$num);
						//$ee = date($year."-".$month.'-t');
					
						$endDate = strtotime($ee)+$offset;

						//$todayLoginTime = $em->createQuery("SELECT min(l.logintime) as mindt,l.created_date as cdate FROM Application\Entity\Login l  WHERE l.user_id=$userid AND l.created_date=$activityDate")->getResult();
						$todayLoginTime = $em->getRepository('Application\Entity\Login')->getTodayLoginTime($userid,$activityDate);
						$workingday=$em->createQuery("SELECT l.id as lid,min(l.logintime) as mindt,l.created_date as cdate FROM Application\Entity\Login l  WHERE l.user_id=$userid AND l.created_date BETWEEN $strDate AND $endDate group by l.created_date ")->getResult();
						$notLoggedInQuery = $em->createQuery("SELECT l.id as lid,min(l.logintime) as mindt,l.created_date as cdate FROM Application\Entity\Login l  WHERE l.user_id=$userid AND l.created_date BETWEEN $strDate AND $endDate group by l.created_date having mindt > l.created_date+35100")->getResult();
						$holiday=$em->createQuery("SELECT h.id FROM Application\Entity\Holiday h  WHERE h.date BETWEEN $strDate AND $endDate")->getResult();
						$avglate=0;
						foreach ($notLoggedInQuery as $rws){
							$avglate+=$rws['mindt']-(35100+$rws['cdate']);
						} 
						if (count($workingday)>0){
						$avglate=$avglate/count($workingday);
						}else{
							$avglate=0;
						}
						$countsun=0;
						
						for($i=1;$i<=$num;$i++){
							$formatedMonthDay=sprintf('%02d', $i);
							$date = $i."-".$month."-".$year;
							$weekday = date('D', strtotime($date));
							if ($weekday=="Sun"){
								$countsun++;
							}
						}
						$officeworkingday=$num-count($holiday)-$countsun;
						$workingdaycount=count($workingday);
						
						$valuesToSend=array('avglate'=>$avglate,'workingdaycount'=>$workingdaycount,'officeworkingday'=>$officeworkingday,'todayLoginTime'=>$todayLoginTime,'notLoggedInQuery'=>$notLoggedInQuery,'response'=>$templateData,'activityDate'=>$templateActivityDate,'user'=>$user,'activitySpentTime'=>$activitySpentTime);
						$viewModel=new ViewModel($valuesToSend);
						$phpRenderer=new PhpRenderer();
						$resolver = new Resolver\AggregateResolver();
						$phpRenderer->setResolver($resolver);
						$map = new Resolver\TemplateMapResolver(array(
								'templates/mailtemplate' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'mailtemplate.phtml',
						));
						$stack = new Resolver\TemplatePathStack(array(
								'script_paths' => array(
										__DIR__ . '/../../view',
								)
						));
						$resolver->attach($map)    // this will be consulted first
						->attach($stack);
						try{
							$viewModel->setTemplate("templates/mailtemplate");
							//$delmailTemplate->setTemplate("templates/newlyassignedactivities");
							$htmlOutput=$phpRenderer->render($viewModel);
							$response['html']=$htmlOutput;
						}
						catch (Exception $e){
							echo $e->getMessage();exit;
						}
					}
					else{
						$response['data']['contenttext']="null";
						$response['returnvalue']="invalid";
					}

				}
			}else if($flag=="sendmail"){

				$selectedDate = $this->getRequest()->getPost('selecteddate');
				$selectedDate1=explode("/", $selectedDate);
				 if (count($selectedDate1)==3){
				$maildate=strtotime($selectedDate1[2]."-".$selectedDate1[1]."-".$selectedDate1[0]." 00:00:00");
			     }
                 else{
                 	$maildate=date("Y-m-d H:i:s");
                 }
                 
				$to1 = $this->getRequest()->getPost('to');
				$cc1 = $this->getRequest()->getPost('cc');
				$subject = $this->getRequest()->getPost('subject');
				$content = $this->getRequest()->getPost('content');
				$userid = $this->getRequest()->getPost('userid');
				$toarray = array();
				$ccarray=array();
				$validator = new EmailAddress();
				if($to1!=""){
					$to=trim($to1,",");
					$to=trim($to);
					$temp=explode(",",$to);
					foreach ($temp as $value){
						if((!empty($value) || $value!="" ) ){
							if(preg_match_all("/\<(.*?)\>/",$value,$matches))
							{
								if($validator->isValid($matches[1][0])){
									array_push($toarray, $matches[1][0]);
								}
							}
							else{
								if($validator->isValid($value)){
									array_push($toarray, $value);
								}
							}
						}
					}
				}else{
					$response['data']['to']="null";
				}

				if(count($toarray)==0 && $to!=""){
					$response['data']['to']="invalid";
				}

				if($cc1!=""){
					$cc=trim($cc1,",");
					$cc=trim($cc);
					$temp=explode(",",$cc);
					foreach ($temp as $value){
						if((!empty($value) || $value!="" ) ){
							if(preg_match_all("/\<(.*?)\>/",$value,$matches))
							{
								if($validator->isValid($matches[1][0])){
								array_push($ccarray, $matches[1][0]);
								}
							}
							else{
								if($validator->isValid($value)){
									array_push($ccarray, $value);
								}
							}
						}
					}
				}else{
					$response['data']['cc']="null";
				}

				if(count($ccarray)==0 && $cc!=""){
					$response['data']['cc']="invalid";
				}

				if($subject==""){
					//$response['data']['subject']="null";
				}
				if($content==""){
					$response['data']['contenttext']="null";
				}
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}

				if(count($response)==0){
					$response['returnvalue']="valid";
					$toemail=array();
					$ccemail=array();
					$isUserIncluded=false;
					for($i=0;$i<count($toarray);$i++){
						if($toarray[$i]==$auth->getIdentity()->email){
							$isUserIncluded=true;
						}
					}
					for($i=0;$i<count($ccarray);$i++){
						if($ccarray[$i]==$auth->getIdentity()->email){
							$isUserIncluded=true;
						}
					}
                  if ($userid==''){
					$name=$auth->getIdentity()->fname;
					if($auth->getIdentity()->lname!=""){
						$name.=" ".$auth->getIdentity()->lname;
						$replyto=$auth->getIdentity()->email;
					}
                  }else {
                  	$username=$em->find("Application\Entity\User",$userid);
                  	$name=$username->__get('fname')." ".$username->__get('lname');
                  	$replyto=$username->__get('email');
                  	
                  }
					if(!$isUserIncluded){
						$count=count($ccemail);
						array_push($ccarray, $replyto);
						if($cc==""){
							$cc='"'.ucwords($name).'"'.'<'.$replyto.'>';
						}
						else{
							$cc.=",".'"'.ucwords($name).'"'.'<'.$replyto.'>';
						}
					}
					$to=array();
					$i=0;
					foreach ($toarray as $rws){
						$to[$i]['email'] = $rws;
						$i++;
					}
					$ccmail=array();
					$i=0;
					foreach ($ccarray as $rws){
						$ccmail[$i]['email'] = $rws;
						$i++;
					}
					if($content!=""){
						if (!isset($userid) && $userid==""){
							$userid = $auth->getIdentity()->id;
						}
						$sentMail = new Sentmail();
						$sentMail->setTableName('dailyReport');
						$sentMail->setTypeId(2);
						$sentMail->setMailTo($to1);
						$sentMail->setCc($cc1);
			    	  //$sentMail->setBcc($bccString);
						$sentMail->setMailFrom($replyto);
						$sentMail->setContent($content);
						$sentMail->setCreatedDatetTime($maildate);
						$sentMail->setSentDateTime(strtotime(date("Y-m-d H:i:s")));
						$sentMail->setUserId($userid);
						try{
							$em->persist($sentMail);
							$em->flush();
						}
						catch (Exception $e){
							echo $e->getMessage();exit;
						}
						
						$common->sendEmail("Status Report of ".ucwords($name)." for $selectedDate",html_entity_decode($content),$to,ucwords($name),$ccmail,null,$replyto);
					}
				}
			}
			echo json_encode($response);exit;
		}
		else{
			$id=$auth->getIdentity()->id;
			$user=$em->getRepository("Application\Entity\User")->getUserByName('ASC');
			$preferences=$em->CreateQuery("Select p  from Application\Entity\Preferences p where p.user_id=$id")->getResult();
			$viewModel=new ViewModel(array('preferences'=>$preferences,'user'=>$user));
			return $viewModel;
		}
	}
	
	
	public function addactivityAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$id=$this->params('id');

		if($this->getRequest()->isPost()){
				
			$response=array();
			$project=$this->getRequest()->getPost('project');
			$esthours=$this->getRequest()->getPost('esthours');
			$status=$this->getRequest()->getPost('status');
			$common=new Common();

			if($esthours==""){
				$response['data']['esthours']="null";
			}
			else if($esthours!=""){
				$response['data']['esthours']="valid";
				if(!floatval($esthours)){
				}
			}

			$project=$this->getRequest()->getPost('project');
			if($project==""){
				$response['data']['project']="null";
			}
			else{
				$response['data']['project']="valid";
			}
			$milestoneid=$this->getRequest()->getPost('milestone');
						if($milestoneid==""){
							$response['data']['milestone']="null";
						}
						else{
							$milestone=$em->getRepository("Application\Entity\Milestones")->getMilestonsByProjectId($project,$milestoneid);
							if (count($milestone)>0){
								$response['data']['milestone']="valid";
								
							}else {
								$response['data']['milestone']="milestonemissmatch";
							}
							
						}
			$subject=$this->getRequest()->getPost('subject');
			if($subject==""){
				$response['data']['subject']="null";
			}
			else{
				$response['data']['subject']="valid";
			}
			$category=$this->getRequest()->getPost('category');
			if($category==""){
				$response['data']['category']="null";
			}
			else{
				$response['data']['category']="valid";
			}
			$status=$this->getRequest()->getPost('status');
			if($status==""){
				$response['data']['status']="null";
			}
			else{
				$response['data']['status']="valid";
			}
			$priority=$this->getRequest()->getPost('priority');
			if($priority==""){
				$response['data']['priority']="null";
			}
			else{
				$response['data']['priority']="valid";
			}
			$assigneduser=$this->getRequest()->getPost('assigneduser');
			if ($assigneduser==""){
				$response['data']['assigneduser']="null";
			}else{
				$response['data']['assigneduser']="valid";
			}
			$toarray = array();
			$validator = new EmailAddress();
			if($assigneduser!=""){
					
				$assigneduser=trim($assigneduser,",");
				$assigneduser=trim($assigneduser);
				$temp=explode(",",$assigneduser);
				foreach ($temp as $value){
					if((!empty($value) || $value!="" ) ){
						if(preg_match_all("/\<(.*?)\>/",$value,$matches))
						{
							if($validator->isValid($matches[1][0])){
								array_push($toarray, $matches[1][0]);
							}
						}
						else{
							if($validator->isValid($value)){
								array_push($toarray, $value);
							}
						}
					}
				}
			}

			$toEmailString="";
			if(count($toarray) > 0){
				for($i=0;$i<count($toarray);$i++){
					if($toEmailString!=""){
						$toEmailString.=","."'".$toarray[$i]."'";
					}
					else{
						$toEmailString="'".$toarray[$i]."'";

					}
					$selected=$em->createQuery("Select u.id as id,u.fname as fname from Application\Entity\User u Where u.email IN ('$toarray[$i]')");
					$countUserFound=$selected->getResult();
					if(count($countUserFound)==0){
						$response['data']['assigneduser']="invalid";
						break;
					}
				}
			}

			$description=$this->getRequest()->getPost('description');
			if($description==""){
				$response['data']['description']="null";
			}else {
				$response['data']['description']="valid";
			}
			$alternateDueDate=$this->getRequest()->getPost('alternate_due_date');
			if($alternateDueDate==""){
				$response['data']['due_date']="null";
			}else {
				$response['data']['due_date']="valid";
			}
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']) || in_array("milestonemissmatch", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}

			if(count($response)==0){

				$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
				$duedateInYmd=date("Y-m-d",strtotime($alternateDueDate));
				$dueDate=strtotime($duedateInYmd)+$offset;
				$dueTime=strtotime($alternateDueDate)-strtotime($duedateInYmd);
				$newRecord=new \stdClass();
				$isNewRecord=false;
				if($id!="" && $id>0){
					$newRecord=$em->find('Application\Entity\Activities', $id);
					//echo $newRecord->getId();exit();
				}
				else{
					$newRecord=new Activities();
					$isNewRecord=true;
					$newRecord->setUser_id($auth->getIdentity()->id);
					$currentDate=strtotime(date("Y-m-d"));
					$currentTime=strtotime(date("Y-m-d H:i:s"))-strtotime(date("Y-m-d"));
					$newRecord->setCreated_date($currentDate);
					$newRecord->setCreated_time($currentTime);
				}
				$newRecord->setEntityManager($em);
				$spentTime=0;
				$spentTimeInArray=explode(":",$esthours);
				$spentTime=$spentTimeInArray[0]*3600;
				if(isset($spentTimeInArray[1])){
					$spentTime=$spentTime+$spentTimeInArray[1]*60;
				}
				if ($spentTime!="" && $spentTime!=""  && ($auth->getIdentity()->isadmin==1 || $auth->getIdentity()->id==$newRecord->getUser_id())){
					$newRecord->setEstimated_hours($spentTime);
				}
				$newRecord->setStatus_id($status);
				$newRecord->setDescription($description);
				$newRecord->setSubject($subject);
				$newRecord->setCategory_id($category);
				if($milestoneid!=""){
					$newRecord->setMilestone_id($milestoneid);
				}
				$newRecord->setProject_id($project);
				$newRecord->setPriority_id($priority);
				$newRecord->setDue_date($dueDate);
				$newRecord->setDue_time($dueTime);
				$phpRenderer=new PhpRenderer();
				$resolver = new Resolver\AggregateResolver();
				$phpRenderer->setResolver($resolver);
				$map = new Resolver\TemplateMapResolver(array(
						'templates/newlyassignedactivities' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'newlyassignedactivities.phtml',
				));
				$stack = new Resolver\TemplatePathStack(array(
						'script_paths' => array(
								__DIR__ . '/../../view',
						)
				));
				$resolver->attach($map)    // this will be consulted first
				->attach($stack);
				try{
					$em->persist($newRecord);
					$em->flush();
					$assignedactivityid=$newRecord->getId();
					if($id!="" && $id>0){
						$mid=$em->createQuery("Select a.milestone_id as milestone_id from Application\Entity\Activities a where a.id=$id")->getresult();
						$mid=$mid[0]['milestone_id'];
						$em->createQuery("UPDATE Application\Entity\ActivityLog as al SET  al.milestone_id =$mid where  al.activity_id=$id ")->getResult();
					}
					
					$newAssignee=new \stdClass();
					$newUsersMapper = array();
					$newUsers=array();
					if($toEmailString!=""){
						$selected=$em->createQuery("Select u.id as id,u.fname as fname from Application\Entity\User u Where u.email IN ($toEmailString)");
						$newUsers=$selected->getResult();
					}
					foreach($newUsers as $user){
						array_push($newUsersMapper,$user['id']);
					}
					$oldUsersMapper = array();
					$oldAssignee = $em->getRepository('Application\Entity\Assignee')->findBy(array('activity_id' => $id));
					foreach($oldAssignee as $user){
						array_push($oldUsersMapper,$user->getUser_id());
					}

					if(!$isNewRecord){
							
						$needToBeDeletedUsers=array();
						$needToBeDeletedUsers=array_diff($oldUsersMapper,$newUsersMapper);
						if(count($needToBeDeletedUsers)>0){
							$todeletedUser="";
							foreach($oldAssignee as $assignee){
								if(in_array($assignee->getUser_id(),$needToBeDeletedUsers)){
									$assignee->setEntityManager($em);
									$sendmailto=$em->getRepository("Application\Entity\User")->getUserById($assignee->getUser_id());
									//createQuery('Select u.fname as fname,u.lname as lname,u.email as email from Application\Entity\User u Where u.id='.$assignee->getUser_id());
									$mailtodeletedassignee=$sendmailto->getResult();
									//$activity=$em->creatrQuery('Select a.subject as subject from Application\Entity\Activities a where a.id=$linkId')->getResult();
									//$deleteactivity=$activity[0]['subject'];
									$delFname=$mailtodeletedassignee[0]['fname'];
									$delLname=$mailtodeletedassignee[0]['lname'];
									$linkId=$assignee->getId();
									$delusername=$delFname." ".$delLname;
									$assignedBy=$auth->getIdentity()->fname." ".$auth->getIdentity()->lname;
									/*$todeletedUser=array();
									 $todeletedUser[0]['email']=$mailtodeletedassignee[0]['email'];
									$todeletedUser[0]['name']=$delusername;*/
									if($todeletedUser==""){
										$todeletedUser='"'.$delusername.'"'." "."<".$mailtodeletedassignee[0]['email'].">";
									}else{
										$todeletedUser.=",".'"'.$delusername.'"'." "."<".$mailtodeletedassignee[0]['email'].">";
									}
									//	'deleteactivity'=>$deleteactivity,
									$removedDetailtosend=array('delusername'=>$delusername,'linkId'=>$linkId,'assignedBy'=>$assignedBy);
									$delmailTemplate= new ViewModel($removedDetailtosend);

									try{

										$delmailTemplate->setTemplate("templates/newlyassignedactivities");
										$htmlOutputforDelete=$phpRenderer->render($delmailTemplate);
										//echo $htmlOutputforDelete;exit;
										//$common->sendEmail("Notification for the Assigned activty",$htmlOutputforDelete,$todeletedUser,$assignedBy);
									}
									catch (Exception $e){
										echo $e->getMessage();exit;
									}
									$em->remove($assignee);
								}
							}
						}
					}
						
					$needToBeInsertedUsers=array();
						
					$needToBeInsertedUsers=array_diff($newUsersMapper,$oldUsersMapper);
						
					if(count($needToBeInsertedUsers)>0){

						foreach ($needToBeInsertedUsers as $newUser){
							$newAssignee=new Assignee();
							$newAssignee->setEntityManager($em);
							if($assignedactivityid!="" && $assignedactivityid>0){
								$newAssignee->setActivity_id($assignedactivityid);
							}else{
								$newAssignee->setActivity_id($id);
							}
							$newAssignee->setProject_id($project);
							$newAssignee->setMilestone_id($milestoneid);
							$newAssignee->setUser_id($newUser);
							$em->persist($newAssignee);
								
						}
						$to="";
						foreach($needToBeInsertedUsers as $sendMailToinserted){

							$sendmail=$em->createQuery('Select u.fname as fname,u.lname as lname,u.email as email from Application\Entity\User u Where u.id='.$sendMailToinserted);
							$mailtoinsertedassignee=$sendmail->getResult();
							$fname=$mailtoinsertedassignee[0]['fname'];
							$lname=$mailtoinsertedassignee[0]['lname'];
							$username=$fname." ".$lname;
							$assignedBy=$auth->getIdentity()->fname." ".$auth->getIdentity()->lname;
							if($assignedactivityid!="" && $assignedactivityid>0){
								$inserteduserId=$assignedactivityid;
							}else{
								$inserteduserId=$id;
							}
								
							$to=array();
							$to[0]['email']=$mailtoinsertedassignee[0]['email'];
							$to[0]['name']=$username;
							$detailtosend=array('subject'=>$subject,'username'=>$username,'inserteduserId'=>$inserteduserId,'assignedBy'=>$assignedBy);

							$mailTemplate= new ViewModel($detailtosend);
								
							try{

								$mailTemplate->setTemplate("templates/newlyassignedactivities");
								$htmlOutputforInsert=$phpRenderer->render($mailTemplate);
									
								$common->sendEmail("Notification for the Assigned activty",$htmlOutputforInsert,$to,$assignedBy);

							}
							catch (Exception $e){

								echo $e->getMessage();exit;
							}
								
						}
					}
					$em->flush();
					//	$response['returnvalue']="$htmlOutputforInsert";
					$response['returnvalue']="valid";
				}
				catch (Exception $e)
				{
						
					$response['returnvalue']="exception";
				}

			}
				
			echo json_encode($response);
			exit;
		}else{
			$selectd_project_id='';
			$where='';
			if($id!="" && $id>0){
				$activityRecord=$em->find('Application\Entity\Activities', $id);
				$selectd_project_id=$activityRecord->getProject_id();
				$where="m.project_id=$selectd_project_id";
			}else {
					$where="1=1";
				}
			
			$comanys = $em->createQuery("SELECT c.id as id,c.name as name FROM Application\Entity\Company c order by c.name ASC")->getArrayResult();
			$status=$em->createQuery('Select a from Application\Entity\Activitystatuses a Order by a.name ASC')->getArrayResult();
			$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
			$milestone=$em->createQuery("Select m.id as id,m.name as name,p.name as pname from Application\Entity\Milestones m JOIN Application\Entity\Projects p WITH m.project_id=p.id  where $where Order by m.name ASC")->getArrayResult();
			$category=$em->getRepository('Application\Entity\Activitycategories')->getActivitycategoriesByName('ASC');
			//$category=$em->createQuery('Select ac.id as id,ac.name as name from Application\Entity\Activitycategories ac Order by ac.name ASC')->getArrayResult();
			$valuesToSend=array('comanys'=>$comanys,'status' =>$status,'project'=>$project,'selectd_project_id' =>$selectd_project_id,'category'=>$category,'milestone'=>$milestone);
			if($id!="" && $id>0){
				$activityRecord=$em->find('Application\Entity\Activities', $id);
				$projectid=$activityRecord->getProject_id();
				$as=$em->createQuery('Select u.fname as fname,u.lname as lname,u.email as email from Application\Entity\Assignee a JOIN Application\Entity\User u WITH u.id=a.user_id Where a.activity_id='.$id);
				$assigneeRecord=$as->getResult();
				$auName='';
				for($j=0;$j<count($assigneeRecord);$j++){
					$name='"'.$assigneeRecord[$j]['fname'];
					if(isset($assigneeRecord[$j]['lname'])){
						$name.=" ".$assigneeRecord[$j]['lname'].'"';
					}
					if(isset($assigneeRecord[$j]['email'])){
						$name.="<".$assigneeRecord[$j]['email'].'>';
					}
					if($auName==""){
						$auName=ucwords($name);
					}
					else{
						$auName.=",".ucwords($name);
					}
				}
				$valuesToSend['activityRecord']=$activityRecord;
				$valuesToSend['auName']=$auName;

			}
			
			$currentLoggedInUser=$auth->getIdentity()->id;
			$viewModel=new ViewModel($valuesToSend);
			return $viewModel;
		}
	}
	
	
	public function getmilestoneAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$projectid=$this->getRequest()->getPost('projectid');

		$milestone=$em->getRepository("Application\Entity\Milestones")->getMilestonsByProjectId($projectid);
		$response=array();
		if(count($milestone)>0){
			for($i=0;$i<count($milestone);$i++){
				$response['data'][$i]['id']=$milestone[$i]['id'];
				$response['data'][$i]['name']=$milestone[$i]['name'];
				$response['data'][$i]['pname']=$milestone[$i]['pname'];
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
	public function viewactivitydetailAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		$currentLoggedInUser=$auth->getIdentity()->id;
		$curdate=date('Y-m-d');
		$em = $this->getEntityManager();
		$activtyId=$this->params('id');

		$valuesToSend=array();
		if($activtyId!="" && $activtyId>0){
			$activityRecord=$em->find('Application\Entity\Activities', $activtyId);
			$valuesToSend['activityRecord']=$activityRecord;
			if($activityRecord->getStatus_id()!=null){
				$status=$em->getRepository("Application\Entity\Activitystatuses")->getActivitystatusesById($activityRecord->getStatus_id());
				$valuesToSend['status']=$status;
			}
			if($activityRecord->getProject_id()!=null){
				$project=$em->getRepository('Application\Entity\Projects')->getProjectsById($activityRecord->getProject_id());
				$valuesToSend['project']=$project;
			}
			if($activityRecord->getCategory_id()!=null){
				$category=$em->createQuery('Select ac.id as id,ac.name as name from Application\Entity\Activitycategories ac Where ac.id='.$activityRecord->getCategory_id())->getArrayResult();
				$valuesToSend['category']=$category;
			}
			if($activityRecord->getEstimated_hours()!=null){
				$estimatedHours=$activityRecord->getEstimated_hours();
				$esthours=$common->convertSpentTime($estimatedHours);
				$valuesToSend['esthours']=$esthours;
			}
			$totalActivityTime=array();
			$activityTotalTime = $em->createQuery("SELECT sum(a.seconds_spent) as totaltime FROM Application\Entity\ActivityLog a WHERE a.activity_id=$activtyId ");
			$totalActivityTime = $activityTotalTime->getResult();
			if(count($totalActivityTime)>0){
				$totalTime=$common->convertSpentTime($totalActivityTime[0]['totaltime']);
				$valuesToSend['totalTime']=$totalTime;
			}

			$assigneeName='';
			$user=$em->createQuery("Select u.id as id,u.fname as fname,u.lname as lname from Application\Entity\Assignee a JOIN Application\Entity\User u WITH u.id=a.user_id AND a.activity_id=$activtyId");
			$userResult=$user->getResult();
			for($i=0;$i<count($userResult);$i++){
				$name=$userResult[$i]['fname'];
				if(isset($userResult[$i]['lname'])){
					$name.=" ".$userResult[$i]['lname'];
				}
				if($assigneeName==""){
					$assigneeName=ucwords($name);
				}
				else{
					$assigneeName.=",".ucwords($name);
				}
			}
			$valuesToSend['assigneeName']=$assigneeName;
		}
		$userlog=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		$valuesToSend['userlog']=$userlog;
		$valuesToSend['currentLoggedInUser']=$currentLoggedInUser;
		$viewModel=new ViewModel($valuesToSend);
		$getHistoryQuery = $em->createQuery(
				"SELECT ah.description, au.fname as fname, au.lname as lname, ah.created_date as cdate,
				ah.created_time as ctime FROM Application\Entity\Activityhistory ah
				JOIN Application\Entity\User au
				WITH au.id=ah.activity_by_id
				WHERE ah.activity_id =". $activtyId);
		$getHistoryQueryResult = $getHistoryQuery->getResult();
		for($i=0;$i<sizeof($getHistoryQueryResult);$i++)
		{
			$activityHistoryCreatedDate='';
			if($getHistoryQueryResult[$i]['cdate']>0){
				if($getHistoryQueryResult[$i]['ctime']>0){
					$activityHistoryCreatedDate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getHistoryQueryResult[$i]['cdate']+$getHistoryQueryResult[$i]['ctime']),"Asia/Calcutta");
				}
				else{
					$activityHistoryCreatedDate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getHistoryQueryResult[$i]['cdate']),"Asia/Calcutta");
				}
			}
			$getHistoryQueryResult[$i]['cdate'] =$activityHistoryCreatedDate;
			$getHistoryQueryResult[$i]['name'] = $getHistoryQueryResult[$i]['fname']." ".$getHistoryQueryResult[$i]['lname'];
		}
		
	//	print_r($getHistoryQueryResult);exit;
		$viewModel->getacthistory = $getHistoryQueryResult;
		$viewModel->activityId = $activtyId;
		return $viewModel;
	}
	
	
	public function gridactivitiesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		$page = $this->getRequest()->getPost('page');
		$limit =$this->getRequest()->getPost('rows');
		$sidx = $this->getRequest()->getPost('sidx');
		$sord = $this->getRequest()->getPost('sord');
		$assignedId=$this->getRequest()->getPost('assignedid');
		if($auth->getIdentity()->isadmin!=1){
			$assignedId=$auth->getIdentity()->id;
		}
		$assignedBy=$this->getRequest()->getPost('assignedby');
		$projectId=$this->getRequest()->getPost('projectid');
		$duedate=$this->getRequest()->getPost('duedate');
		$activitydate=$this->getRequest()->getPost('activitydate');
		$activitiestatus=$this->getRequest()->getPost('activitystatus');
		$subjectLike=$this->getRequest()->getPost('subject');
		$activityloguser=$this->getRequest()->getPost('activityloguser');
		if($page > 0){
			$start = ($limit * $page) - $limit; // do not put $limit*($page - 1)
		}
		else{
			$start = 0;
		}
		if($sidx=="" ||$sidx=="due_date"){
			$sidx="a.due_date";
		}
		else if($sidx=="subject"){
			$sidx="a.subject";
		}
		else if($sidx=="priority"){
			$sidx="a.priority_id";
		}
		else if($sidx=="status"){
			$sidx="astatus.name";
		}
		else if($sidx=="category"){
			$sidx="ac.name";
		}
		else if($sidx=="pname"){
			$sidx="p.name";
		}
		else if($sidx=="auser"){
			$sidx="au.fname";
		}
		$em = $this->getEntityManager();
		$whereactivityid="";
		if (isset($projectId) && trim($projectId)!=""){
			if($whereactivityid==""){
			
				$whereactivityid="al.project_id=$projectId";
			}
			else{
				$whereactivityid.=" AND al.project_id=$projectId";
		}
		}
		if (isset($activitydate) && trim($activitydate)!=""){
			$em = $this->getEntityManager();
			$duedatestart =$common->ConvertLocalTimezoneToGMT($activitydate." 00:00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$duedatestart= strtotime($duedatestart);
			if($whereactivityid==""){
				$whereactivityid="al.activity_date=$duedatestart";
			}
			else{
				$whereactivityid.=" AND al.activity_date=$duedatestart";
			}
		}
		
		if (isset($activityloguser) && trim($activityloguser)!=""){
			if($whereactivityid==""){
				$whereactivityid="al.user_id=$activityloguser";
			}
			else{
				$whereactivityid.=" AND al.user_id=$activityloguser";
			}
		}
		if($whereactivityid==""){
			$whereactivityid="1=1";
		}
		$where="";
		$activityIds="";
		if ((isset($activityloguser) && $activityloguser!="") || (isset($activitydate) && trim($activitydate)!="") || (isset($projectId) && trim($projectId)!="")){
			$em = $this->getEntityManager();
			$activityLogByUser = $em->createQuery("SELECT al.activity_id as aid FROM Application\Entity\ActivityLog al  WHERE $whereactivityid  group by aid")
			->getResult();
		
			foreach ($activityLogByUser as $rws){
				if($activityIds==""){
					$activityIds=$rws['aid'];
				}
				else{
					$activityIds.=",".$rws['aid'];
				}
			}
			
		}
		
		//filter by user_id
		if(isset($assignedId) && $assignedId!=""){
			$whereassignedId="";
			if (isset($activityIds) && $activityIds!=""){
			$whereassignedId="asu.user_id=$assignedId AND asu.activity_id in ($activityIds)";
			}else {
				$whereassignedId="asu.user_id=$assignedId";
			}
			$filterAssignee=$em->createQuery("SELECT asu.activity_id as activity_id from  Application\Entity\Assignee asu JOIN Application\Entity\Activities a WITH asu.activity_id=a.id Where $whereassignedId group by asu.activity_id");
			//echo $filterAssignee->getSQL();exit;
			
			$filterResult=$filterAssignee->getResult();
			if (count($filterResult)>0){
				$activityIds="";
				foreach ($filterResult as $rws){
					if($activityIds==""){
						$activityIds=$rws['activity_id'];
					}
					else{
						$activityIds.=",".$rws['activity_id'];
					}
				}
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
		
		//filter by projectid
		if (isset($projectId) && trim($projectId)!=""){
			if($where==""){
				$where="a.project_id=$projectId";
			}
			else{
				$where.=" AND a.project_id=$projectId";
			}
		}
		//filter by duedate
		if (isset($duedate) && trim($duedate)!=""){
			$duedatestart =$common->ConvertLocalTimezoneToGMT($duedate." 00:00",'Asia/Calcutta','Y-m-d H:i:s');
			$duedatestart= strtotime($duedatestart);
			if($where==""){
				$where="a.due_date =$duedatestart";
			}
			else{
				$where.="AND a.due_date =$duedatestart";
			}
		}
		
		//Application\Entity\ActivityLog
		if ($assignedBy!=""){
			if($where==""){
				$where=" a.user_id=$assignedBy";
			}
			else{
				$where.="AND a.user_id=$assignedBy";
			}
		}
		if ($activitiestatus!=""){
			if($where==""){
				$where=" a.status_id=$activitiestatus";
			}
			else{
				$where.="AND a.status_id=$activitiestatus";
			}
		}
		if ($subjectLike!=""){
			if($where==""){
				$where=" a.subject LIKE '%$subjectLike%'";
			}
			else{
				$where.="AND a.subject LIKE '%$subjectLike%'";
			}
		}
		if($where==""){
			$where="1=1";
		}
		$em = $this->getEntityManager();
		$activitiesCountQuery = $em->createQuery("SELECT a.id as id
				FROM Application\Entity\Activities a
				JOIN Application\Entity\Projects p WITH p.id=a.project_id
				LEFT JOIN Application\Entity\Milestones m WITH m.id=a.milestone_id
				JOIN Application\Entity\Activitycategories as ac WITH ac.id=a.category_id
				JOIN Application\Entity\Activitystatuses as astatus WITH astatus.id=a.status_id
             	WHERE $where ");
		$totalRecords = $activitiesCountQuery->getResult();
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$activitiesQuery = $em->createQuery("SELECT a.id as id,a.estimated_hours as estimatedhours,a.subject as subject,
				a.priority_id as priority_id,a.description as description,
				a.created_date as created_date,a.created_time as created_time,
				a.due_date as due_date,a.due_time as due_time,m.name as milestonename,
				p.name as projectname,astatus.name as status,
				ac.name as category FROM Application\Entity\Activities a
				JOIN Application\Entity\Projects p WITH p.id=a.project_id
				LEFT JOIN Application\Entity\Milestones m WITH m.id=a.milestone_id
				JOIN Application\Entity\Activitycategories as ac WITH ac.id=a.category_id
				JOIN Application\Entity\Activitystatuses as astatus WITH astatus.id=a.status_id
				WHERE $where order by $sidx $sord ")
				->setFirstResult( $start )
				->setMaxResults( $limit );
	//	echo $activitiesQuery->getSql();
		$totalrows = $activitiesQuery->getResult();
		$activityIds="";
		foreach ($totalrows as $rws){
			if($activityIds==""){
				$activityIds=$rws['id'];
			}
			else{
				$activityIds.=",".$rws['id'];
			}
		}
		$totalActivityTime=array();
		if($activityIds!=""){
			$activityTotalTime = $em->createQuery("SELECT a.activity_id as activityid,sum(a.seconds_spent) as totaltime FROM Application\Entity\ActivityLog a WHERE a.activity_id in ($activityIds)  group by a.activity_id")
			->setFirstResult( $start )
			->setMaxResults( $limit );
			$totalActivityTime = $activityTotalTime->getResult();
		}
		$i=0;
		foreach ($totalrows as $rws){
			$common =new Common();
			$spenttime='';
			foreach ($totalActivityTime as $rrr){
				if($rws['id'] == $rrr['activityid']){
					$spenttime=$common->convertSpentTime($rrr['totaltime']);
					break;
				}
			}
			$createdDateTime='';
			$dueDate='';
			$auName='';//assigned user name
			$common =new Common();
			$assigneeRows = $em->createQuery("SELECT assign.user_id as user_id,u.fname as fname,u.lname as lname from Application\Entity\Assignee assign JOIN Application\Entity\User u Where assign.activity_id=".$rws['id']."AND u.id=assign.user_id");
			$assigneeResult = $assigneeRows->getResult();

			$esthours=$common->convertSpentTime($rws['estimatedhours']);

			if(isset($rws['created_date']) && $rws['created_date']>0){
				if(isset($rws['created_time']) && $rws['created_time']>0){
					$createdDateTime =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['created_date']+$rws['created_time']),'Asia/Calcutta','d/m/Y H:i:s');
				}
				else{
					$createdDateTime =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['created_date']),'Asia/Calcutta','d/m/Y H:i:s');
				}
			}
			if(isset($rws['due_date']) && $rws['due_date']>0){
				if(isset($rws['due_time']) && $rws['due_time']>0){
					$dueDate =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['due_date']+$rws['due_time']),'Asia/Calcutta','d/m/Y H:i:s');

				}
				else{
					$dueDate =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['due_date']),'Asia/Calcutta','d/m/Y H:i:s');
				}
			}
			for($j=0;$j<count($assigneeResult);$j++){
				$name=	$assigneeResult[$j]['fname'];
				if(isset($assigneeResult[$j]['lname'])){
					$name.=" ".$assigneeResult[$j]['lname'];
				}
				if($auName==""){
					$auName=ucwords($name);
				}
				else{
					$auName.=",".ucwords($name);
				}
			}
			
			$action="<a href='/viewactivitydetail/".$rws['id']."' target='_blank'><i class='icon-file'></i></a>&nbsp;<a href='/addactivity/".$rws['id']."'><i class='icon-edit'></i></a>&nbsp;<a href='javascript:deleteActivity(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];

			$response['rows'][$i]['cell']=array(
					$rws['projectname'],
					$rws['subject'],
					$rws['priority_id'],
					$rws['status'],
					$rws['category'],
					$rws['milestonename'],
					$auName,
					$dueDate,
					$esthours,
					$spenttime,
					$action);
			$i++;
		}

		header("Content-type: application/json");
		echo json_encode($response);

		exit;
	}
	
	
	
	public function getmultiuserAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$q = $_GET['term'];
		$data=array();
		$em=$this->getEntityManager();
		$query=trim($q);
		if(isset($_GET['company'])){
		$companyid=trim($_GET['company']);
			$user=$em->createQuery("Select u.id as id,u.fname as fname,u.lname as lname,u.email as email,u.company_id as companyId from Application\Entity\User u Where  (u.fname like '".$query."%' or u.email like '".$query."%')  AND u.company_id='$companyid' AND u.isactive=1 ");
		}else{
			$user=$em->createQuery("Select u.id as id,u.fname as fname,u.lname as lname,u.email as email,u.company_id as companyId from Application\Entity\User u Where  (u.fname like '".$query."%' or u.email like '".$query."%')   AND u.isactive=1 ");
		}
		$serchuser = $user->getResult();
		$i=0;
		foreach($serchuser as $rws){
			$data[$i]['name'] = ucwords($rws['fname']." ".$rws['lname']);
			$data[$i]['value'] = '"'.ucwords($rws['fname']." ".$rws['lname']).'"'.'<'.$rws['email'].'>';
			$i++;

		}
		echo json_encode($data);exit;
	}
	public function deleteactivityAction(){
		$auth = new AuthenticationService();
		$em=$this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$response=array();
		$id=$this->getRequest()->getPost('id');
		$activitiesQuery = $em->createQuery("SELECT a.user_id as userid FROM Application\Entity\Activities a where a.id=$id")->getResult();
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
			$deleteActivitylog=$em->getRepository('Application\Entity\ActivityLog')->findBy(array('activity_id' => $id));
			if($deleteActivitylog){
				foreach($deleteActivitylog as $delActivitylog){
					$delActivitylog->setEntityManager($em);
					$this->getEntityManager()->remove($delActivitylog);
					$this->getEntityManager()->flush();
				}
			}
			$deleteAssignee =$em->getRepository('Application\Entity\Assignee')->findBy(array('activity_id' => $id));
			if($deleteAssignee){
				foreach($deleteAssignee as $delassignee){
					$delassignee->setEntityManager($em);
					$this->getEntityManager()->remove($delassignee);
					$this->getEntityManager()->flush();
				}
			}
			$delete =$em->find('Application\Entity\Activities', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue']="valid";
			}
		}
		echo json_encode($response);
		exit;
	}
	
	public function updateduedateAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$common=new Common();
		$activitiesQuery = $em->createQuery("SELECT a.id as id, a.due_date as duedate, a.due_time as duetime
				FROM Application\Entity\Activities a");
		$activitiesQueryResult = $activitiesQuery->getResult();
		$oldDue = array();
		foreach($activitiesQueryResult as $activity)
		{
			$oldDue[$activity['id']] = strtotime($common->ConvertLocalTimezoneToGMT(date("Y-m-d H:i:s",$activity['duedate'] + $activity['duetime']), "Asia/Calcutta","Y-m-d H:i:s"));
		}
		foreach($oldDue as $key=>$value)
		{
			$duedateInYmd=date("Y-m-d",$value);
			$updateDue = $em->find('Application\Entity\Activities', $key);
			if($updateDue){
				$updateDue->setEntityManager($em);
				$updateDue->setDue_date(strtotime($duedateInYmd));
				$updateDue->setDue_time($value - strtotime($duedateInYmd));
				try{
					$em->persist($updateDue);
					$em->flush();
				}
				catch (Exception $e)
				{
					echo $e->getMessage();
					return false;
				}
			}
		}
		exit;
	}
	
	public function savefilteractivityAction(){

		$response=array();
		$em=$this->getEntityManager();
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		
				$userId =$auth->getIdentity()->id;
		
				$savefilter = $this->getRequest()->getPost('savefilter');	
			
				$activityFilterName = $this->getRequest()->getPost('activityFilterName');
				$data['company'] = $this->getRequest()->getPost('company');
				$data['activitystatus'] = $this->getRequest()->getPost('activitystatus');
    			$data['userid'] = $this->getRequest()->getPost('userid');
    			$data['assignedby'] = $this->getRequest()->getPost('assignedby');
    			$data['projectid'] = $this->getRequest()->getPost('projectid');
    			$data['duedates'] = $this->getRequest()->getPost('duedates');
    			$data['alterduedate'] = $this->getRequest()->getPost('alterduedate');
    			 
    			$data['activitydate'] = $this->getRequest()->getPost('activitydate');
    			$data['altactivitydate'] = $this->getRequest()->getPost('altactivitydate');
    			 
    			$data['subject'] = $this->getRequest()->getPost('subject');
    			$data['activityloguser'] = $this->getRequest()->getPost('activityloguser');
    			$data['duedate'] = $this->getRequest()->getPost('duedate');
    			$data['altactivitydate'] = $this->getRequest()->getPost('altactivitydate');
    			
		
		if($savefilter>0 ){
			$obj =$em->find("Application\Entity\Activityfilter",$savefilter);
		}else{
			$obj = new Activityfilter();
		}
		$obj->setName($activityFilterName);
		$obj->setUser_id($userId);
		$obj->setString(json_encode($data));
		try{
			$em->persist($obj);
			$em->flush();
			$response['returnvalue']="valid";
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			return false;
		}
		echo json_encode($response);
		exit;
	}
	
	public function getfilteractivityAction(){
		$em=$this->getEntityManager();
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$response=array();
		$savefilterid=$this->getRequest()->getPost("savefilterid");
		if ($savefilterid>0) {
			$where="af.id='$savefilterid'";
		}
		$userId =$auth->getIdentity()->id;
		$activitysFilter=$em->CreateQuery("SELECT af.id as id,af.user_id as userid,af.string as string FROM Application\Entity\Activityfilter af WHERE $where")->getArrayResult();
		if(count($activitysFilter)>0){
			$response['returnvalue']="valid";
			$response['savefilter']=$activitysFilter[0]['id'];
		    $response['data']= json_decode($activitysFilter[0]['string']);
		}else{
			$response['returnvalue']="invalid";
		}
		
		echo json_encode($response);
		exit;
	}
}
