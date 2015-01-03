<?php
namespace Application\Controller;

use Zend\Form\Element\Password;

use Zend\Soap\Client\Common;
use IntranetUtils\AuthAdapter as AuthAdapter;
use IntranetUtils\Common as Misc;
use Zend\Config\Reader\Ini;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use	Doctrine\ORM\EntityManager;
use DoctrineModule\Authentication\Adapter as DoctrineAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Result;
use Doctrine\ORM\Query\Expr;
use Application\Entity\Login;
use Application\Entity\Preferences;
//use Application\Entity\Tmp;
use Zend\Authentication\Storage;
use Zend\Authentication\Storage\Session as SessionStorage;
use Application\Entity\User;
use Application\Entity\Projects;
use Application\Entity\Holiday;
use Application\Entity\Dailyquote;
use Application\Entity\Activities;
use Application\Entity\Timingslot;
use Zend\Json\Decoder;


/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class IndexController extends AbstractActionController
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
	public function resetpasswordallAction(){
		$common=new Misc();
		$em=$this->getEntityManager();
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		ini_set("set_time_limit",0);
		$changepassword = $em->getRepository("Application\Entity\User")->getUserByName();
		//createQuery("SELECT u.fname as fname,u.lname as lname,u.id as id,u.password as password,u.email as email FROM Application\Entity\User u")->getResult();
		
		
		for ($i=0;$i<count($changepassword);$i++){
			$to="";
			$oldpassword=$changepassword[$i]['password'];
			$newpassword=substr(uniqid(),6,13);
			$setnewpassword=md5($newpassword);
			$to=$changepassword[$i]['email'];
			$add=new \stdClass();
			$add=$em->find('Application\Entity\User', $changepassword[$i]['id']);
			$add->__set('password',$setnewpassword);
			try{
				$em->persist($add);
				$em->flush();
				$common->sendEmail("Reset password notification","your password has been reset by administrator,your new password is $newpassword",$to,"Intranet");
			echo $changepassword[$i]['fname']." ".$newpassword."<br/>";
			}
			catch (Exception $e)
			{
				echo $e->getMessage();exit;
			}
		}

	}
	
	public function setpreferencesAction(){
		$em=$this->getEntityManager();
		$user=$em->getRepository("Application\Entity\User")->findAll();
		//CreateQuery("select u.id as id from Application\Entity\User u")->getResult();
		foreach ($user as $rws){
			$newRecord = new Preferences();
			$newRecord->setUser_id($rws->__get('id'));
			
			$newRecord->setCc('');
			$newRecord->setTomail('');
			try{
				$em->persist($newRecord);
				$em->flush();
			}catch (Exception $e)
			{
				echo $e->getMessage();exit;
			}
		}
		exit;
	
	}
	
	public function indexAction()
 	{   
 		
 		$em=$this->getEntityManager();
 		$common = new Misc();
 		
 			
 		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		$userid=$auth->getIdentity()->id;
			
		if($auth->getIdentity()->isadmin==1){
			if($this->params('id')!=""){
				$userid=$this->params('id');
			}
		}
			
			
		$selectedYear =date("Y");
		$selectedMonth =date('m');
		$fromdate=$selectedYear."-".$selectedMonth."-1";
		$todate=$selectedYear."-".$selectedMonth."-".cal_days_in_month(CAL_GREGORIAN,$selectedMonth,$selectedYear);
		$fromdate=$common->ConvertLocalTimezoneToGMT($fromdate,"Asia/Calcutta","Y-m-d H:i:s");
		$todate=$common->ConvertLocalTimezoneToGMT($todate,"Asia/Calcutta","Y-m-d H:i:s");
			
		$fromdate=strtotime($fromdate);
		$todate=strtotime($todate);
			
		$allocationQuery=$em->CreateQuery("SELECT ra.user_id as user_id,u.company_id as company_id
				,sum(ra.duration) as allocated,ra.project_id as project_id,p.name as project_name,ts.slot_login_time as timeSlot
				FROM Application\Entity\User u
				LEFT JOIN Application\Entity\ResourceAllocation ra WITH u.id=ra.user_id
				LEFT JOIN Application\Entity\Projects as p WITH p.id=ra.project_id
				LEFT JOIN Application\Entity\TimingSlot ts WITH ts.slot_id=u.timing_slot_id
				WHERE ra.allocation_date BETWEEN $fromdate AND $todate AND ra.user_id=$userid GROUP BY ra.project_id")->getArrayResult();
			
		$activityReport=$em->createQuery("Select u.id as user_id,u.company_id as company_id,u.fname as fname
				,u.lname as lname,sum(al.seconds_spent) as spenttime,al.project_id as project_id,p.name as project_name
				,ts.slot_login_time as timeSlot
				FROM Application\Entity\User u
				LEFT JOIN Application\Entity\ActivityLog as al WITH u.id=al.user_id
				LEFT JOIN Application\Entity\Projects as p WITH p.id=al.project_id
				LEFT JOIN Application\Entity\TimingSlot ts WITH ts.slot_id=u.timing_slot_id
				where al.activity_date BETWEEN $fromdate AND $todate AND al.user_id=$userid  group By al.project_id")->getResult();
			
// 		print_r($activityReport);exit;
		$projectArray=array();
		$totalallocateinmonth=0;
// 		$timingslot=0;
		foreach ($activityReport as $spent){
			$timingslot=$spent['timeSlot'];
			$projectRecordIndex=-1;
			for($i=0;$i<count($projectArray);$i++){
				if($projectArray[$i]['id']==$spent['project_id']){
					$projectRecordIndex=$i;
					break;
				}
			}
			if ($projectRecordIndex==-1){
				$projectRecordIndex=count($projectArray);
				$projectArray[$projectRecordIndex]['id']=$spent['project_id'];
				$projectArray[$projectRecordIndex]['name']=ucfirst($spent['project_name']);
				$projectArray[$projectRecordIndex]['spenttime'] =0;
				$projectArray[$projectRecordIndex]['allocated'] =0;
				$projectArray[$projectRecordIndex]['allocatedstr'] =0;
			}
			$projectArray[$projectRecordIndex]['spenttime'] +=$spent['spenttime'];
		}
			
		foreach ($allocationQuery as $allocated){
			$timingslot=$allocated['timeSlot'];
			$projectRecordIndex=-1;
			for($i=0;$i<count($projectArray);$i++){
				if($projectArray[$i]['id']==$allocated['project_id']){
					$projectRecordIndex=$i;
					break;
				}
			}
			if ($projectRecordIndex==-1){
				$projectRecordIndex=count($projectArray);
				$projectArray[$projectRecordIndex]['id']=$allocated['project_id'];
				$projectArray[$projectRecordIndex]['name']=ucfirst($allocated['project_name']);
				$projectArray[$projectRecordIndex]['spenttime'] =0;
				$projectArray[$projectRecordIndex]['allocated'] =0;
				$projectArray[$projectRecordIndex]['allocatedstr'] =0;
			}
			$projectArray[$projectRecordIndex]['allocated'] +=$allocated['allocated'];
			$projectArray[$projectRecordIndex]['allocatedstr'] +=$allocated['allocated']*60*60;
		
			$totalallocateinmonth +=$allocated['allocated'];
		}
		$birthdayUsers="";
		$selfBirthday=0;
		
// 		$currentDate=date("Y-m-d H:i:s");
// 		$currentDate=strtotime($currentDate);
		$birthDayQuery = $em->createQuery("SELECT u.id as uid,u.fname as fname,u.lname as lname,u.dob as dob FROM Application\Entity\User u  Where u.isactive=1 ");
		$birthDayQueryResult=$birthDayQuery->getResult();

		
		if(count($birthDayQueryResult)>0){
			
				
			foreach ($birthDayQueryResult as $rw){
				$birthDate= $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rw['dob']),"Asia/Calcutta","d/m/Y");
				$birthDate= explode("/", $birthDate);
				$birthDateDay=$birthDate[0];
				$birthDateMonth=$birthDate[1];
				
				$todayDate=date('d');
				$todayMonth=date('m');
				
				if($todayMonth==$birthDateMonth && $todayDate==$birthDateDay){
				$name=$rw['fname'];
				if(isset($rw['lname']) && $rw['lname']!=""){
					$name.=" ".$rw['lname'];
				}
				if($auth->getIdentity()->id==$rw['uid']){
					$selfBirthday=1;
				}
				if($selfBirthday==1){
					continue;
				}
				if($birthdayUsers==""){
					$birthdayUsers=ucwords($name);
				}
				else{
					$birthdayUsers.=",".ucwords($name);
				}
				}
			}
		}
		//echo "Select a.subject as subject,a.due_date as date from Application\Entity\Activities a JOIN Application\Entity\Assignee as WITH a.id=as.activity_id Where as.user_id=".$auth->getIdentity()->id." order by a.due_date DESC";exit;
		$activityRecord=$em->createQuery("Select a.id as id,a.subject as subject,a.due_date as date,p.name as pname from Application\Entity\Activities a JOIN Application\Entity\Assignee asu WITH a.id=asu.activity_id JOIN Application\Entity\Activitystatuses aas WITH aas.id=a.status_id JOIN Application\Entity\Projects p WITH p.id=a.project_id Where asu.user_id=$userid AND aas.name!='Closed' order by a.due_date DESC ")
		->setFirstResult(0)
		->setMaxResults(5);
		$activityRecordResult=$activityRecord->getResult();
 		
 		$date=date("Y-m-d");
 		$activityDate=strtotime($date)-19800;
// 		$loginTime=$em->CreateQuery("Select l.created_date as cdate,min(l.logintime) as mindt,max(l.logouttime) as maxdt FROM Application\Entity\Login l where l.user_id=$loginUserId and l.created_date=$date")->getResult();
        $loginTime=$em->getRepository('Application\Entity\Login')->getTodayLoginTime($userid,$activityDate);
		$todayLoginTime=$loginTime[0]['mindt']-$loginTime[0]['cdate'];
		$todayLoginTimeStr=$todayLoginTime;
         $todayLoginTime=$common->convertSpentTime($todayLoginTime);
         
		 $lateBy=$loginTime[0]['mindt']-$loginTime[0]['cdate']-$loginTime[0]['timeSlot'];
		 $timingslot=$loginTime[0]['timeSlot'];
		 if ($lateBy>0){
		 	$lateBy=$common->convertSpentTime($lateBy);
		 }else{
		 	$lateBy="00:00";
		 }
		 
		 $month=date("m");
		 $year=date("Y");
		 $offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		 $ss = date($year."-".$month."-01");
		 $strDate = strtotime($ss)+$offset;
		 $num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		 $ee = date($year."-".$month.'-'.$num);
		 $endDate = strtotime($ee)+$offset;
		// $userid=$auth->getIdentity()->id;
		$username=$em->find("Application\Entity\User",$userid);
		//print_r($username);exit;
		$username=ucwords($username->__get('fname')." ".$username->__get('lname'));
		
		
		$dailyQuote = $em->CreateQuery("SELECT da.id as id,da.heading as heading, da.description as description FROM Application\Entity\Dailyquote da")->getArrayResult();
		
		return new ViewModel(array(
				'test' => $this->getEntityManager()->getRepository('Application\Entity\User')->findAll(),
				'birthdayUsers'=>$birthdayUsers,
				'selfBirthday'=>$selfBirthday,
				'activityRecordResult'=>$activityRecordResult,
				'lateBy'=>$lateBy,
				'todayLoginTimeStr'=>$todayLoginTimeStr,
				'todayLoginTime'=>$todayLoginTime,
				'userid'=>$userid,
				'username'=>$username,
				'projectArray'=>$projectArray,
				'totalallocateinmonth'=>$totalallocateinmonth,
				'timingslot'=>$timingslot,
				'dailyQuote'=>$dailyQuote
		));
	}

	public  function birthdaymailAction(){
		$em=$this->getEntityManager();
		$common = new Misc();
			
		$auth = new AuthenticationService();
		
		$birthDayQuery = $em->createQuery("SELECT u.id as uid,u.email as email,u.fname as fname,u.lname as lname,u.dob as dob FROM Application\Entity\User u ");
		$birthDayQueryResult=$birthDayQuery->getResult();
		
		
		if(count($birthDayQueryResult)>0){
				
		
			foreach ($birthDayQueryResult as $rw){
				$birthDate= $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rw['dob']),"Asia/Calcutta","d/m/Y");
				$birthDate= explode("/", $birthDate);
				$birthDateDay=$birthDate[0];
				$birthDateMonth=$birthDate[1];
		
				$todayDate=date('d');
				$todayMonth=date('m');
		
				if($todayMonth==$birthDateMonth && $todayDate==$birthDateDay){

					$name=$rw['fname'];
					if(isset($rw['lname']) && $rw['lname']!=""){
						$name.=" ".$rw['lname'];
					}
					$content="Dear ".ucwords($rw['fname'])." ,<br/><br/><br/><b>Many Many Happy Returns of the day</b> <br/><br/>
					May you have all the joy your heart can hold, All the smiles a day can bring and May God continue to bless the work of your hands and grant you continued favor in your life. </b>
					<br/><br/><br/><br/>
					<div style='font-family:arial,sans-serif;font-size:13px'><div><b>Thanks &amp; Regards,</b></div><div><b>Team IntraNet</b></div></div>
					";
				
					$to=$rw['email'];
					if (APPLICATION_ENV == "development") {
						$common->sendEmail("Happy Birthday Testing Ignore It",$content,$to,"Savitriya");
					} else {
						$common->sendEmail("Happy Birthday",$content,$to,"Savitriya");
					}
				}
			}
		}
	}
	
	public function getRepository()
	{
		return  $this->getEntityManager()->getRepository('Application\Entity\User');
	}

	
	public function newlayoutAction(){
		$viewModel=new ViewModel();
		$viewModel->setTerminal(true);
		return $viewModel;
	}
   	public function loginAction(){
	
		$auth = new AuthenticationService();
		if($auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		
		if($this->getRequest()->isPost()){
			$username=$this->getRequest()->getPost('username');
			$password=$this->getRequest()->getPost('password');
			$message=new \stdClass();
			$message->isvalid=true;
			if($username==""){
				$message->username="null";
				$message->isvalid=false;
			}
			if($password==""){
				$message->password="null";
				$message->isvalid=false;
			}
			if(isset($message->isvalid) && $message->isvalid){
				$authAdapter = new AuthAdapter();
				$authAdapter->setAuthEntityName('Application\Entity\User');
				$authAdapter->setAuthIdentityField('email');
				$authAdapter->setAuthCredentialField('password');
				$authAdapter->setEntityManager($this->getEntityManager());
				$authAdapter->setIdentity($username);
				$authAdapter->setCredential($password);
				$result=$authAdapter->authenticate();
				if(!$result->isValid()){
					$message->isvalid=false;
					switch($result->getCode())
					{
						case Result::FAILURE_CREDENTIAL_INVALID:
							$message->credentials="invalid";
							break;
						case Result::FAILURE_IDENTITY_NOT_FOUND:
						case Result::FAILURE_IDENTITY_AMBIGUOUS:
						case Result::FAILURE:
						default:
							$message->credentials="invalid";
							break;
					}
				}
				$response=array();
				if($message->isvalid){
					$common =new Misc();
					$em=$this->getEntityManager();
					$auth->getStorage()->write($result->getIdentity());
					$ip = $common->getRealIpAddr();
					
					$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
					$date=date("Y-m-d H:i:s");
					$d=$common->ConvertGMTToLocalTimezone($date,'Asia/Calcutta',"Y-m-d H:i:s");
					$exdate=explode(" ",$d);
					$cdate=strtotime($exdate[0]." 00:00:00")+$offset;
					$common =new Misc();
					//$aa = date('Y-m-d H:i:s');
					$datetime = strtotime($date);
					$explodedDate=explode(" ",$date);
					$cdate1=strtotime($explodedDate[0]);
					$ctime = ($datetime - $cdate);
					$login = new Login;
					$login->setUser($auth->getIdentity());
					$login->setLogintime($datetime);
					$login->setipaddress($ip);
					$login->setCreated_date($cdate);
					$login->setCreated_time($ctime);
					$login->setLoggedinby($auth->getIdentity()->id);
					$login->setLoggedoutby($auth->getIdentity()->id);
					try{
						$em->persist($login);
						$em->flush();
					}
					catch (Exception $e)
					{
						echo $e->getMessage();exit;
					}
					return $this->redirect()->toRoute('home');
				}
			}
			$viewModel = new ViewModel(array("messages"=>$message));
			$viewModel->setTerminal(true);
			return $viewModel;
		}else{
			$viewModel = new ViewModel();
			$viewModel->setTerminal(true);
			return $viewModel;
			
		}
		$viewModel=new ViewModel();
		$viewModel->setTerminal(true);
		return $viewModel;
	}

	public function loginreminderAction(){
		/*$auth = new AuthenticationService();
		 if(!$auth->hasIdentity()){
		return $this->redirect()->toRoute('home');
		}*/
		$common=new Misc();
		$em = $this->getEntityManager();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$currentDateInLocal=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s"),"Asia/Calcutta","Y-m-d H:i:s");
		$currentDayName=date("D",strtotime($currentDateInLocal));
		echo "\n-------------------------------------------------\n";
		echo "Date:".$currentDateInLocal."\n\n";
		if($currentDayName=="Sun"){
			echo "Its a Sunday Today,So Skip Executing Login Reminder";
			echo "\n-------------------------------------------------\n";
			exit;
		}
		$aa=date("Y-m-d");
		$today = strtotime($aa)+$offset;
		$yesterday=strtotime("yesterday");
		if($currentDayName=="Mon"){
			$dayBeforeYesterDay=date("Y-m-d",strtotime("-2 days"));
			$yesterday=strtotime($dayBeforeYesterDay);
			echo "Executing Login Reminders for ".$dayBeforeYesterDay."\n";
		}
		
		$day=date("d");
		$year=date("Y");
		$month=date("m");
		$ss = date($year."-".$month."-01");
		$strDate = strtotime($ss)+$offset;
		$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$ee = date($year."-".$month.'-'.$num);
		//$ee = date($year."-".$month.'-t');
		$endDate = strtotime($ee)+$offset;
		$holiday=$em->CreateQuery("SELECT h.date as date FROM Application\Entity\Holiday h  Where h.date BETWEEN $strDate AND $endDate ORDER by h.date DESC")->getResult();
		$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);// 31
		$workingday =array ();
		if (count($num)>0){
			for($i=1;$i<=$num;$i++){
				$date ="$year-$month-$i";
				$currentDayName=date("D",strtotime($date));
				$date=strtotime($date)+$offset;
				foreach ($holiday as $rws){
					if ($date==$rws['date'] || $currentDayName=="Sun"){
						continue 2;
					}					
				}
				if(!in_array($date,$workingday)){
					array_push($workingday, $date);
				}
				$recordIndex=array_search($date,$workingday);
			}
		}
		$currentdate=strtotime(date("Y-m-d"))+$offset;
		for ($j=1;$j<=$day;$j++){
			if($j==1){
				$yesterday=strtotime(date("Y-m-d")." -1 day")+$offset;
				break;
			}
			else if ($workingday[$j]==$currentdate){
				$yesterday=strtotime(date("Y-m-d",$currentdate)." -1 day")+$offset;
				break;	
			}
		}
		$notLoggedInQuery = $em->createQuery("SELECT count(l.id) as lcount,u.id as uid,u.fname as fname,u.email as email,u.lname as lname FROM Application\Entity\User u  LEFT JOIN  Application\Entity\Login l WITH l.user_id=u.id and l.created_date=$today Where u.isactive=1 group by u.id having lcount=0");
		$notLoggedInQueryResult = $notLoggedInQuery->getResult();
		$userMapper=array();
		$mailData=array();
		$j=0;
		if(count($notLoggedInQueryResult)>0){
			foreach ($notLoggedInQueryResult as $rw){
				array_push($userMapper, $rw['uid']);
				$mailData[$j]['user_id']=$rw['uid'];
				$name=$rw['fname'];
				if($rw['lname']!=""){
					$name.=" ".$rw['lname'];
				}
				$mailData[$j]['user_name']=$name;
				$mailData[$j]['email']=$rw['email'];
				$mailData[$j]['isNotLoggedIn']=1;
				$mailData[$j]['isNotLoggedOut']=0;
				$j++;
			}
		}
		$notLoggedOutQuery=$em->createQuery("SELECT l.user_id as uid,l.created_date as cdate,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname,u.email as email FROM Application\Entity\Login l JOIN Application\Entity\User u WITH l.user_id=u.id and l.created_date=$yesterday Where u.isactive=1 group by u.id,l.created_date");
		$notLoggedOutQueryResult=$notLoggedOutQuery->getResult();
		$yesterday6pm=strtotime($yesterday." 12:30pm");
		if(count($notLoggedOutQueryResult)>0){
			foreach ($notLoggedOutQueryResult as $rw){
				$recordIndex=0;
				if(!in_array($rw['uid'],$userMapper)){
					array_push($userMapper, $rw['uid']);
					$recordIndex=$j;
					$mailData[$j]['user_id']=$rw['uid'];
					$name=$rw['fname'];
					if($rw['lname']!=""){
						$name.=" ".$rw['lname'];
					}
					$mailData[$j]['user_name']=$name;
					$mailData[$j]['email']=$rw['email'];
					$mailData[$recordIndex]['isNotLoggedIn']=0;
					$j++;
				}
				else{
					$recordIndex=array_search($rw['uid'], $userMapper);
				}
				if(isset($rw['maxdt']) && $rw['maxdt']!=""){
					if($rw['maxdt']>0){
						$logoutTime=$rw['maxdt'];
						if($logoutTime>$yesterday6pm){
							$mailData[$recordIndex]['isNotLoggedOut']=0;
							continue;
						}
					}
				}
				if($recordIndex!=0){
					$mailData[$recordIndex]['isNotLoggedOut']=1;
				}
			}
		}
		$misc=new Misc();
		$notLoggedInAndNotLoggedOut="";
		$notLoggedIn="";
		$notLoggedOut="";
		foreach ($mailData as $rw){
			$content="";
			if($rw['isNotLoggedIn']==1 && $rw['isNotLoggedOut']==1){
				if($notLoggedInAndNotLoggedOut==""){
					$notLoggedInAndNotLoggedOut=$rw['user_name'];
				}
				else{
					$notLoggedInAndNotLoggedOut.=",".$rw['user_name'];
				}
				$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged out from intranet on yesterday while leaving the office,please inform your logout timings to Maulik Shah,you have also not logged in to the system till ".$misc->ConvertGMTToLocalTimezone(date("Y-m-d H:i"),'Asia/Calcutta','d/m/Y H:i').",please login immediately.";
			}
			else if($rw['isNotLoggedIn']==1){
				if($notLoggedIn==""){
					$notLoggedIn=$rw['user_name'];
				}
				else{
					$notLoggedIn.=",".$rw['user_name'];
				}
				$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged in to the intranet till ".$misc->ConvertGMTToLocalTimezone(date("Y-m-d H:i"),'Asia/Calcutta','d/m/Y H:i').",please login immediately.";
			}
			else if($rw['isNotLoggedOut']==1){
				if($notLoggedOut==""){
					$notLoggedOut=$rw['user_name'];
				}
				else{
					$notLoggedOut.=",".$rw['user_name'];
				}
				$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged out from intranet on yesterday while leaving the office,please inform your logout timings to Maulik Shah.";
			}
			$to=array();
			$to[0]['email']=$rw['email'];
			$to[0]['name']=$rw['user_name'];
			
			
			if($content!=""){

				if (APPLICATION_ENV == "development") {
					$misc->sendEmail("Login Reminder From Intranet Testing Ignore It",$content,$to,"Intranet");
				} else {
					$misc->sendEmail("Login Reminder From Intranet",$content,$to,"Intranet");
				}
				
					}
		}
		$content="";
		if($notLoggedInAndNotLoggedOut!=""){
			$content=$notLoggedInAndNotLoggedOut." have not logged out yesterday and also has not logged in today";
		}

		if($notLoggedIn){
			if($content==""){
				$content=$notLoggedIn." have not logged in today";
			}
			else{
				$content.="<br/>".$notLoggedIn." have not logged in today";
			}
		}
		if($notLoggedOut){
			if($notLoggedOut==""){
				$content=$notLoggedOut." have not logged out yesterday";
			}
			else{
				$content.="<br/>".$notLoggedOut." have not logged out yesterday";
			}
		}
		if($content!=""){
			$reader = new Ini();
			$data =$reader->fromFile(__DIR__."/../../../../../config/application.ini");
			$to2=Decoder::decode($data['login_summary_recipient']);
			$to=array();
			$j=0;
			$to2 = $to2->data;
			foreach ($to2 as $rw){
				$to[$j]['email'] = $rw->email;
				$to[$j]['name'] = ucwords($rw->name);
				$j++;
			}
			if($content!=""){
				
				if (APPLICATION_ENV == "development") {
				$misc->sendEmail("Login Reminder Summary From Intranet Testing Purpose Ignore It", $content, $to,"Intranet");
				} else {
                $misc->sendEmail("Login Reminder Summary From Intranet", $content, $to,"Intranet");
				}
				
				
			}
		}
		echo $content;
		//print_r($mailData);
		echo "\n-------------------------------------------------\n";
		exit;


		/*$auth = new AuthenticationService();
		 if(!$auth->hasIdentity()){
		return $this->redirect()->toRoute('home');
		}
		$common=new Misc();
		$em = $this->getEntityManager();
		$currentDateInLocal=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s"),"Asia/Calcutta","Y-m-d H:i:s");
		$currentDayName=date("D",strtotime($currentDateInLocal));
		echo "\n-------------------------------------------------\n";
		echo "Date:".$currentDateInLocal."\n\n";
		$holiday = $em->CreateQuery("SELECT h.date as date from Application\Entity\Holiday h")->getResult();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		foreach ($holiday as $rws){
		if($rws['date']==strtotime(date("Y-m-d"))+$offset){
		echo "Its a Holiday Today,So Skip Executing Login Reminder";
		echo "\n-------------------------------------------------\n";
		exit;
		}
		}
		if($currentDayName=="Sun"){
		echo "Its a Sunday Today,So Skip Executing Login Reminder";
		echo "\n-------------------------------------------------\n";
		exit;
		}
		$aa=date("Y-m-d");
		$today = strtotime($aa);
		$yesterday=strtotime("yesterday");
		if($currentDayName=="Mon"){
		$dayBeforeYesterDay=date("Y-m-d",strtotime("-2 days"));
		$yesterday=strtotime($dayBeforeYesterDay);
		echo "Executing Login Reminders for ".$dayBeforeYesterDay."\n";
		}
		$notLoggedInQuery = $em->createQuery("SELECT count(l.id) as lcount,u.id as uid,u.fname as fname,u.email as email,u.lname as lname FROM Application\Entity\User u  LEFT JOIN  Application\Entity\Login l WITH l.user_id=u.id and l.created_date=$today group by u.id having lcount=0");
		$notLoggedInQueryResult = $notLoggedInQuery->getResult();
		$userMapper=array();
		$mailData=array();
		$j=0;
		if(count($notLoggedInQueryResult)>0){
		foreach ($notLoggedInQueryResult as $rw){
		array_push($userMapper, $rw['uid']);
		$mailData[$j]['user_id']=$rw['uid'];
		$name=$rw['fname'];
		if($rw['lname']!=""){
		$name.=" ".$rw['lname'];
		}
		$mailData[$j]['user_name']=$name;
		$mailData[$j]['email']=$rw['email'];
		$mailData[$j]['isNotLoggedIn']=1;
		$mailData[$j]['isNotLoggedOut']=0;
		$j++;
		}
		}
		$notLoggedOutQuery=$em->createQuery("SELECT l.user_id as uid,l.created_date as cdate,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname,u.email as email FROM Application\Entity\Login l JOIN Application\Entity\User u WITH l.user_id=u.id Where l.created_date=$yesterday and u.isactive=1 group by u.id,l.created_date");
		$notLoggedOutQueryResult=$notLoggedOutQuery->getResult();
		$yesterday6pm=strtotime($yesterday." 12:30pm");
		if(count($notLoggedOutQueryResult)>0){
		foreach ($notLoggedOutQueryResult as $rw){
		$recordIndex=0;
		if(!in_array($rw['uid'],$userMapper)){
		array_push($userMapper, $rw['uid']);
		$recordIndex=$j;
		$mailData[$j]['user_id']=$rw['uid'];
		$name=$rw['fname'];
		if($rw['lname']!=""){
		$name.=" ".$rw['lname'];
		}
		$mailData[$j]['user_name']=$name;
		$mailData[$j]['email']=$rw['email'];
		$mailData[$recordIndex]['isNotLoggedIn']=0;
		$j++;
		}
		else{
		$recordIndex=array_search($rw['uid'], $userMapper);
		}
		if(isset($rw['maxdt']) && $rw['maxdt']!=""){
		if($rw['maxdt']>0){
		$logoutTime=$rw['maxdt'];
		if($logoutTime>$yesterday6pm){
		$mailData[$recordIndex]['isNotLoggedOut']=0;
		continue;
		}
		}
		}
		if($recordIndex!=0){
		$mailData[$recordIndex]['isNotLoggedOut']=1;
		}
		}
		}
		$misc=new Misc();
		$notLoggedInAndNotLoggedOut="";
		$notLoggedIn="";
		$notLoggedOut="";
		foreach ($mailData as $rw){
		$content="";
		if($rw['isNotLoggedIn']==1 && $rw['isNotLoggedOut']==1){
		if($notLoggedInAndNotLoggedOut==""){
		$notLoggedInAndNotLoggedOut=$rw['user_name'];
		}
		else{
		$notLoggedInAndNotLoggedOut.=",".$rw['user_name'];
		}
		$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged out from intranet on yesterday while leaving the office,please inform your logout timings to Maulik Shah,you have also not logged in to the system till ".$misc->ConvertGMTToLocalTimezone(date("Y-m-d H:i"),'Asia/Calcutta','d/m/Y H:i').",please login immediately.";
		}
		else if($rw['isNotLoggedIn']==1){
		if($notLoggedIn==""){
		$notLoggedIn=$rw['user_name'];
		}
		else{
		$notLoggedIn.=",".$rw['user_name'];
		}
		$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged in to the intranet till ".$misc->ConvertGMTToLocalTimezone(date("Y-m-d H:i"),'Asia/Calcutta','d/m/Y H:i').",please login immediately.";
		}
		else if($rw['isNotLoggedOut']==1){
		if($notLoggedOut==""){
		$notLoggedOut=$rw['user_name'];
		}
		else{
		$notLoggedOut.=",".$rw['user_name'];
		}
		$content="Dear ".ucwords($rw['user_name']).",<br/>You have not logged out from intranet on yesterday while leaving the office,please inform your logout timings to Maulik Shah.";
		}
		$to=array();
		$to[0]['email']=$rw['email'];
		$to[0]['name']=$rw['user_name'];
		if($content!=""){
		$misc->sendEmail("Login Reminder From Intranet",$content,$to,"Intranet");
		}
		}
		$content="";
		if($notLoggedInAndNotLoggedOut!=""){
		$content=$notLoggedInAndNotLoggedOut." have not logged out yesterday and also has not logged in today";
		}

		if($notLoggedIn){
		if($content==""){
		$content=$notLoggedIn." have not logged in today";
		}
		else{
		$content.="<br/>".$notLoggedIn." have not logged in today";
		}
		}

		if($notLoggedOut){
		if($notLoggedOut==""){
		$content=$notLoggedOut." have not logged out yesterday";
		}
		else{
		$content.="<br/>".$notLoggedOut." have not logged out yesterday";
		}
		}

		if($content!=""){
		$reader = new Ini();
		$data =$reader->fromFile(__DIR__."/../../../../../config/application.ini");
		$to2=Decoder::decode($data['login_summary_recipient']);
		$to=array();
		$j=0;
		foreach ($to2 as $rw){
		$to[$j]['email']=$rw[$j]->email;
		$to[$j]['name']=ucwords($rw[$j]->name);
		}
		if($content!=""){
		$misc->sendEmail("Login Reminder Summary From Intranet", $content, $to,"Intranet");
		}
		}
		echo $content;
		//print_r($mailData);
		echo "\n-------------------------------------------------\n";
		exit;*/
	}
	public function sessionexpiredAction(){
		$auth = new AuthenticationService();
		if($auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
	}
	public function logoutAction(){
		$common=new Misc();
		$auth = new AuthenticationService();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		if($auth->hasIdentity()){
			$lId=$auth->getIdentity()->id;
			//date_default_timezone_set('Asia/Calcutta');
			$date1=date("Y-m-d H:i:s");
			$d=$common->ConvertGMTToLocalTimezone($date1,'Asia/Calcutta',"Y-m-d H:i:s");
			$mdate=explode(" ",$d);			
			$cdate=strtotime($mdate[0]." 00:00:00")+$offset;		
			//echo $cdate;exit;
			$em = $this->getEntityManager();
			$countQuery = $em->createQuery("SELECT max(l.id) as maxid,l.user_id as userid FROM Application\Entity\Login l Where l.user_id=$lId And l.created_date=$cdate");
			$totalRecords = $countQuery->getResult();
			 
			if(count($totalRecords)>0){
				$maxid = $totalRecords[0]['maxid'];
			
				if(isset($maxid) && $maxid>0){
			
					$loginRepository=$em->getRepository('Application\Entity\Login');
					$login = $loginRepository->find($maxid);
					$logout= date('Y-m-d H:i:s');
					$logouttime = strtotime($logout);
					$today = date('Y-m-d');
					$todaytime = strtotime($today)+$offset;
					$cdate=$login->getCreated_date();
				//	echo $cdate;exit;
				 if ($todaytime==$cdate){
				 
                 	$login->setLogouttime($logouttime);
                  }
                  else {
                  	$todaylogout=strtotime($today." 23:59:59")+$offset;
                  	
                  	$login->setLogouttime($todaylogout);
                  }
                    $login->setLoggedoutby($auth->getIdentity()->id);
					$em->persist($login);
					$em->flush();
				}
			}
			$auth->clearIdentity();
		}else{
			return $this->redirect()->toRoute('sessionexpired');
		}
	}
	public function loginreportAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
	}

	public function changepasswordAction(){
		$auth = new AuthenticationService();
		$em=$this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$auth = new AuthenticationService();
		if($this->getRequest()->isGet()){
			if($auth->getIdentity()->isadmin ==1){
				$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
				return new ViewModel(array('users' =>$users));
			}
		}
		if($this->getRequest()->isPost()){
			$opassword=$this->getRequest()->getPost('opassword');
			$npassword=$this->getRequest()->getPost('npassword');
			$uId=$this->getRequest()->getPost('user');
			$response=array();
			if($opassword=="" && $uId==""){
				$response['data']['opassword']="null";
			}
			
			if($npassword==""){
				$response['data']['npassword']="null";
			}
			
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}
			
			if(count($response)==0){					
				if ($uId>0){
					$id=$uId;
					$user=$em->getRepository('Application\Entity\User')->findOneBy(array(
							'id' => $id,
					));
				}
				else {
					$id=$auth->getIdentity()->id;
					$user=$em->getRepository('Application\Entity\User')->findOneBy(array(
							'id' => $id,
							'password' => md5($opassword)
					));
				}
				if($user !== null) {
					$user->__set('password',md5($npassword));
					try{
						$em->persist($user);
						$em->flush();
						$to=array();
						$name=$user->__get('fname');
						$lname=$user->__get('lname');
						if($lname!=""){
							$name.=" ".$lname;
						}
						$misc=new Misc();
						$to[0]['email']=$user->__get('email');
						$to[0]['name']=$name;
						$content="Dear ".$name.",<br/>"."your password has been changed your new password is <b>".$npassword."</b>";
						try{
							if (APPLICATION_ENV == "development") {
							$misc->sendEmail("Testing Ignore It Intranet Password Change Notification", $content, $to,"Intranet");
							}else {
								$misc->sendEmail("Intranet Password Change Notification", $content, $to,"Intranet");
							}
							}
						catch (Exception $e){

						}
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
					}
				}
				else{
					$response['returnvalue']="invalid";
					$response['data']['opassword']="invalid";
				}
			}
			echo json_encode($response);
			exit;
		}
	}

	public function forgotpasswordAction(){
		if($this->getRequest()->isPost()){
			$email=$this->getRequest()->getPost('email');
			if($email==""){
				$response['data']['email']="null";
			}
			if ($email!="" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$response['data']['email']="invalid";
			}
			$response=array();
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("valid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}
			if(count($response)==0){
				$em=$this->getEntityManager();
				$user=$em->getRepository('Application\Entity\User')->findOneBy(array(
						'email' => $email
				));
				if($user !== null) {
					try{
						$password=substr(uniqid(),0,7);
						$encPassword=md5($password);
						$to=array();
						$name=$user->__get('fname');
						$lname=$user->__get('lname');
						$user->__set('password',$encPassword);
						try{
							$em->persist($user);
							$em->flush();
						}
						catch (Exception $e)
						{
							echo $e->getMessage();exit;
						}
						if($lname!=""){
							$name.=" ".$lname;
						}
						$misc=new Misc();

						$to[0]['email']=$user->__get('email');
						$to[0]['name']=ucwords($name);
						$content="Dear ".ucwords($name).",<br/>"."your password is <b>".$password."</b>";
						if (APPLICATION_ENV == "development") {
						$misc->sendEmail("Testing Ignore It IntraNet Password Notification", $content, $to,"Intranet");
						}else {
							$misc->sendEmail("IntraNet Password Notification", $content, $to,"Intranet");
						}
						
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
					}
				}
				else{
					$response['returnvalue']="invalid";
					$response['data']['email']="invalid";
				}
			}
			echo json_encode($response);
			exit;
		}
	}

	public function gridAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
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
			$sidx="l.created_date";
		}
		else if($sidx=="cdate"){
			$sidx="l.created_date";
		}
		else if($sidx=="username"){
			$sidx="u.fname";
		}
		else if($sidx=="userid"){
			$sidx="l.user_id";
		}
		if($sord==""){
			$sord="DESC";
		}
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery('SELECT l.id FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id
				group by u.id,l.created_date');
		$totalRecords = $countQuery->getResult();
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$query = $em->createQuery("SELECT l.id as id,l.user_id as uid,l.created_date as cdate,l.created_time as ctime,min(l.logintime) as mindt,max(l.logouttime) as maxdt,l.ipaddress as ipaddress,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u WITH l.user_id=u.id group by u.id,l.created_date order by $sidx $sord")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$loginRows = $query->getResult();
		$i=0;
		$common =new Misc();
		foreach ($loginRows as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$min=$rws['mindt'];
			$max=$rws['maxdt'];
			$date = $rws['cdate'];
			$time = $rws['ctime'];
			$timeDiff="00:00:00";
			$maxdt="";
			$mindt="";
			$cdate="";
			$ctime="";
			if($min!="" && $min>0)
			{
				$mindt = date("Y-m-d H:i:s",$min);
			}
			if($max!="" && $max>0)
			{
				$maxdt = date("Y-m-d H:i:s", $max);
			}
			if($date!="" && $date>0)
			{
				if($time!="" && $time>0)
				{
					$cdate = date("Y-m-d H:i:s", $date+$time);
				}
				else{
					$cdate = date("Y-m-d H:i:s", $date);
				}
			}
			if($maxdt!=''){
				$timeDiff = $common->getTimeDiff($maxdt, $mindt);
			}
			$crdate =$common->ConvertGMTToLocalTimezone(($cdate),'Asia/Calcutta','d/m/Y H:i:s');
			if(isset($mindt) && $mindt!=""){
				$mindt=$common->ConvertGMTToLocalTimezone($mindt,"Asia/Calcutta");
			}
			else{
				$mindt='';
			}
			if(isset($maxdt) && $maxdt!=""){
				$maxdt=$common->ConvertGMTToLocalTimezone($maxdt,"Asia/Calcutta");
			}
			else{
				$maxdt='';
			}
			$response['rows'][$i]['cell']=array(ucwords($rws['fname'] . " ".$rws['lname']),$mindt,$maxdt,$timeDiff,$crdate,$rws['ipaddress'],$rws['uid']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}

	public function getlogindetailbyuserAction()
	{
		$id =$this->getRequest()->getPost('userid');
		$aa =$this->getRequest()->getPost('cdate');
		$common =new Misc();
		$bb=$common->Timeformat($aa,'Y-m-d');
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$cdate = strtotime($bb)+$offset;
		$em = $this->getEntityManager();
		$query = $em->createQuery("SELECT l.id as id,l.created_date as cdate,l.logintime as login,l.logouttime as logout,l.ipaddress as ipaddress FROM Application\Entity\Login l WHERE l.user_id =$id AND l.created_date=$cdate");
		$loginRows = $query->getResult();
		$i=0;
		$response=array();
		foreach ($loginRows as $rws){
			$in=$rws['login'];
			$out=$rws['logout'];
			$logoutd="";
			$logind="";
			if($in!="" && $in>0)
			{
				$logind = date("Y-m-d H:i:s", $in);
			}
			if($out!="" && $out>0)
			{
				$logoutd = date("Y-m-d H:i:s", $out);
			}
			if(isset($logind) && $logind!=""){
				$logind=$common->ConvertGMTToLocalTimezone($logind,"Asia/Calcutta");
			}
			else{
				$logind='';
			}
			if(isset($logoutd) && $logoutd!=""){
				$logoutd=$common->ConvertGMTToLocalTimezone($logoutd,"Asia/Calcutta");
			}
			else{
				$logoutd='';
			}
			$action="<a href='javascript:deletesubrow(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($logind,$logoutd,$rws['ipaddress'],$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	
	public function  deleteloginrowAction(){
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$response=array();
			
			$delete =$em->find('Application\Entity\Login', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue']="valid";
			}
			header("Content-type: application/json");
			echo json_encode($response);
			exit();
	}
}
}