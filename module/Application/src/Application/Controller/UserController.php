<?php
namespace Application\Controller;

use Doctrine\Tests\Models\ECommerce\ECommerceCart;
use Zend\Filter\File\LowerCase;
use Doctrine\ORM\Query\AST\Functions\LowerFunction;
use	Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Decoder;
use Zend\Authentication\AuthenticationService;
use Application\Entity\User;
use Zend\Validator\EmailAddress;
use IntranetUtils\AuthAdapter as AuthAdapter;
use IntranetUtils\Common as Misc;
use Zend\Config\Reader\Ini;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use DoctrineModule\Authentication\Adapter as DoctrineAuthAdapter;
use Zend\Authentication\Result;
use Doctrine\ORM\Query\Expr;
use Application\Entity\Login;
use Application\Entity\Holiday;
use Application\Entity\Preferences;
use Zend\Authentication\Storage;
use Zend\Authentication\Storage\Session as SessionStorage;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Application\Entity\Company;
use Application\Entity\Contact;
use IntranetUtils\Common as IntraCommon;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter as Adapter;
use Application\Entity\TimingSlot;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class UserController extends AbstractActionController
{
	/**
	 * @var Doctrine\ORM\EntityManager
	 *
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
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}

	public function griduserAction(){
		$companyId = $this->getRequest()->getPost('companyid');

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
			$sidx="u.fname";
		}else if($sidx=="name"){
			$sidx="u.fname";
		}else if($sidx=="email"){
			$sidx="u.email";
		}else if($sidx=="mobile"){
			$sidx="u.mobile";
		}else if($sidx=="dob"){
			$sidx="u.dob";
		}
		$where="";
		if(isset($companyId) && $companyId>0){
			$where="u.company_id=$companyId";
		}else{
			$where="1=1";
		}
		$em = $this->getEntityManager();
		//$totalRecords = $em->getRepository("Application\Entity\User")->findAll();
		$totalRecords = $em->createQuery("SELECT u.id as id  FROM Application\Entity\User u WHERE $where")->getResult();
		//createQuery('SELECT u.id as id  FROM Application\Entity\User u ');
		//$totalRecords = $countQuery->getResult();

		if($limit==0){
			//	$limit=5;
		}
		$totalPages = 0;
		if (count($totalRecords)>0 && $limit>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$em = $this->getEntityManager();
		$usersQuery = $em->createQuery("SELECT u.id as id,u.fname as fname,u.lname as lname,u.email as email,u.password as password,u.mobile as mobile,u.dob as dob,u.isactive as active,u.isadmin as admin  FROM Application\Entity\User u WHERE $where order by $sidx $sord")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$totalrows = $usersQuery->getResult();


		$i=0;
		$common=new Misc();
		foreach ($totalrows as $rws){
			$dob=$rws['dob'];
			$db='';
			if(isset($dob) && $dob!="-" && $dob!=""){
				$db=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$dob),"Asia/Calcutta","d/m/Y");
			}
			$action="<a href='/editcontact/userid/".$rws['id']."'><i class='icon-edit'></i>&nbsp;<a href='javascript:deleteUser(".$rws['id'].')'."'><i class='icon-trash'></i>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['fname']." ".$rws['lname']),$rws['email'],$rws['mobile'],$db,$rws['active'],$rws['admin'],$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}

	/*
	 public function adduserAction(){
	$misc=new Misc();
	if($this->getRequest()->isPost()){
	$em=$this->getEntityManager();
	$id=$this->getRequest()->getPost('id');
	$lowername=$this->getRequest()->getPost('name');
	$name=strtolower($lowername);
	$name=explode(" ", $name);
	$fname=$name[0];
	$lname="";
	if(count($name)>=2){
	$lname=$name[1];
	}
	$email=$this->getRequest()->getPost('email');
	$password=$this->getRequest()->getPost('password');
	$mobile=$this->getRequest()->getPost('mobile');
	$dob=$this->getRequest()->getPost('dob');
	$dobmd="";
	$doy="";
	if($dob!=""){
	$dob=explode("-", $dob);

	if(count($dob)>=3){
	$dobmd=$dob[0]."-".$dob[1];
	$doy=$dob[2];
	}
	}
	$isactive=$this->getRequest()->getPost('isactive');
	$isadmin=$this->getRequest()->getPost('isadmin');
	$where="";
	if ($id>0){
	$where="u.id!=$id";
	}
	if (isset($email) && $email!=""){
	if ($where==""){
	$where="u.email='$email'";
	}else {
	$where.=" AND u.email='$email'";
	}
	}else {
	$where="1!=1";
	}
	$duplicateEmail=$em->CreateQuery("SELECT u.id from Application\Entity\User u where $where ")->getresult();
	if (count($duplicateEmail)>0){
	$add=new \stdClass();
	if($id>0){
	$add=$em->find('Application\Entity\User', $id);

	}
	else{
	$newRecord = new Preferences();
	$add =new User();
	$addcontact=new Contact();
	$addcontact->__set('user_id',"");
	$uniq=substr(uniqid(),0,7);
	$content="Dear ".ucwords($fname).",<br/>"."your password is <b>".$uniq."</b>";
	$misc->sendEmail("IntraNet Password Notification", $content, $email,ucwords($fname));
	$add->__set('password',md5($uniq));
	$newRecord->setCc("");
	$newRecord->setTomail("");

	}
	$add->__set('fname',$fname);
	$add->__set('lname',$lname);
	$add->__set('email',$email);
	$add->__set('mobile',$mobile);
	$add->__set('dob',$dobmd);
	$add->__set('doy',$doy);
	$add->__set('isactive',$isactive);
	$add->__set('isadmin',$isadmin);
	try{
	$em->persist($add);
	$em->flush();
	$newUID = $add->__get('id');
	if ($id==0){
	$newRecord->setUser_id($newUID); //NOT DYNAMICALLY SETTING
	$em->persist($newRecord);
	$addcontact->__set('user_id',$newUID);
	$em->persist($addcontact);
	$em->flush();
	}
	}
	catch (Exception $e)
	{
	echo $e->getMessage();exit;
	}
	}
	exit;
	}
	}
	*/

	public function deleteuserAction(){
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$deleteuser =$em->find('Application\Entity\User', $id);
			if ($deleteuser) {
				$this->getEntityManager()->remove($deleteuser);
				$this->getEntityManager()->flush();
			}
		}exit;
	}
	public function attendancereportAction()
	{
		$em = $this->getEntityManager();
		$auth = new AuthenticationService();
		$companyIndex=0;
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		$month=$this->getRequest()->getPost('month');

		$year=$this->getRequest()->getPost('year');
		if($month==""){
			$month=date("m");
		}
		if($year==""){
			$year=date("Y");
		}
		if($month==date('m') && $year==date('Y')){
			$numc=date('d');
		}else{
			$numc=31;
		}
		$common =new Misc();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$aa = date($year."-".$month."-01");
		$strDate = strtotime($aa)+$offset;
		$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$bb = date($year."-".$month.'-'.$num);

		$endDate = strtotime($bb)+$offset;
		$cidLoginUser= $auth->getIdentity()->company_id;
		/*
		 if($auth->getIdentity()->isadmin!=1){
		$user=$em->CreateQuery("Select u from Application\Entity\User u where u.company_id=$cidLoginUser")->getResult();
		$userQuery=$em->createQuery("SELECT u.company_id as cid,l.user_id as uid,l.created_date as cdate,l.loggedinby as inby,l.loggedoutby as outby,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id AND l.created_date BETWEEN $strDate AND $endDate AND u.isactive=1 AND u.company_id=$cidLoginUser  group by u.id,l.created_date");
		$uQuery = $userQuery->getResult();
		$company=$em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id and u.company_id=$cidLoginUser group by u.company_id")->getResult();

		}else{
		//	echo $auth->getIdentity()->isadmin;exit();
		$companyId=$this->getRequest()->getPost('company');
		if(isset($companyId) && $companyId!=""){
		$cidLoginUser=$companyId;
		}
		$user=$em->getRepository('Application\Entity\User')->getUserGroupByCompany();
		$userQuery=$em->createQuery("SELECT u.company_id as cid,l.user_id as uid,l.created_date as cdate,l.loggedinby as inby,l.loggedoutby as outby,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id AND l.created_date BETWEEN $strDate AND $endDate AND u.isactive=1 group by u.id,l.created_date");
		$uQuery = $userQuery->getResult();
		$company=$em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id group by u.company_id")->getResult();
		}
		*/

		if($auth->getIdentity()->isadmin==1){
			$companyId=$this->getRequest()->getPost('company');
			if(isset($companyId) && $companyId!=""){
				$cidLoginUser=$companyId;
			}
			$viewCompanies=$em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id group by u.company_id")->getResult();
		}
		else{
			$viewCompanies=$em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id and u.company_id=$cidLoginUser group by u.company_id")->getResult();
		}
		$user=$em->CreateQuery("Select u from Application\Entity\User u where u.company_id=$cidLoginUser AND (( $strDate < u.leavingdate  OR u.leavingdate IS NULL) AND (u.isactive=1)) ")->getResult();
		//echo $user;exit;
		$userQuery=$em->createQuery("SELECT u.company_id as cid,l.user_id as uid,l.created_date as cdate,l.loggedinby as inby,l.loggedoutby as outby,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id AND l.created_date BETWEEN $strDate AND $endDate AND u.isactive=1 AND u.company_id=$cidLoginUser AND ( $strDate < u.leavingdate OR u.leavingdate IS NULL) group by u.id,l.created_date");
		$uQuery = $userQuery->getResult();
		$company=$em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id and u.company_id=$cidLoginUser group by u.company_id")->getResult();

		//$companyqueru=$em->CreatQuery("Select c.id as cid,c.name as cname from Application\Entity\Company c")->getResult();
		$response=array();
		$userMapper=array();
		$companyMapper=array();
		$i=0;
		$z=0;
		$monthDays = cal_days_in_month(CAL_GREGORIAN,$month, $year); // 31

		foreach ($user as $uorder){
			// 			$leavingdate=$uorder->__get('leavingdate');
			// 			if ($strDate<$leavingdate || !isset($leavingdate)){
			array_push($userMapper, $uorder->__get('id'));

			if(!in_array($uorder->__get('company_id'), $companyMapper)){
				array_push($companyMapper,$uorder->__get('company_id'));
				$response[$z]=array();
				$i=0;
				$response[$z]['id']=$uorder->__get('company_id');
				$z++;
			}
			$recordIndex=-1;
			for($k=0;$k<sizeof($response);$k++){
				if($uorder->__get('company_id')==$response[$k]['id']){
					$recordIndex=$k;
				}
			}
			if($uorder->__get('id')==$auth->getIdentity()->id){
				$companyIndex=$recordIndex;
			}
			if($recordIndex==-1){
				continue;
			}
			$slotTimingId = $uorder->__get('timing_slot_id');
			$slotTime = $em->createQuery("SELECT ts.slot_login_time as login_time FROM Application\Entity\TimingSlot ts WHERE ts.slot_id = $slotTimingId")->getResult();
			
			$response[$recordIndex]['cname']='';
			$response[$recordIndex]['user'][$i]['user_id']=$uorder->__get('id');
			$response[$recordIndex]['user'][$i]['user_name']=ucwords($uorder->__get('fname') . " ".$uorder->__get('lname'));
			$response[$recordIndex]['user'][$i]['user_id']=$uorder->__get('id');
			$response[$recordIndex]['user'][$i]['companyid']=$uorder->__get('company_id');
			$response[$recordIndex]['user'][$i]['totalspenttime']=0;
			$response[$recordIndex]['user'][$i]['login_slot']=$slotTime[0]['login_time'];
			$response[$recordIndex]['totalspenttime']=0;
			for ($j=1;$j<=$monthDays;$j++){
				$formatedMonthDay=sprintf('%02d', $j);
				$response[$recordIndex]['user'][$i][$formatedMonthDay]=array();
				$response[$recordIndex]['user'][$i][$formatedMonthDay]['intime']='';
				$response[$recordIndex]['user'][$i][$formatedMonthDay]['outtime']='';
				$response[$recordIndex]['user'][$i][$formatedMonthDay]['spenttime']='';
				$response[$recordIndex]['user'][$i][$formatedMonthDay]['loginByResult']='';
				$response[$recordIndex]['user'][$i][$formatedMonthDay]['logoutByResult']='';
					
			}
			$i++;
			// 			}
		}

		$reader = new Ini();
		$data =$reader->fromFile(__DIR__."/../../../../../config/application.ini");
		$to2=Decoder::decode($data['allowedIpAddresses']);
		$ip= $to2->data->ipaddress;
		$ipexplode=explode(",",$ip);

		$ipaddresses="";
		foreach ($ipexplode as $ipa){
			if($ipaddresses==""){
				$ipaddresses="'".$ipa."'";
			}
			else{
				$ipaddresses.=","."'".$ipa."'";
			}
		}

		//$userQuery=$em->createQuery("SELECT l.user_id as uid,l.created_date as cdate,l.loggedinby as inby,l.loggedoutby as outby,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id AND l.created_date BETWEEN $strDate AND $endDate AND u.isactive=1 AND l.ipaddress In($ipaddresses) group by u.id,l.created_date");
		//echo "SELECT l.user_id as uid,l.created_date as cdate,min(l.logintime) as mindt,max(l.logouttime) as maxdt,u.fname as fname,u.lname as lname FROM Application\Entity\Login l JOIN Application\Entity\User u Where l.user_id=u.id AND l.created_date BETWEEN $strDate AND $endDate AND u.isactive=1 AND u.id=11	group by u.id,l.created_date";exit;

		$holiday=$em->createQuery("SELECT h.id as id,h.date as date from Application\Entity\Holiday h WHERE h.date BETWEEN $strDate AND $endDate ")->getResult();

		//print_r($uQuery);exit;
		foreach ($uQuery as $rwi){
			if($rwi['cid']=="" ){
				continue;
			}
			//$userMapperIndex=array_search($rwi['uid'], $userMapper);
			$companyMapperIndex=array_search($rwi['cid'], $companyMapper);
			$data=$response[$companyMapperIndex]['user'];
			$userMapperIndex=0;
			for($u=0;$u<count($data);$u++){
				if($data[$u]['user_id']==$rwi['uid']){
					$userMapperIndex=$u;
				}

			}
			//	echo $userMapperIndex."/".$companyMapperIndex;exit();
			$min=$rwi['mindt'];
			$max=$rwi['maxdt'];
			$cdate=$rwi['cdate'];
			$inBy=$rwi['inby'];
			$outBy=$rwi['outby'];
			$timediff="00:00";
			$secondsDiff=0;
			$maxdt="";
			$mindt="";
			if($min!="" && $min>0)
			{
				$mindt = date("Y-m-d H:i:s", $min);
			}
			if($max!="" && $max>0)
			{
				$maxdt = date("Y-m-d H:i:s", $max);
			}
			if($maxdt!=''){
				$timediff = $common->getTimeDiff($maxdt, $mindt,'H:i');
				if(($max>0 && $max!="") && ($min>0 && $min!="")){
					$secondsDiff=$max-$min;
					/*echo $max."  ";
					 echo $min;
					echo "<br/>";*/
				}
			}
			//echo $maxdt."($max) ".$mindt."($min) ".$timediff	." <br/>";
			if($mindt !=""){
				$mindt=$common->ConvertGMTToLocalTimezone($mindt,"Asia/Calcutta","Y-m-d H:i");
				$dateString=explode("-",$mindt);
				if(count($dateString)>0){
					$separateBySpace=explode(" ",$dateString[2]);
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['login']=$rwi['mindt'];
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['cdate']=$rwi['cdate'];
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['intime']=$separateBySpace[1];
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['loginByResult']=$inBy;
				}
			}
			if($maxdt!=""){
				$maxdt=$common->ConvertGMTToLocalTimezone($maxdt,"Asia/Calcutta","Y-m-d H:i");
				$dateString=explode("-",$maxdt);
				if(count($dateString)>0){
					//echo $userMapperIndex;

					$separateBySpace=explode(" ",$dateString[2]);
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['outtime']=$separateBySpace[1];
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['logoutByResult']=$outBy;
					$response[$companyMapperIndex]['user'][$userMapperIndex][$separateBySpace[0]]['spenttime']=$timediff;
					$response[$companyMapperIndex]['user'][$userMapperIndex]['totalspenttime']=$response[$companyMapperIndex]['user'][$userMapperIndex]['totalspenttime']+$secondsDiff;
				}
			}

		}
			
		$listHoliday=array();
		foreach ($holiday as $rw){
			$hDate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rw['date']),"Asia/Calcutta","Y-m-d");
			$dateString=explode("-",$hDate);
			if(count($dateString)>0){
				array_push($listHoliday,$dateString[2]);
			}
		}

		//print_r($response[2])."</br>";exit;
		//echo count($response);exit();
		//print_r($response);exit();
		return new ViewModel(array('company'=>$company,'response' => $response,'month'=>$month,'year'=>$year,'numc'=>$numc,'holiday'=>$listHoliday,'companyIndex'=>$companyIndex,"viewCompanies"=>$viewCompanies));

	}

	public function reportnotfilledAction(){
		$common =new Misc();
		$em=$this->getEntityManager();
		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		//$projectTable = new TableGateway('user', $dbAdapter);
		$user=$dbAdapter->query("select * from user where isactive=1 AND needdailyreport=1",Adapter::QUERY_MODE_EXECUTE);
		//$rowset = $projectTable->select(array('isactive'=>"1"));

		$senderUserMapper=array();
		$senderList=array();
		$reportNotFilledList=array();
		set_time_limit(0);
		$templateActivityDate="";
		foreach ($user as $userRow) {
				
			$where="";
			$userid=$userRow->id;
			$where="al.user_id=$userid";
			$preferences=$dbAdapter->query("Select * from preferences p where p.user_id=$userid",Adapter::QUERY_MODE_EXECUTE);
			$yesterday=gmdate("Y-m-d", time() - 60 * 60 * 24);
			$activityDate= $common->ConvertLocalTimezoneToGMT($yesterday,'Asia/Calcutta','Y-m-d H:i:s');
			$templateActivityDate=date("d/m/Y",strtotime($yesterday));
			$tempDate=explode(" ",$activityDate);
			$date=explode("-", $tempDate[0]);
			$activityDate= strtotime($activityDate);
			$where.=" AND al.activity_date=$activityDate";
			$sendmail=$dbAdapter->query("Select pt.name as type,a.subject as subject,al.description as description,p.name as projectname,p.id as projectid,al.seconds_spent as totaltime
					FROM activity_log as al
					JOIN Projects p ON p.id=al.project_id
					JOIN projecttypes pt ON pt.id=p.type_id
					JOIN Activities a ON al.activity_id=a.id
					where $where",Adapter::QUERY_MODE_EXECUTE);
			$month=$date['1'];
			$year=$date['0'];
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');

			$aa = date($year."-".$month."-01");
			$strDate = strtotime($aa)+$offset;
			$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$bb = date($year."-".$month.'-'.$num);
			//					$bb = date($year."-".$month.'-t');
			$endDate = strtotime($bb)+$offset;

			$templateData=array();
			$projectMapper =array ();
			$count=count($senderUserMapper);
			$senderUserMapper[$count]['id']=$userRow->id;
			$senderUserMapper[$count]['name']=$userRow->fname." ".$userRow->lname;
			$senderUserMapper[$count]['mail']=$userRow->email;

				
			if($sendmail->count()==0){
				$totalspenttime=array();
				$total=array();
				$senderUserMapper[$count]['senderlist']=array($userRow->email);
				$senderUserMapper[$count]['content']='';
				array_push($reportNotFilledList,$userRow->email);
				$name=ucwords($userRow->fname." ".$userRow->lname);
				$finalContent="Dear ". $name." <br/> You have not Send Daily Status Report of $templateActivityDate, please Send daily Report immediately.";
				$subject="Daily Report Not Send From Intranet for $templateActivityDate";
				if (APPLICATION_ENV == "development")
				{
					$common->sendEmail("Testing Purpose $subject","$finalContent",$userRow->email,'',null,null,null);
				}else
				{
					$common->sendEmail("$subject","$finalContent",$userRow->email,'',null,null,null);
				}
				// 				}
			}

		}
	}

	public function automatereportAction(){
		$common =new Misc();
		$em=$this->getEntityManager();		

		$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
		$user=$dbAdapter->query("select * from user where isactive=1",Adapter::QUERY_MODE_EXECUTE);

		$senderUserMapper=array();
		$senderList=array();
		$absentList=array();
		$reportNotFilledList=array();
		set_time_limit(0);
		$templateActivityDate="";
		foreach ($user as $userRow) {
			$where="";
			$userid=$userRow->id;
			$where="al.user_id=$userid";
			$preferences=$dbAdapter->query("Select * from preferences p where p.user_id=$userid ",Adapter::QUERY_MODE_EXECUTE);
			$yesterday=gmdate("Y-m-d", time() - 60 * 60 * 24);
			$activityDate= $common->ConvertLocalTimezoneToGMT($yesterday,'Asia/Calcutta','Y-m-d H:i:s');
			$templateActivityDate=date("d/m/Y",strtotime($yesterday));
			$tempDate=explode(" ",$activityDate);
			$date=explode("-", $tempDate[0]);
			
// 			issue will arise starting of 1st day of month get wrong result of query who use start date nd enddate.(Show Wrong late count ,Leave,Avg Late etc.)  (1st date of month) to resolve it uncomment next line your issue get resolved (nitin) 
		//	$date=explode("-", $yesterday);			
		
			$activityDate= strtotime($activityDate);
			$where.=" AND al.activity_date=$activityDate";
			
			$count=count($senderUserMapper);
			$senderUserMapper[$count]['id']=$userRow->id;
			$senderUserMapper[$count]['name']=$userRow->fname." ".$userRow->lname;
			$senderUserMapper[$count]['mail']=$userRow->email;
			$senderUserMapper[$count]['content']='';
				
				
			$validator = new EmailAddress();
			$toarray = array();
			$ccarray=array();
			$meregedSenderList=array();
			if($preferences->count()>0){
				$preferencesRow = $preferences->current();
				$to1=$preferencesRow->tomail;
				$cc1=$preferencesRow->cc;
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
				}
				unset($validator);
				foreach ($toarray as $rw){
					if(!in_array($rw, $senderList)){
						array_push($senderList, $rw);
					}
				}
				foreach ($ccarray as $rw){
					if(!in_array($rw, $senderList)){
						array_push($senderList, $rw);
					}
				}
				if(!in_array($userRow->email, $senderList)){
					array_push($senderList, $userRow->email);
				}
				$meregedSenderList=array_merge($toarray,$ccarray);
			}
			else{
				$reader = new Ini();
				$data = $reader->fromFile(__DIR__."/../../../../../config/application.ini");
				$emailAddresses = Decoder::decode($data['automate_report_to']);
				$emailAddresses = $emailAddresses->data;
				$senderList = array();
				foreach ($emailAddresses as $mailAddr){
					array_push($meregedSenderList, $mailAddr->email);
					array_push($senderList, $mailAddr->email);
				}
			}
			
			$senderUserMapper[$count]['senderlist'] = $meregedSenderList;
			unset($emailAddresses);
			unset($toarray);
			unset($ccarray);
			unset($meregedSenderList);
			
			$checkForLogin=$dbAdapter->query("SELECT count(l.id) as lcount,u.id as uid,u.fname as fname,u.email as email,u.lname as lname FROM user u  LEFT JOIN  login l ON l.user_id=u.id and l.created_date=$activityDate Where u.needdailyreport=1 and u.id=".$userRow->id." and u.isactive=1 group by u.id having lcount=0",Adapter::QUERY_MODE_EXECUTE);
			if(isset($checkForLogin) && $checkForLogin->count()>0){
				//person was absent on that day,add it to the absent list
				array_push($absentList,$userRow->email);
				continue;
			}
				
			$sendmail=$dbAdapter->query("Select pt.name as type,a.subject as subject,al.description as description,p.name as projectname,p.id as projectid,al.seconds_spent as totaltime
					FROM activity_log as al
					JOIN Projects p ON p.id=al.project_id
					JOIN projecttypes pt ON pt.id=p.type_id
					JOIN Activities a ON al.activity_id=a.id
					where $where",Adapter::QUERY_MODE_EXECUTE);

			$month=$date['1'];
			$year=$date['0'];
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');

			$aa = date($year."-".$month."-01");
			$strDate = strtotime($aa)+$offset;

			$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$bb = date($year."-".$month.'-'.$num);
			//					$bb = date($year."-".$month.'-t');
			$endDate = strtotime($bb)+$offset;
			$templateData=array();
			$projectMapper =array ();
			
			if($sendmail->count()>0){
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
						$projectData=$dbAdapter->query("Select al.user_id as userid,p.estimated_hours as project_estime,sum(al.seconds_spent) as project_spent
								FROM activity_log as al
								JOIN Projects p ON p.id=al.project_id
								where $projectWhere group by al.user_id",Adapter::QUERY_MODE_EXECUTE);
						unset($projectWhere);
						$totalProjectSpent=0;
						$totalCurrentUserSpent=0;
						$totalCurrentUserEst=0;
						if($projectData->count()>0){
							foreach ($projectData as $rwp){
								$templateData[$recordIndex]['pestime']=$rwp->project_estime;
								$totalProjectSpent+=$rwp['project_spent'];
								if($rwp['userid']==$userid){
									$totalCurrentUserSpent=$rwp['project_spent'];
									$useridest=$rwp['userid'];
									$projectid=$rw['projectid'];
									$userestimate=$dbAdapter->query("Select sum(a.estimated_hours) as esttime from Activities a where  $useresttime and a.user_id=$userid and a.project_id=$projectid",Adapter::QUERY_MODE_EXECUTE);
									foreach ($userestimate as $r){
										$totalCurrentUserEst=$r->esttime;
									}
								}
							}
						}
						unset($useresttime);
						unset($projectData);

						$templateData[$recordIndex]['totalProjectSpent']=$totalProjectSpent;
						$templateData[$recordIndex]['totalCurrentUserSpent']=$totalCurrentUserSpent;
						$templateData[$recordIndex]['totalCurrentUserEst']=$totalCurrentUserEst;
					}

					$recordIndex=array_search($rw['projectid'], $projectMapper);
					if(!isset($templateData[$recordIndex]['activities'])){
						$templateData[$recordIndex]['activities']=array();
						$templateData[$recordIndex]['todayactivitylogtime']=0;
					}
					$sizeOfCurrentActivitiesByProject=count($templateData[$recordIndex]['activities']);
					$templateData[$recordIndex]['pname']=$rw['projectname'];
					$templateData[$recordIndex]['todayactivitylogtime']=$templateData[$recordIndex]['todayactivitylogtime']+$rw['totaltime'];
					$templateData[$recordIndex]['activities'][$sizeOfCurrentActivitiesByProject]['description']=$rw['description'];
					$templateData[$recordIndex]['activities'][$sizeOfCurrentActivitiesByProject]['totaltime']=$rw['totaltime'];
					array_push($totalspenttime,$rw['totaltime']);
				}
				unset($sendmail);
				$activitySpentTime=array_sum($totalspenttime);

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

				$todayLoginTime=$dbAdapter->query("SELECT min(l.logintime) as mindt,l.created_date as cdate FROM login l  WHERE l.user_id=$userid AND l.created_date=$activityDate",Adapter::QUERY_MODE_EXECUTE);
				$workingday=$dbAdapter->query("SELECT count(l.id) as lid FROM login l  WHERE l.user_id=$userid AND l.created_date BETWEEN $strDate AND $endDate group by l.created_date",Adapter::QUERY_MODE_EXECUTE);
				$lateLoggedInQuery = $dbAdapter->query("SELECT l.id as lid,min(l.logintime) as mindt,l.created_date as cdate FROM login l  WHERE l.user_id=$userid AND l.created_date BETWEEN $strDate AND $endDate group by l.created_date having mindt > l.created_date+33000",Adapter::QUERY_MODE_EXECUTE);
				$holiday=$dbAdapter->query("SELECT h.id FROM Holiday h  WHERE h.date BETWEEN $strDate AND $endDate",Adapter::QUERY_MODE_EXECUTE);
				$avglate=0;
				$latelogin=0;
				$lateLoggedIn=$lateLoggedInQuery->count();

				foreach ($lateLoggedInQuery as $rws){
					$avglate+=$rws['mindt']-(33000+$rws['cdate']);
					$latelogin++;
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
				unset($holiday);
				//$workingdaycount=$workingday[0]['lid'];
				$workingdaycount=count($workingday);
				unset($workingday);
				$valuesToSend=array('avglate'=>$avglate,'latelogin'=>$latelogin,'workingdaycount'=>$workingdaycount,'officeworkingday'=>$officeworkingday,'todayLoginTime'=>$todayLoginTime,'notLoggedInQuery'=>$lateLoggedInQuery,'response'=>$templateData,'activityDate'=>$activityDate,'user'=>$user,'activitySpentTime'=>$activitySpentTime);
				unset($lateLoggedInQuery);
				unset($todayLoginTime);
				unset($templateData);
				$viewModel=new ViewModel($valuesToSend);
				$phpRenderer=new PhpRenderer();
				$resolver = new Resolver\AggregateResolver();
				$phpRenderer->setResolver($resolver);
				$map = new Resolver\TemplateMapResolver(array(
						'templates/automatedailyreport' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'automatedailyreport.phtml',
				));
				$stack = new Resolver\TemplatePathStack(array(
						'script_paths' => array(
								__DIR__ . '/../../view',
						)
				));
				$resolver->attach($map)    // this will be consulted first
				->attach($stack);
				
				try{
					$viewModel->setTemplate("templates/automatedailyreport");
					$htmlOutput=$phpRenderer->render($viewModel);
					unset($viewModel);
					unset($resolver);
					unset($phpRenderer);					
					$senderUserMapper[$count]['content']=$htmlOutput;
					unset($htmlOutput);
				}
				catch (Exception $e){
					echo $e->getMessage();exit;
				}
			}
			else{				
				if($userRow->needdailyreport==1){
					array_push($reportNotFilledList,$userRow->email);
				}
			}
		}
		foreach ($senderList as $mail){
			$finalContent="";
			$reportNotFilledString="";
			$templateData=array();
			foreach ($senderUserMapper as $userRecord){
				$details=$userRecord['senderlist'];				
				if(in_array($userRecord['mail'],$reportNotFilledList)){
					if(in_array($mail,$details)){
						if($mail!=$userRecord['mail']){
							if($reportNotFilledString==""){
								$reportNotFilledString=$userRecord['name'];
							}
							else{
								$reportNotFilledString.=",".$userRecord['name'];
							}
						}
					}
				}
			}

			if($reportNotFilledString!=""){
				$finalContent.="Following users have not filled up their report<br/><b>".$reportNotFilledString."</b>"."<br/>";
			}

			foreach ($senderUserMapper as $userRecord){
				if(isset($userRecord['senderlist']) && $userRecord['content']!=""){
					$details=$userRecord['senderlist'];
					if(in_array($mail, $details)){
						$finalContent.="<b>".ucwords($userRecord['name'])."</b><br/>".$userRecord['content'];
					}
					else if($mail==$userRecord['mail']){
						$finalContent.="<b>".ucwords($userRecord['name'])."</b><br/>".$userRecord['content'];
					}
				}
			}
			if($finalContent!=""){
				if (APPLICATION_ENV == "development")
				{
					$common->sendEmail("Testing Purpose Status Report For $templateActivityDate",html_entity_decode($finalContent),$mail,'',null,null,null);
				}else{
					$common->sendEmail("Status Report For $templateActivityDate",html_entity_decode($finalContent),$mail,'',null,null,null);
				}
			}
		}
		exit;
	}

	public function addlogtimeAction()
	{
		
		$auth = new AuthenticationService();
		$common=new Misc();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$em = $this->getEntityManager();
		$query = $em->createQuery("SELECT u.id as id,u.fname as fname,u.lname as lname,u.email as email FROM Application\Entity\User u where u.isactive='1' Order by u.fname ASC");
		$loginRows = $query->getResult();
		$response=array();
		$i=0;
		foreach ($loginRows as $rws){
			$response[$i]['user_id']=$rws['id'];
			$response[$i]['user_name']=ucwords($rws['fname'] . " ".$rws['lname']);
			$i++;
		}
		$misc=new Misc();
		if($this->getRequest()->isPost()){
			$offset = $misc->get_timezone_offset('Asia/Calcutta','GMT');
			$userId=$this->getRequest()->getPost('user');
			$logAction=$this->getRequest()->getPost('logtime');
			$dateTime=$this->getRequest()->getPost('inouttime');
			$convertdate=$this->getRequest()->getPost('convertinout');
			
			$nofifyResponse=array();
			if ($userId==""){
				$nofifyResponse['data']['user']="null";
			}else {
				$nofifyResponse['data']['user']="valid";
			}
			if ($logAction==""){
				$nofifyResponse['data']['logtime']="null";
			}else {
				$nofifyResponse['data']['logtime']="valid";
			}
			$convertdate=$this->getRequest()->getPost('convertinout');
			if($convertdate=="")
			{
				$nofifyResponse['data']['inouttime']="null";
			}else {
				$nofifyResponse['data']['inouttime']="valid";
			}
			if(isset($nofifyResponse['data']) && (in_array("null", $nofifyResponse['data']) || in_array("invalid", $nofifyResponse['data']))){
				$nofifyResponse['returnvalue']="invalid";
			}
			else{
				$nofifyResponse=array();
			}
			
			if(count($nofifyResponse)==0){
				$gmtconvert = $misc->ConvertLocalTimezoneToGMT($convertdate,"Asia/Calcutta","Y-m-d H:i:s");
				$stampconvert = strtotime($gmtconvert);
				$cdate=explode(" ", $convertdate);
				$stampcdate = strtotime($cdate[0])+$offset;
				$user=$em->find("Application\Entity\User",$userId);
				if($logAction == "logintime")
				{
					$login = new Login;
					$login->setUser($user);
					$login->setLogintime($stampconvert);
					$login->setCreated_date($stampcdate);
					$login->setCreated_time(0);
					$login->setLoggedinby($auth->getIdentity()->id);
					try{
						$em->persist($login);
						$em->flush();
						$nofifyResponse['returnvalue']="valid";
						$common->sendEmail("Login Time Updated in Attendance", "Hi ".$user->__get('fname').",<br/>     Your Login time Update By ".$dateTime.".Verify this.", $user->__get('email'), $auth->getIdentity()->email,$auth->getIdentity()->email);
					}
					catch (Exception $e)
					{
						$nofifyResponse['returnvalue']="exception";
					}
				}elseif ($logAction == "logouttime" && count($nofifyResponse)==0){
					$em = $this->getEntityManager();
					$countQuery = $em->createQuery("SELECT l.id as lid,max(l.logintime) as maxlogintime,l.logouttime as outtime,l.user_id as userid FROM Application\Entity\Login l Where l.user_id=$userId AND l.created_date=$stampcdate");
					$totalRecords = $countQuery->getResult();
					$maxlogintime = $totalRecords[0]['maxlogintime'];
					if(count($totalRecords)>0 && $maxlogintime < $stampconvert){
						$logouttimerow = $totalRecords[0]['lid'];
						$log = $em->find('Application\Entity\Login',$logouttimerow);
						$log->setLogouttime($stampconvert);
						$log->setLoggedoutby($auth->getIdentity()->id);
						$em->persist($log);
						$em->flush();
						$nofifyResponse['returnvalue']="valid";
						$common->sendEmail("Logout Time Updated in Attendance", "Hi ".$user->__get('fname').", <br/>     Your Logout time Update for ".$dateTime.".Verify this.", $user->__get('email'), $auth->getIdentity()->email);
					}
					else{
						$nofifyResponse['returnvalue']="invalid";
						$nofifyResponse['data']['inouttime']="invalid";
					}

				}
			}
			echo json_encode($nofifyResponse);
			exit;
		}
		return new ViewModel(array('response' => $response,));

	}

	public function updatecreateddateAction(){
		$em = $this->getEntityManager();
		$loginRecords=$em->getRepository('Application\Entity\Login')->findAll();
		//print_r($date[0]->getCreated_date());exit;
		for($i=0;$i<count($loginRecords);$i++){
			$date=$loginRecords[$i]->getCreated_date()+$loginRecords[$i]->getCreated_time()-19800;
			$aa = date("Y-m-d H:i:s",$date);
			//$datetime = strtotime($aa);
			$bb = date("Y-m-d",$date);
			$cdate = strtotime($bb);
			$ctime = ($date - $cdate);
			$loginRecords[$i]->setCreated_date($cdate);
			$loginRecords[$i]->setCreated_time($ctime);
			$em->persist($loginRecords[$i]);
		}
		$em->flush();
	}
	public function preferencesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
	}
	public function gridpreferencesAction(){
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
		if($sidx=="" ||$sidx=="username"){
			$sidx="u.fname";
		}

		$em=$this->getEntityManager();
		$preferences=$em->getRepository("Application\Entity\Preferences")->findAll();
		$totalPages = 0;
		if (count($preferences)>0){
			$totalPages = ceil(count($preferences)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($preferences);
		$i=0;
		$preferences =$em->createQuery("Select p.id as id,p.tomail as tomail,p.cc as cc,p.leave_contact as leave_contact,u.fname as fname,u.lname as lname from Application\Entity\Preferences p JOIN Application\Entity\User u Where u.id=p.user_id")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$preferences = $preferences->getResult();

		foreach ($preferences as $rws){
			$action="<a href='/addpreferences/".$rws['id']."'><i class='icon-edit'></i></a>&nbsp;<a href='javascript:deletePreferences(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['fname']." ".$rws['lname']),htmlentities($rws['tomail']),htmlentities($rws['cc']),htmlentities($rws['leave_contact']),$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}

	
	public function addpreferencesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$id=$this->params('id');
		$userid=$this->params('userid');
		//	echo $userid."id".$id;exit;

		if($this->getRequest()->isPost()){
			$response=array();
			$preferencesUser=$this->getRequest()->getPost('user');
			$ccmail=$this->getRequest()->getPost('ccmail');
			$tomail=$this->getRequest()->getPost('tomail');
			$leavecontact=$this->getRequest()->getPost('leavecontact');

			/*if($tomail==""){
				$response['data']['tomail']="null";
			}
			if($ccmail==""){
			$response['data']['ccmail']="null";
			}*/

			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}
			if(count($response)==0 ){
				$preferences=new \stdClass();
				$isNewRecord=false;
				if(isset($id) && $id>0 ){
					if($auth->getIdentity()->isadmin==1){
						$preferences=$em->find('Application\Entity\Preferences', $id);
					}
					else{
						$preferences=$em->getRepository('Application\Entity\Preferences')->findOneBy(array('user_id'=>$auth->getIdentity()->id));
						$isNewRecord=true;
						$preferences->setUser_id($auth->getIdentity()->id);
					}
				}
				if(count($preferences)>0){

					$preferences->setTomail($tomail);
					$preferences->setCc($ccmail);
					if($auth->getIdentity()->isadmin==1){
						$preferences->setLeaveContact($leavecontact);
					}
					try{
						$em->persist($preferences);
						$em->flush();
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
						echo $e->getMessage();exit;
					}
				}else {
					$response['returnvalue']="invalid";
				}
				header("Content-type: application/json");
				echo json_encode($response);
				exit;
			}
		}
		else{
			$valuesToSend=array();
			if(isset($userid) && $userid>0){
				$oldPref=$em->getRepository('Application\Entity\Preferences')->findOneBy(array('user_id'=>$auth->getIdentity()->id));
				$valuesToSend['oldPref']=$oldPref;
			}
			else if(isset($id) && $id>0){
				$oldPref=$em->find('Application\Entity\Preferences',$id);
				$valuesToSend['oldPref']=$oldPref;
			}

			$viewModel=new ViewModel($valuesToSend);
			//$viewModel->setTerminal(true);
			return $viewModel;
		}

	}

	public function deletepreferencesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$response=array();
		$id=$this->getRequest()->getPost('id');
		$em=$this->getEntityManager();
		$delete =$em->find('Application\Entity\Preferences', $id);
		if ($delete) {
			$this->getEntityManager()->remove($delete);
			$this->getEntityManager()->flush();
			$response['returnvalue']="valid";
		}
		echo json_encode($response);
		exit;
	}

	public function editcontactAction(){
		$misc = new Misc();
		$common = new IntraCommon();
		$em = $this->getEntityManager();
		$auth = new AuthenticationService();

		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$id=$this->params('id');

		if (isset($id) && $id!=""){

			if($auth->getIdentity()->isadmin == 1){
				$id = $this->params('id');
			}
			else{
				$id = $auth->getIdentity()->id;
			}
		}
		else{
			if($auth->getIdentity()->isadmin != 1){
				return $this->redirect()->toRoute('home');
			}
		}
			
		if($this->getRequest()->isPost()){
			$response = array();
			$loginSlotId = $this->getRequest()->getPost('loginslot');
			$companyid = $this->getRequest()->getPost('companyid');
			if($companyid == ""){
				$response['data']['companyid'] = "null";
			}
			else{
				$response['data']['companyid'] = "valid";
			}

			$fname = $this->getRequest()->getPost('fname');
			if($fname == ""){
				$response['data']['fname'] = "null";
			}
			else{
				$response['data']['fname'] = "valid";
			}

			$lname = $this->getRequest()->getPost('lname');
			if($lname == ""){
				$response['data']['lname'] = "null";
			}
			else{
				$response['data']['lname'] = "valid";
			}
			$mobile = $this->getRequest()->getPost('mobile');
			$email = $this->getRequest()->getPost('email');
			if($email == ""){
				$response['data']['email'] = "null";
			}
			else{
				if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				{
					$response['data']['email'] = "emailinvalid";
				}
				else
				{
					$response['data']['email'] = "valid";
				}
			}

			$dobview = $this->getRequest()->getPost('dob');
			if($dobview == ""){
				$response['data']['dob'] = "null";
			}
			else{
				$response['data']['dob'] = "valid";
			}

			$isactive = $this->getRequest()->getPost('isactive');

			$isadmin = $this->getRequest()->getPost('isadmin');

			$joindate = $this->getRequest()->getPost('joiningdate');

			if($joindate == ""){
				$response['data']['joiningdate'] = "null";
			}
			else{
				$response['data']['joiningdate'] = "valid";
			}

			$altjoindate = $this->getRequest()->getPost('alternate_joining_date');
			$altjoindate = $common->ConvertLocalTimezoneToGMT($altjoindate,'Asia/Calcutta','Y-m-d H:i:s');
			$altjoindate = strtotime($altjoindate);
				
			$leavedate = $this->getRequest()->getPost('leavingdate');
			$altleavedate = $this->getRequest()->getPost('alternate_leaving_date');
			if(isset($altleavedate) && $altleavedate != ""){
				$altleavedate = $common->ConvertLocalTimezoneToGMT($altleavedate,'Asia/Calcutta','Y-m-d H:i:s');
				$altleavedate = strtotime($altleavedate);
			}else {
				$altleavedate = null;
			}
				
			$contactid = $this->getRequest()->getPost('contactid');
			$paddress = $this->getRequest()->getPost('paddress');
			if($paddress == ""){
				$response['data']['paddress'] = "null";
			}
			else{
				$response['data']['paddress'] = "valid";
			}
			$userid = $this->getRequest()->getPost('userid');

			$where = "";
			if ($userid > 0){
				$where = "u.id!=$userid";
			}

			if (isset($email) && $email != ""){
				if ($where == ""){
					$where = "u.email='$email'";
				}else {
					$where .= " AND u.email='$email'";
				}
			}else {
				$where = "1!=1";
			}
			$duplicateEmail = $em->CreateQuery("SELECT u.id from Application\Entity\User u where $where ")->getResult();
			if (count($duplicateEmail) > 0){
				$response['data']['email'] = "emailduplicate";
			}

			$raddress = $this->getRequest()->getPost('raddress');
			if($raddress == ""){
				$response['data']['raddress'] = "null";
			}
			else{
				$response['data']['raddress'] = "valid";
			}

			$needdailyreport = $this->getRequest()->getPost('needdailyreport');
			$altcontactnumber = $this->getRequest()->getPost('altcontactnumber');

			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("emailinvalid", $response['data']) || in_array("invalid", $response['data']) || in_array("emailduplicate", $response['data']))){
				$response['returnvalue'] = "invalid";
			}
			else{
				$response = array();
			}
			if(count($response) == 0){
				$altdob = $this->getRequest()->getPost('alternate_dob');
				$altdob = $common->ConvertLocalTimezoneToGMT($altdob,'Asia/Calcutta','Y-m-d H:i:s');
				$altdob = strtotime($altdob);

				$updatecontact = "";
				$updateUser = "";
				if($contactid != "" && $contactid > 0 ){
					$updatecontact = $em->find('Application\Entity\Contact', $contactid);
				}else{
					$updatecontact = new Contact();
				}
					
				if($userid != "" && $userid > 0){
					$updateUser = $em->find('Application\Entity\User', $userid);
				}else {
					$addPreferences = new Preferences();
					$updateUser = new User();
					$uniq = substr(uniqid(),0,7);
					$content = "Dear ".ucwords($fname).",<br/>"."your password is <b>".$uniq."</b>";
					$misc->sendEmail("IntraNet Password Notification", $content, $email,ucwords($fname));
					$updateUser->__set('password',md5($uniq));
					$addPreferences->setCc("");
					$addPreferences->setTomail("");
				}

				$updateUser->__set('fname',$fname);
				$updateUser->__set('lname',$lname);
				$updateUser->__set('company_id',$companyid);
				$updateUser->__set('email',$email);
				$updateUser->__set('dob',$altdob);
				$updateUser->__set('mobile',$mobile);
				$updateUser->__set('isactive',$isactive);
				$updateUser->__set('isadmin',$isadmin);
				$updateUser->__set('joiningdate',$altjoindate);
				$updateUser->__set('timing_slot_id',$loginSlotId);
				if(isset($altleavedate) && $altleavedate!=""){
					$updateUser->__set('leavingdate',$altleavedate);
				}
				else{
					$updateUser->__set('leavingdate',null);
				}
				$updateUser->__set('needdailyreport',$needdailyreport);
				$updatecontact->__set('paddress',$paddress);
				$updatecontact->__set('raddress',$raddress);
				$updatecontact->__set('altcontactnumber',$altcontactnumber);
				try{
					$em->persist($updateUser);
					$em->flush();
					$newUID = $updateUser->__get('id');
					$updatecontact->__set('user_id',$newUID);
					$em->persist($updatecontact);
					if($userid == 0){
						$addPreferences->setUser_id($newUID); //NOT DYNAMICALLY SETTING
						$em->persist($addPreferences);
						$em->flush();
					}
					$em->flush();

					$response['returnvalue'] = "valid";
				}
				catch (Exception $e)
				{
					$response['returnvalue'] = "exception";
				}
			}
			echo json_encode($response);
			exit;
		}
		else {
			$company = $em->createQuery("Select c.id as id,c.name as name from 
				Application\Entity\Company c")->getResult();
			$loginSlots = $em->createQuery("Select ts.slot_id as slot_id,ts.slot_login_time as
					slot_time from Application\Entity\TimingSlot ts")->getResult();
			$slots = array();
			foreach ($loginSlots as $slot){
				$slot['slot_time'] = $common->convertSpentTime($slot['slot_time']);
				$slots[$slot['slot_id']] = $slot['slot_time'];
			}
			if ($id != '' && $id > 0){
				$contact = $em->createQuery("Select cm.id as companyid,co.id as contactid,u.id as uid,
					u.needdailyreport as needdailyreport,u.fname as fname,u.lname as lname,u.email as 
					email,u.mobile as mobile,u.joiningdate as joindate,u.leavingdate as leavedate,
					u.dob as dob,u.isactive as isactive,u.isadmin as isadmin,u.timing_slot_id as 
					login_slot,cm.name as companyname,co.paddress as paddress,co.raddress as 
					raddress,co.altcontactnumber as altcontactnumber from Application\Entity\User as u
					LEFT JOIN  Application\Entity\Contact as co WITH co.user_id=u.id
					LEFT JOIN Application\Entity\Company as cm WITH cm.id=u.company_id where 
					u.id=$id")->getResult();
				$valuesToSend = array('contact' =>$contact,'company'=>$company,'slots'=>$slots);
			}else{
				$valuesToSend = array('company'=>$company,'slots'=>$slots);
			}

			$viewModel = new ViewModel($valuesToSend);
			return $viewModel;
		}
	}

	public function addcontactAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$user=$em->createQuery("Select u.id as id from Application\Entity\User u")->getResult();
		foreach ($user as $rws){
			$add=new Contact();
			$add->__set('paddress',"address");
			$add->__set('raddress',"address");
			$add->__set('altcontactnumber',"0");
			$add->__set('user_id',$rws['id']);
			try{
				$em->persist($add);
				$em->flush();
			}
			catch (Exception $e)
			{
				echo $e->getMessage();exit;
			}
		}
		exit;
	}

	public function myprofileAction(){
		$em = $this->getEntityManager();
		$auth = new AuthenticationService();
		$common = new Misc();
		$userid = $auth->getIdentity()->id;
		$company = $em->createQuery("Select c.id as id,c.name as name from 
			Application\Entity\Company c")->getResult();
			
		$contact = $em->createQuery("Select  cm.id as companyid,co.id as contactid,u.id as uid,
			u.fname as fname,u.lname as lname,u.email as email,u.mobile as mobile,u.joiningdate 
			as joindate,u.dob as dob,u.isactive as isactive,u.isadmin as isadmin,u.timing_slot_id 
			as login_slot,cm.name as companyname,co.paddress as paddress,co.raddress as raddress,
			co.altcontactnumber as altcontactnumber from Application\Entity\User as u 
			LEFT JOIN  Application\Entity\Contact as co WITH  co.user_id=u.id 
			LEFT JOIN Application\Entity\Company as cm WITH cm.id=u.company_id where u.id=$userid")
			->getResult();
		
		$slotTime = $em->createQuery("SELECT ts.slot_login_time as login_time FROM 
			Application\Entity\TimingSlot ts WHERE ts.slot_id = ".$contact[0]['login_slot'])
			->getResult();
		$slotTime = $common->convertSpentTime($slotTime[0]['login_time']);
		$contact[0]['login_slot'] = $slotTime;
		$valuesToSend = array('contact' =>$contact,'company'=>$company);
			
		$viewModel = new ViewModel($valuesToSend);
		return $viewModel;
	}

	public function updatedojAction() {
		$auth = new AuthenticationService();
		$admin = $auth->getIdentity()->isadmin;
		if($admin) {
			$em = $this->getEntityManager();
			$common=new IntraCommon();

			$dateOfJoining = $em->createQuery("Select u.id as id,u.joiningdate as doj
					from Application\Entity\User as u")->getResult();
			for($i=0;$i<sizeof($dateOfJoining);$i++) {
				if($dateOfJoining[$i]['doj'] != '' && $dateOfJoining[$i]['doj'] != NULL) {
					//$dateOfJoining[$i]['doj'] = strtotime($dateOfJoining[$i]['doj']);
					$updateRecordId = $em->find('Application\Entity\User', $dateOfJoining[$i]['id']);
					$dateOfJoining[$i]['doj'] = $common->ConvertLocalTimezoneToGMT($dateOfJoining[$i]['doj'],'GMT','Y-m-d');
					$updateRecordId->__set('joiningdate',$dateOfJoining[$i]['doj']);
					try{
						$em->persist($updateRecordId);
						$em->flush();
					}
					catch (Exception $e) {
						echo $e->getMessage();
						exit;
					}
				}
			}
		}
		else {
			//echo "Sorry! You dont have the required authorization to access this page.";
			return $this->redirect()->toRoute('home');
		}
		exit;
	}
	
	public function loginslotsAction() {
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function gridloginslotsAction(){
		$em = $this->getEntityManager();
		$common = new Misc();
		
		$page = $this->getRequest()->getPost('page');
		$limit = $this->getRequest()->getPost('rows');
		$sidx = $this->getRequest()->getPost('sidx');
		$sord = $this->getRequest()->getPost('sord');
		if($page > 0){
			$start = ($limit * $page) - $limit; // do not put $limit*($page - 1)
		}
		else{
			$start = 0;
			$page = 1;
		}
		if($sidx==""){
			$sidx="ts.slot_id";
		}
		else if($sidx=="slot_name"){
			$sidx="ts.slot_name";
		}
		else if($sidx=="slot_login_time"){
			$sidx="ts.slot_login_time";
		}
		$loginSlots = $em->createQuery("Select ts from Application\Entity\TimingSlot ts");
		$totalRecords = $loginSlots->getResult();
		if($limit == 0){
			$limit = 20;
		}
		$totalPages = 0;
		if(count($totalRecords) > 0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$i=0;
		foreach ($totalRecords as $rws){
			$slotTimeInSec = $rws->getSlotLoginTime();
			$slotTimeInSec = $common->convertSpentTime($slotTimeInSec);
			$response['rows'][$i]['id'] = $rws->getSlotId();
			$response['rows'][$i]['cell'] = array($rws->getSlotName(),$slotTimeInSec);
			$i++;
		}
		
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	
	public function addloginslotsAction(){
		$auth = new AuthenticationService();
		$common = new Misc();
		if($auth->getIdentity()->isadmin != 1){
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isPost()){
			$em = $this->getEntityManager();
			$response = array();
			$slotId = $this->getRequest()->getPost('id');
			$slotName = $this->getRequest()->getPost('slot_name');
			$slotLoginTime = $this->getRequest()->getPost('slot_login_time');
			if(!$common->validateSpentTime($slotLoginTime)){
				echo 'Invalid value for slot login time. Please enter correct time.';
				exit;
			}
			$slotLoginTime = explode(":",$slotLoginTime);
			$slotLoginHours = $slotLoginTime[0]*3600;
			$slotLoginSecs = $slotLoginHours+$slotLoginTime[1]*60;
			
			if($slotId != "" && $slotId > 0){
				$add = $em->find('Application\Entity\TimingSlot', $slotId);
				//$add->setSlotId($slotId);
			}
			else{
				$add = new timingslot();
			}
			$add->setSlotName($slotName);
			$add->setSlotLoginTime($slotLoginSecs);

			try{
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
	
	public function deleteloginslotsAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin != 1){
			$response['returnvalue'] = "invalid";
			$response['data']['user'] = "null";
			echo json_encode($response);
			exit;
		}
		$em = $this->getEntityManager();
		$response = array();
		$id = $this->getRequest()->getPost('id');
		if(isset($id) && $id > 0){
			$response = array();
			$delete = $em->find('Application\Entity\TimingSlot', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
				$response['returnvalue'] = "valid";
			}
			echo json_encode($response);
			exit;
		}
		else{
			$response['returnvalue'] = "invalid";
			$response['data']['slot_id'] = "null";
			echo json_encode($response);
			exit;
		}
	}
}