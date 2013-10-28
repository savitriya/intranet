<?php
namespace Application\Controller;

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
use Zend\Validator\EmailAddress;
use Application\Entity\User;
use Application\Repository\UserRepository;
use Application\Entity\Projects;
use Application\Entity\Activities;
use Application\Entity\Sentmail;
use Application\Entity\Milestones;
use Application\Entity\Login;
use Application\Entity\ActivityLog;
use Application\Entity\Activitycategories;
use Application\Repository\ProjectsRepository;
use Zend\Config\Reader\Ini;
use Zend\Json\Decoder;
use IntranetUtils\Common as Misc;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ReportController extends AbstractActionController
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
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$em=$this->getEntityManager();
		$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
		$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
		$category=$em->getRepository('Application\Entity\Activitycategories')->getActivitycategoriesByName('ASC');

		$valuesToSend=array('users'=>$users,'project'=>$project,'category'=>$category);
		$viewModel=new ViewModel($valuesToSend);
		return $viewModel;

	}
	public function generateDailyReportAtAttendanceReportAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		$common=new Common();
		$em=$this->getEntityManager();
		if($this->getRequest()->isXmlHttpRequest()){
			$userid = $this->getRequest()->getPost('userid');
			$activityDate = $this->getRequest()->getPost('activitydate');
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
		}
	}
	public function  reportbyprojectAction(){
		$common=new Common();
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}

		if($this->getRequest()->isPost()){
			$response=array();
			$user=$this->getRequest()->getPost('user');
			$project=$this->getRequest()->getPost('project');
			$fromdate=$this->getRequest()->getPost('altfromdate');

			$todate=$this->getRequest()->getPost('alttodate');
			// echo $fromdate."last".$todate;exit;
			$category=$this->getRequest()->getPost('category');
			$groupby=$this->getRequest()->getPost('groupby');
			if($fromdate==""){
				$response['data']['fromdate']="null";
			}else{
				$response['data']['fromdate']="valid";
			}
			if($todate==""){
				$response['data']['todate']="null";
			}else{
				$response['data']['todate']="valid";
			}
			// 			if($user==""){
			// 				$response['data']['user']="null";
			// 			}else{
			// 				$response['data']['user']="valid";
			// 				}
			if($groupby==""){
				$response['data']['groupby']="null";
			}else{
				$response['data']['groupby']="valid";
			}
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}

			if(count($response)==0){
				$fromdate=$common->ConvertLocalTimezoneToGMT($fromdate,"Asia/Calcutta","Y-m-d H:i:s");
				$todate=$common->ConvertLocalTimezoneToGMT($todate,"Asia/Calcutta","Y-m-d H:i:s");
				//echo $user.count($response);exit;
				$fromdate=strtotime($fromdate);
				$todate=strtotime($todate);
				//				echo $fromdate."last".$todate;exit();
				$where='';
				if(isset($user) && $user!=""){
					$where="al.user_id=$user";
				}

				if (isset($project) && $project!=""){
					if ($where==""){
						$where="al.project_id=$project";
					}else {
						$where.="AND al.project_id=$project";
					}
				}
				if (isset($category) && $category!=""){
					if ($where==""){
						$where="al.category_id=$category";
					}else {
						$where.="AND al.category_id=$category";
					}
				}
				if (isset($fromdate) && $fromdate!="" && isset($todate) && $todate!=""){
					if ($where==""){
						$where="al.activity_date BETWEEN $fromdate AND $todate";
					}else {
						$where.="AND al.activity_date BETWEEN $fromdate AND $todate";
					}
				}
				if ($where==""){
					$where="1=1";
				}

				$em=$this->getEntityManager();
				if ($groupby==1){
					$projectreport=$em->createQuery("Select p.name as pname,sum(al.seconds_spent) as spenttime
							FROM Application\Entity\ActivityLog as al
							JOIN Application\Entity\Projects p WITH p.id=al.project_id
							JOIN Application\Entity\User u WITH u.id=al.user_id where $where group by p.id")->getResult();
					//print_r($projectreport);
					$valuesToSend=array('projectreport'=>$projectreport);
				}
				elseif ($groupby==2){
					$report=$em->createQuery("Select p.id as pid,u.id as uid,al.id as alid,p.name as projectname,u.fname as fname,u.lname as lname,al.seconds_spent as spenttime,al.description as description
							FROM Application\Entity\ActivityLog as al
							JOIN Application\Entity\Projects p WITH p.id=al.project_id
							JOIN Application\Entity\User u WITH u.id=al.user_id where $where ")->getResult();
					//      print_r($report);exit();
					$reportarray = array();
					$projectMapper =array ();
					if(count($report)>0){
						foreach ($report as $rw){
							if(!in_array($rw['pid'],$projectMapper)){
								array_push($projectMapper, $rw['pid']);
								$projectRecordIndex=array_search($rw['pid'],$projectMapper);
								$reportarray[$projectRecordIndex]['totaltime']=0;
								$reportarray[$projectRecordIndex]['pname']=$rw['projectname'];
							}
							$projectRecordIndex=array_search($rw['pid'],$projectMapper);

							if(!isset($reportarray[$projectRecordIndex]['user'])){
								$reportarray[$projectRecordIndex]['user']=array();
							}
							$data=(array)$reportarray[$projectRecordIndex]['user'];
							$userrRecordIndex=-1;
							for($i=0;$i<count($data);$i++){
								if($data[$i]['id']==$rw['uid']){
									$userrRecordIndex=$i;
									break;
								}
							}
							if($userrRecordIndex==-1){
								$userrRecordIndex=count($data);
								$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['time']=0;
								$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['name']=$rw['fname']." ".$rw['lname'];
								$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['id']=$rw['uid'];
							}
							$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['time']+=$rw['spenttime'];
							$reportarray[$projectRecordIndex]['totaltime']+=$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['time'];
						}
					}
					//print_r($reportarray);exit();
					$valuesToSend=array('responce'=>$reportarray);
				}
				elseif ($groupby==3){
					$projectUserDate=$em->createQuery("Select al.activity_date as activitydate,u.id as uid,p.id as pid,u.fname as fname,u.lname as lname,p.name as pname,sum(al.seconds_spent) as spenttime
							FROM Application\Entity\ActivityLog as al
							JOIN Application\Entity\Projects p WITH p.id=al.project_id
							JOIN Application\Entity\User u WITH u.id=al.user_id where $where   group by p.id,al.activity_date,u.id")->getResult();

					//print_r($projectUserDate);exit;

					$dateReport=array();
					$projectMapper=array();

					if (count($projectUserDate)>0){
						foreach ($projectUserDate as $rw){
							if (!in_array($rw['pid'], $projectMapper)){
								array_push($projectMapper,$rw['pid']);
								$projectRecordIndex=array_search($rw['pid'],$projectMapper);
								$dateReport[$projectRecordIndex]['totaltime']=0;
								$dateReport[$projectRecordIndex]['pname']=$rw['pname'];
							}
							$projectRecordIndex=array_search($rw['pid'],$projectMapper);

							if(!isset($dateReport[$projectRecordIndex]['date'])){
								$dateReport[$projectRecordIndex]['date']=array();
							}
							$date=$dateReport[$projectRecordIndex]['date'];
							$dateRecordIndex=-1;
							for($j=0;$j<count($date);$j++){
								if($date[$j]['date']==$rw['activitydate']){
									$dateRecordIndex=$j;
									break;
								}
							}

							if($dateRecordIndex==-1){
								$dateRecordIndex=count($date);
								$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['date']=$rw['activitydate'];
							}

							if(!isset($dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'])){
								$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user']=array();
							}
							$date1=$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'];
							$dateuserRecordIndex=-1;
							for($j=0;$j<count($date1);$j++){
								if($date1[$j]['id']==$rw['uid']){
									$dateuserRecordIndex=$j;
									break;
								}
							}

							if($dateuserRecordIndex==-1){
								$dateuserRecordIndex=count($date1);
								$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['id']=$rw['uid'];

								$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['name']=$rw['fname']." ".$rw['lname'];
								$dateReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['time']=$rw['spenttime'];
							}
							$dateReport[$projectRecordIndex]['totaltime']+=$rw['spenttime'];

						}
					}

					//	print_r($dateReport);exit;
					$valuesToSend=array('dateReport'=>$dateReport);
				}
				elseif ($groupby==4){
					$projectUserDate=$em->createQuery("Select al.activity_date as activitydate,u.id as uid,p.id as pid,u.fname as fname,u.lname as lname,p.name as pname,al.description as description,al.seconds_spent as spenttime
							FROM Application\Entity\ActivityLog as al
							JOIN Application\Entity\Projects p WITH p.id=al.project_id
							JOIN Application\Entity\User u WITH u.id=al.user_id where $where ")->getResult();

					//print_r($projectUserDate);exit;

					$dateDescriptionReport=array();
					$projectMapper=array();

					if (count($projectUserDate)>0){
						foreach ($projectUserDate as $rw){
							if (!in_array($rw['pid'], $projectMapper)){
								array_push($projectMapper,$rw['pid']);
								$projectRecordIndex=array_search($rw['pid'],$projectMapper);
								$dateDescriptionReport[$projectRecordIndex]['totaltime']=0;
								$dateDescriptionReport[$projectRecordIndex]['pname']=$rw['pname'];
							}
							$projectRecordIndex=array_search($rw['pid'],$projectMapper);

							if(!isset($dateDescriptionReport[$projectRecordIndex]['date'])){
								$dateDescriptionReport[$projectRecordIndex]['date']=array();
							}
							$date=$dateDescriptionReport[$projectRecordIndex]['date'];
							$dateRecordIndex=-1;
							for($j=0;$j<count($date);$j++){
								if($date[$j]['date']==$rw['activitydate']){
									$dateRecordIndex=$j;
									break;
								}
							}

							if($dateRecordIndex==-1){
								$dateRecordIndex=count($date);
								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['date']=$rw['activitydate'];
								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['time']=0;
							}
							$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['time']+=$rw['spenttime'];
							if(!isset($dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'])){
								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user']=array();
							}
							$date1=$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'];
							$dateuserRecordIndex=-1;
							for($j=0;$j<count($date1);$j++){
								if($date1[$j]['id']==$rw['uid']){
									$dateuserRecordIndex=$j;
									break;
								}
							}							//
							if($dateuserRecordIndex==-1){
								$dateuserRecordIndex=count($date1);
								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['id']=$rw['uid'];

								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['name']=$rw['fname']." ".$rw['lname'];

								$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['time']=0;
								if(!isset($dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['description'])){
									$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['description']=array();
								}
							}
							$spenttime="(".$common->convertSpentTime($rw['spenttime']).")";
							array_push($dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['description'],$rw['description'].$spenttime);
							$dateDescriptionReport[$projectRecordIndex]['date'][$dateRecordIndex]['user'][$dateuserRecordIndex]['time']+=$rw['spenttime'];
							$dateDescriptionReport[$projectRecordIndex]['totaltime']+=$rw['spenttime'];
						}
					}

					//	print_r($dateDescriptionReport);exit;
					$valuesToSend=array('dateDescriptionReport'=>$dateDescriptionReport);
				}


					

				$viewModel=new ViewModel($valuesToSend);
				$phpRenderer=new PhpRenderer();
				$resolver = new Resolver\AggregateResolver();
				$phpRenderer->setResolver($resolver);
				$map = new Resolver\TemplateMapResolver(array(
						'templates/report' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'report.phtml',
				));
				$stack = new Resolver\TemplatePathStack(array(
						'script_paths' => array(
								__DIR__ . '/../../view',
						)
				));
				$resolver->attach($map)    // this will be consulted first
				->attach($stack);
				try{
					$viewModel->setTemplate("templates/report");
					//$delmailTemplate->setTemplate("templates/newlyassignedactivities");
					$htmlOutput=$phpRenderer->render($viewModel);
					//		echo $htmlOutput;exit;
					$response['html']=$htmlOutput;
				}
				catch (Exception $e){
					echo $e->getMessage();exit;
				}

			}
		}
		echo json_encode($response);exit;
	}

	public function summaryreportAction(){
		$auth = new AuthenticationService();
		$common=new Common();

		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}

		$response=array();
		if($this->getRequest()->isPost()){
			$user=$this->getRequest()->getPost('user');
			$project=$this->getRequest()->getPost('project');
			$fromdate=$this->getRequest()->getPost('altfromdate');
			$todate=$this->getRequest()->getPost('alttodate');
			$category=$this->getRequest()->getPost('category');
			$groupby=$this->getRequest()->getPost('groupby');
			if($fromdate==""){
				$response['data']['fromdate']="null";
			}else{
				$response['data']['fromdate']="valid";
			}
			if($todate==""){
				$response['data']['todate']="null";
			}else{
				$response['data']['todate']="valid";
			}
			// 			if($user==""){
			// 				$response['data']['user']="null";
			// 			}else{
			// 				$response['data']['user']="valid";
			// 				}
			if($groupby==""){
				$response['data']['groupby']="null";
			}else{
				$response['data']['groupby']="valid";
			}
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}
			if(count($response)==0){

				$fromdate=$common->ConvertLocalTimezoneToGMT($fromdate,"Asia/Calcutta","Y-m-d H:i:s");
				$todate=$common->ConvertLocalTimezoneToGMT($todate,"Asia/Calcutta","Y-m-d H:i:s");
				$fromdate=strtotime($fromdate);
				$todate=strtotime($todate);

				$where='';
				if(isset($user) && $user!=""){
					$where="al.user_id=$user";
				}
					
				if (isset($project) && $project!=""){
					if ($where==""){
						$where="al.project_id=$project";
					} else {
						$where.="AND al.project_id=$project";
					}
				}
				if (isset($category) && $category!=""){
					if ($where==""){
						$where="al.category_id=$category";
					}else {
						$where.="AND al.category_id=$category";
					}
				}
				if (isset($fromdate) && $fromdate!="" && isset($todate) && $todate!=""){
					if ($where==""){
						$where="al.activity_date BETWEEN $fromdate AND $todate";
					}else {
						$where.="AND al.activity_date BETWEEN $fromdate AND $todate";
					}
				}
				if ($where==""){
					$where="1=1";
				}
					
				$em=$this->getEntityManager();
					
				$report=$em->createQuery("Select p.id as pid,u.id as uid,al.id as alid,p.name as projectname,u.fname as fname,u.lname as lname,al.seconds_spent as spenttime,al.description as description
						FROM Application\Entity\ActivityLog as al
						JOIN Application\Entity\Projects p WITH p.id=al.project_id
						JOIN Application\Entity\User u WITH u.id=al.user_id where $where ")->getResult();

				$reportarray = array();
				$projectMapper =array ();
				if(count($report)>0){
					foreach ($report as $rw){
						if(!in_array($rw['pid'],$projectMapper)){
							array_push($projectMapper, $rw['pid']);
							$projectRecordIndex=array_search($rw['pid'],$projectMapper);
							$reportarray[$projectRecordIndex]['totaltime']=0;
							$reportarray[$projectRecordIndex]['pname']=$rw['projectname'];
						}
						$projectRecordIndex=array_search($rw['pid'],$projectMapper);
						$reportarray[$projectRecordIndex]['totaltime']+=$rw['spenttime'];
						if(!isset($reportarray[$projectRecordIndex]['user'])){
							$reportarray[$projectRecordIndex]['user']=array();
						}
						$data=(array)$reportarray[$projectRecordIndex]['user'];
						$userrRecordIndex=-1;
						for($i=0;$i<count($data);$i++){
							if($data[$i]['id']==$rw['uid']){
								$userrRecordIndex=$i;
								break;
							}
						}
						if($userrRecordIndex==-1){
							$userrRecordIndex=count($data);
							$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['time']=0;
							$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['name']=ucwords($rw['fname'])." ".ucwords($rw['lname']);
							$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['id']=$rw['uid'];
						}

						if(!isset($reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['description'])){
							$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['description']=array();
						}
						$data1=(array)$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['description'];
						$activityRecordIndex=-1;
						/*for($i=0;$i<count($data1);$i++){
							if($data[$i]['id']==$rw['uid']){
						$activityRecordIndex=$i;
						break;
						}
						}*/
						$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['time']+=$rw['spenttime'];
						//if($activityRecordIndex==-1){
						$activityRecordIndex=count($data1);
						$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['description'][$activityRecordIndex]['activity']=$rw['description'];
						$reportarray[$projectRecordIndex]['user'][$userrRecordIndex]['description'][$activityRecordIndex]['spent']=$rw['spenttime'];
						//}

					}
				}
				$valuesToSend=array('responce'=>$reportarray);
				$viewModel=new ViewModel($valuesToSend);
				$phpRenderer=new PhpRenderer();
				$resolver = new Resolver\AggregateResolver();
				$phpRenderer->setResolver($resolver);
				$map = new Resolver\TemplateMapResolver(array(
						'templates/summaryreport' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'summaryreport.phtml',
				));
				$stack = new Resolver\TemplatePathStack(array(
						'script_paths' => array(
								__DIR__ . '/../../view',
						)
				));
				$resolver->attach($map)    // this will be consulted first
				->attach($stack);
				try{
					$viewModel->setTemplate("templates/summaryreport");
					//$delmailTemplate->setTemplate("templates/newlyassignedactivities");
					$htmlOutput=$phpRenderer->render($viewModel);
					$response['html']=$htmlOutput;
				}
				catch (Exception $e){
					echo $e->getMessage();exit;
				}
			}

		}
		echo json_encode($response);exit;
	}


	public function weeklyreportAction(){

		$auth = new AuthenticationService();
		$common=new Common();

		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}

		$response=array();
		if($this->getRequest()->isPost()){
			$user=$this->getRequest()->getPost('user');
			$project=$this->getRequest()->getPost('project');
			$fromdate=$this->getRequest()->getPost('altfromdate');
			$todate=$this->getRequest()->getPost('alttodate');
			$milestone=$this->getRequest()->getPost('milestone');
			$userTime=$this->getRequest()->getPost('user_time');
			if($fromdate==""){
				$response['data']['fromdate']="null";
			}else{
				$response['data']['fromdate']="valid";
			}
			if($todate==""){
				$response['data']['todate']="null";
			}else{
				$response['data']['todate']="valid";
			}
			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
				$response['returnvalue']="invalid";
			}
			else{
				$response=array();
			}
			if(count($response)==0){

				$fromdate=$common->ConvertLocalTimezoneToGMT($fromdate,"Asia/Calcutta","Y-m-d H:i:s");
				$todate=$common->ConvertLocalTimezoneToGMT($todate,"Asia/Calcutta","Y-m-d H:i:s");
				$fromdate=strtotime($fromdate);
				$todate=strtotime($todate);

				$where='';
				if(isset($user) && $user!=""){
					$where="al.user_id=$user";
				}
					
				if (isset($project) && $project!=""){
					if ($where==""){
						$where="al.project_id=$project";
					} else {
						$where.="AND al.project_id=$project";
					}
				}
				if (isset($fromdate) && $fromdate!="" && isset($todate) && $todate!=""){
					if ($where==""){
						$where="al.activity_date BETWEEN $fromdate AND $todate";
					}else {
						$where.="AND al.activity_date BETWEEN $fromdate AND $todate";
					}
				}

				if ($where==""){
					$where="1=1";
				}
					
				$em=$this->getEntityManager();
					
				$report=$em->createQuery("Select p.id as pid,m.name as mname,al.milestone_id as mid,u.id as uid,al.id as alid,p.name as projectname,u.fname as fname,u.lname as lname,al.seconds_spent as spenttime,al.description as description
						FROM Application\Entity\ActivityLog as al
						JOIN Application\Entity\Projects p WITH p.id=al.project_id
						JOIN Application\Entity\User u WITH u.id=al.user_id
						Left JOIN Application\Entity\Milestones m WITH m.id=al.milestone_id where $where  group By al.project_id,al.milestone_id,al.id")->getResult();


				$reportarray = array();
				$projectMapper =array ();
				if(count($report)>0){
					foreach ($report as $rw){
						if(!in_array($rw['pid'],$projectMapper)){
							array_push($projectMapper, $rw['pid']);
							$projectRecordIndex=array_search($rw['pid'],$projectMapper);
							$reportarray[$projectRecordIndex]['totaltime']=0;
							$reportarray[$projectRecordIndex]['pname']=$rw['projectname'];
						}
						$projectRecordIndex=array_search($rw['pid'],$projectMapper);
						$reportarray[$projectRecordIndex]['totaltime']+=$rw['spenttime'];
						//mileston array
						if(!isset($reportarray[$projectRecordIndex]['milestone'])){
							$reportarray[$projectRecordIndex]['milestone']=array();
						}
						$data=(array)$reportarray[$projectRecordIndex]['milestone'];
						$milestoneRecordIndex=-1;
						for($i=0;$i<count($data);$i++){
							if($data[$i]['id']==$rw['mid']){
								$milestoneRecordIndex=$i;
								break;
							}
						}
						if($milestoneRecordIndex==-1){
							$milestoneRecordIndex=count($data);
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['time']=0;
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['mname']=$rw['mname'];
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['id']=$rw['mid'];
						}
						//user array
						if(!isset($reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'])){
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user']=array();
						}
						$userdata=(array)$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'];
						$userRecordIndex=-1;
						for($i=0;$i<count($userdata);$i++){
							if($userdata[$i]['id']==$rw['uid']){
								$userRecordIndex=$i;
								break;
							}
						}

						if ($userRecordIndex==-1){
							$userRecordIndex=count($userdata);
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['time']=0;
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['id']=$rw['uid'];
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['username']=ucwords($rw['fname'])." ".ucwords($rw['lname']);

						}



						if(!isset($reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['description'])){
							$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['description']=array();
						}
						$data1=(array)$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['description'];
						$activityRecordIndex=-1;
						/*for($i=0;$i<count($data1);$i++){
						 if($data[$i]['id']==$rw['uid']){
						$activityRecordIndex=$i;
						break;
						}
						}*/
						$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['time']+=$rw['spenttime'];
						$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['time']+=$rw['spenttime'];
						//if($activityRecordIndex==-1){
						$activityRecordIndex=count($data1);
						$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['description'][$activityRecordIndex]['activity']=$rw['description'];
						$reportarray[$projectRecordIndex]['milestone'][$milestoneRecordIndex]['user'][$userRecordIndex]['description'][$activityRecordIndex]['spent']=$rw['spenttime'];
							
						//}

					}
				}
				$valuesToSend=array('responce'=>$reportarray,"milestone"=>$milestone,"userTime"=>$userTime);
				$viewModel=new ViewModel($valuesToSend);
				$phpRenderer=new PhpRenderer();
				$resolver = new Resolver\AggregateResolver();
				$phpRenderer->setResolver($resolver);
				$map = new Resolver\TemplateMapResolver(array(
						'templates/weeklyreport' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'weeklyreport.phtml',
				));
				$stack = new Resolver\TemplatePathStack(array(
						'script_paths' => array(
								__DIR__ . '/../../view',
						)
				));
				$resolver->attach($map)    // this will be consulted first
				->attach($stack);
				try{
					$viewModel->setTemplate("templates/weeklyreport");
					//$delmailTemplate->setTemplate("templates/newlyassignedactivities");
					$htmlOutput=$phpRenderer->render($viewModel);
					$response['html']=$htmlOutput;
				}
				catch (Exception $e){
					echo $e->getMessage();exit;
				}
			}
			echo json_encode($response);exit;
		}
		else{
			$em=$this->getEntityManager();
			$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
			$project=$em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
			$valuesToSend=array('users'=>$users,'project'=>$project);
			$viewModel=new ViewModel($valuesToSend);
			return $viewModel;
			exit;
		}


	}

	public function userreportAction(){
		$common=new Common();
		$em=$this->getEntityManager();
		$auth = new AuthenticationService();

		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}

		$response=array();
		if($this->getRequest()->isPost()){
			$user=$this->getRequest()->getPost('userid');
			$strDate=$this->getRequest()->getPost('strdate');
			$month=date("m");	$year=date("Y");
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
			$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
			if (!isset($strDate)){
				$strDate = date($year."-".$month."-01"." 00:00:00");
			}
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
			$strDate=strtotime($strDate)+$offset;
			$endDate=$this->getRequest()->getPost('enddate');
			if (!isset($endDate)){
				$endDate = date($year."-".$month.'-'.$num." 00:00:00");
			}
			$endDate=strtotime($endDate)+$offset;
			$activitytotaltime = $em->createQuery("SELECT p.id as pid,p.estimated_hours as pesttime,p.name as pname,sum(al.seconds_spent) as userspent FROM Application\Entity\ActivityLog al
					JOIN Application\Entity\Projects p WITH al.project_id=p.id  WHERE al.user_id=$user and al.activity_date BETWEEN $strDate AND $endDate  group by al.project_id")->getResult();
			$totalSpentTime='';
			$totalEstimateTime='';
			$pName=array();$pEstTime=array();$userSpent=array();$userEst=array();
			foreach ($activitytotaltime as $rws){
				$totalSpentTime+=$rws['userspent'];
				array_push($pName, ucwords($rws['pname']));
				$pid=$rws['pid'];
				$estimatactivity=$em->createQuery("SELECT a.subject as sub,a.project_id as projectid,a.estimated_hours as esttime from Application\Entity\Activities a
						JOIN Application\Entity\ActivityLog al WITH a.id=al.activity_id and al.activity_date BETWEEN $strDate AND $endDate
						WHERE a.user_id=$user and a.project_id=$pid group by a.id")->getResult();
				$activityesttime= 0;
				foreach ($estimatactivity as $rw){
					$activityesttime+=$rw['esttime'];
				}

				$pEst=$common->convertSpentTime($rws['pesttime']);
				$pEst=explode(":", $pEst);
				$tmp=$pEst[0].".".(int)($pEst[1]*10/6);
				$pEstTime1=number_format($tmp,2);
				array_push($pEstTime, $pEstTime1);
					
				$userSpentTime=$common->convertSpentTime($rws['userspent']);
				$userSpentTime=explode(":",$userSpentTime);
				$tmp=$userSpentTime[0].".".(int)($userSpentTime[1]*10/6);
				$userSpent1=number_format($tmp,2);
				array_push($userSpent, $userSpent1);
					
				$totalEstimateTime+=$activityesttime;
				$userEsttime=$common->convertSpentTime($activityesttime);
				$userEsttime=explode(":",$userEsttime);
				$tmp=$userEsttime[0].".".(int)($userEsttime[1]*10/6);
				$userEsttime1=number_format($tmp,2);
				array_push($userEst, $userEsttime1);
			}
			$totalSpentTime=$common->convertSpentTime($totalSpentTime);
			$totalEstimateTime=$common->convertSpentTime($totalEstimateTime);

			$legend=array('User Spent Time' ,'User Estimate Time');
			$response['pname']=$pName;
			$response['projectest']=$pEstTime;
			$response['userspenttime']=$userSpent;
			$response['userest']=$userEst;
			$response['legend']=$legend;
			$response['title']="Total Estimated  $totalEstimateTime Spent $totalSpentTime";
			echo json_encode($response);exit;

		}else {
			$user=$em->getRepository('Application\Entity\User')->getUserByName("ASC");
			$viewModel=new ViewModel(array('user'=>$user));
			return $viewModel;
		}

	}

	public function piechartAction(){
		$common=new Common();
		$em=$this->getEntityManager();
		$auth = new AuthenticationService();

		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}


		$response=array();
		if($this->getRequest()->isPost()){
			$user=$this->getRequest()->getPost('userid');
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
			$strDate=$this->getRequest()->getPost('strdate');
			$month=date("m");	$year=date("Y");
			$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
			$num= cal_days_in_month(CAL_GREGORIAN,$month,$year);
			if (!isset($strDate)){
				$strDate = date($year."-".$month."-01"." 00:00:00");
			}

			$strDate=strtotime($strDate)+$offset;
			$endDate=$this->getRequest()->getPost('enddate');
			if (!isset($endDate)){
				$endDate = date($year."-".$month.'-'.$num." 00:00:00");
			}
			$endDate=strtotime($endDate)+$offset;
			$activitytotaltime = $em->createQuery("SELECT ac.name as categoryname,sum(al.seconds_spent) as userspent FROM Application\Entity\ActivityLog al
					JOIN Application\Entity\Activitycategories ac WITH ac.id=al.category_id
					WHERE al.user_id=$user and al.activity_date BETWEEN $strDate AND $endDate  group by al.user_id,al.category_id")->getResult();

			$i=0;
			$totalspenttime='';
			foreach ($activitytotaltime as $rws){
				$totalspenttime+=$rws['userspent'];
				$response['data'][$i]['categoryname']=$rws['categoryname'];
				$userSpentTime=$common->convertSpentTime($rws['userspent']);
				$userSpentTime=explode(":",$userSpentTime);
				$tmp=$userSpentTime[0].".".(int)($userSpentTime[1]*10/6);
				$userSpent1=number_format($tmp,2);
				$response['data'][$i]['userspenttime']=$userSpent1;
				$response['data'][$i]['categoryname']=$rws['categoryname']."(".$userSpent1.")";
				$i++;
			}
			$totalspenttime=$common->convertSpentTime($totalspenttime);
			$totalspenttime=explode(":",$totalspenttime);
			$tmp=$totalspenttime[0].".".(int)($totalspenttime[1]*10/6);
			$totalspenttime1=number_format($tmp,2);
			$response['totalspent']=$totalspenttime1;
			echo json_encode($response);exit;

		}else {
			$user=$em->getRepository('Application\Entity\User')->getUserByName("ASC");
			$viewModel=new ViewModel(array('user'=>$user));
			return $viewModel;
		}

	}

	public function dailyreportstatusAction(){
		$date=$this->params('date');
		if ($date=="" && !isset($date)){
			$date=date("Y-m-d H:i:s");
		}else {
			$date=$date." 00:00:00";
		}

		$common=new Common();
		$em = $this->getEntityManager();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$currentDateInLocal=$common->ConvertGMTToLocalTimezone($date,"Asia/Calcutta","d/m/Y H:i:s");
		// 		$currentDayName=date("D",strtotime($currentDateInLocal));
		$currentDayName=date("D",strtotime($date));
		echo "\n-------------------------------------------------\n";
		echo "Date:".$currentDateInLocal."\n\n";
		if($currentDayName=="Sun"){
			echo "Its a Sunday Today,So Skip Daily Report Status";
			echo "\n-------------------------------------------------\n";
			exit;
		}


		$date=explode(" ", $date);
		// 	    $startdate=strtotime($date[0]." 00:00:00")+$offset;
		$startdate=$common->ConvertLocalTimezoneToGMT($date[0]." 00:00:00","Asia/Calcutta","Y-m-d H:i:s");
		$startdate=strtotime($startdate);

		$enddate=strtotime($date[0]." 24:00:00")+$offset;
		$enddate=$common->ConvertLocalTimezoneToGMT($date[0]." 24:00:00","Asia/Calcutta","Y-m-d H:i:s");
		$enddate=strtotime($enddate);
		// 		echo $startdate."endDate ".$enddate;
		$todayLoginuser=$em->createQuery("SELECT l.user_id as uid from Application\Entity\Login as l  where l.created_date = $startdate  group by l.created_date,l.user_id")->getResult();
		$loginuser="";
		foreach ($todayLoginuser as $rws){
			if ($loginuser=="" && $loginuser==null){
				$loginuser=$rws['uid'];
			}else {
				$loginuser.=",".$rws['uid'];
			}
		}
		if ($loginuser=="" && $loginuser==null){
			exit;
		}
		$dailyReportNotSend = $em->createQuery("SELECT count(s.id) as scount,u.id as uid,u.fname as fname,u.email as email,u.lname as lname FROM Application\Entity\User u  LEFT JOIN  Application\Entity\Sentmail s WITH s.user_id=u.id and s.type_id=2   and s.created_date_time BETWEEN $startdate AND $enddate where u.isactive=1 and u.needdailyreport=1 and u.id IN ($loginuser)  group by u.id having scount=0");
		// 		$dailyReportNotSend = $dailyReportNotSend->getSql();
		$dailyReportNotSend = $dailyReportNotSend->getResult();
		// 		echo "fndknfckd";exit;
		$date=explode("-", $date[0]);
		$date="$date[2]/$date[1]/$date[0]";

		$userMapper=array();
		$mailData=array();
		$j=0;
		if(count($dailyReportNotSend)>0){
			foreach ($dailyReportNotSend as $rw){
				array_push($userMapper, $rw['uid']);
				$mailData[$j]['user_id']=$rw['uid'];
				$name=$rw['fname'];
				if($rw['lname']!=""){
					$name.=" ".$rw['lname'];
				}
				$mailData[$j]['user_name']=$name;
				$mailData[$j]['email']=$rw['email'];
				$mailData[$j]['isNotMailReport']=1;
				$j++;
			}
		}


		$misc=new Common();
		$notSendDailyReport="";
		foreach ($mailData as $rw){
			$content="";
			if($rw['isNotMailReport']==1){
				if($notSendDailyReport==""){
					$notSendDailyReport=ucwords($rw['user_name']);
				}
				else{
					$notSendDailyReport.=",".ucwords($rw['user_name']);
				}
				$content="Dear ".ucwords($rw['user_name']).",<br/>You have not Send Daily Status Report of $date"."please Send daily Report immediately.";
			}
			$to=array();
			$to[0]['email']=$rw['email'];
			$to[0]['name']=ucwords($rw['user_name']);
			if($content!=""){

				if (APPLICATION_ENV == "development") {
					// 					$misc->sendEmail("Testing Ignore It Daily Report Not Send From Intranet for $date",$content,$to,"Intranet");
				}
				else{
					// 					$misc->sendEmail("Daily Report Not Send From Intranet for $date",$content,$to,"Intranet");
				}
			}

		}

		$content="";
		if($notSendDailyReport){
			if($content==""){
				$content=$notSendDailyReport." have not Send daily report of $date";
			}
			else{
				$content.="<br/>".$notSendDailyReport." have not Send daily report of $date";
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
					// 		            $misc->sendEmail("Testing Ignore It Daily Report Not Send From Intranet for $date", $content, $to,"Intranet");
				}
				else{
					// 				    $misc->sendEmail(" Daily Report Not Send From Intranet for $date", $content, $to,"Intranet");
				}
			}
		}
		echo $content;
		echo "\n-------------------------------------------------\n";
		exit;


	}

	public function reportstatusAction(){

		$em = $this->getEntityManager();
		$auth = new AuthenticationService();
		$companyIndex=0;
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('login');
		}
		$month = $this->getRequest()->getPost('month');

		$year = $this->getRequest()->getPost('year');

		if($month == ""){
			$month = date("m");
		}
		if($year == ""){
			$year = date("Y");
		}
		if($month == date('m') && $year == date('Y')){
			$numc = date('d');
		}else{
			$numOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$numc = $numOfDaysInMonth;
		}
		$common = new Common();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$aa = date($year."-".$month."-01");
		$strDate = strtotime($aa)+$offset;
		$num = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$bb = date($year."-".$month.'-'.$num);

		$endDate = strtotime($bb)+$offset;
		$cidLoginUser = $auth->getIdentity()->company_id;

		if($auth->getIdentity()->isadmin == 1){
			$companyId = $this->getRequest()->getPost('company');
			if(isset($companyId) && $companyId != ""){
				$cidLoginUser = $companyId;
			}
			$viewCompanies = $em->createQuery("Select c.id as cid,c.name as name from Application\Entity\User u JOIN Application\Entity\Company c WITH u.company_id=c.id group by u.company_id")->getResult();
		}

		$byUser = $this->getRequest()->getPost('byuser');
		$currentDay6Pm = "$year-$month-".date('d')." 18:00:00";
		$currentDay6PmInStrtoTime = strtotime($currentDay6Pm)+$offset;
		$currentDay = "$year-$month-".date('d')." 00:00:00";
		$currentTime = strtotime($currentDay)+$offset;
		if(isset($byUser) && $byUser == 1){
			$userArrayById = array();
			$sDate = $strDate;
			$eDate = $endDate;
			$allUsers = $em->createQuery("SELECT u.id, u.fname, u.lname FROM Application\Entity\User u where u.isactive = 1 AND u.needdailyreport = 1");
			$allUsers = $allUsers->getResult();
			if(isset($allUsers) && sizeof($allUsers) > 0) {
				foreach ($allUsers as $user){
					$userArrayById[$user['id']]['name'] = ucfirst($user['fname']).' '.ucfirst($user['lname']);
					$userArrayById[$user['id']]['dates'] = ' ';
				}

				while($sDate<$eDate+86400){
					date("D",($sDate-$offset)) == "Sun" ? $isSunday = 1 : $isSunday = 0;
					if(!$isSunday){
						$dayEnd = $sDate+86399;//23:59:59
						$tempDates = array();
						for($u=0;$u<sizeof($allUsers);$u++){
							$isPresent = $em->createQuery("SELECT count(log.id) as login_count FROM Application\Entity\Login log WHERE log.user_id=".$allUsers[$u]['id']." AND log.created_date =".$sDate)->getResult();
							if(isset($isPresent[0]['login_count']) && $isPresent[0]['login_count'] > 0){
								$anyActivity = $em->createQuery("SELECT count(actlog.id) as activity_count FROM Application\Entity\ActivityLog actlog WHERE actlog.user_id=".$allUsers[$u]['id']." AND actlog.activity_date =".$sDate)->getResult();
								if($anyActivity[0]['activity_count'] == 0){
									if($sDate==$currentTime && $currentTime<$currentDay6PmInStrtoTime){
										continue;
									}
									$userArrayById[$allUsers[$u]['id']]['dates'] .= date('d', ($sDate-$offset)).', &nbsp;';
								}
							}
						}
					}
					$sDate += 86400;
				}
			}
			return new ViewModel(array('userArrayById'=>$userArrayById,'month'=>$month,'year'=>$year,'reportType'=>$byUser));
		}
		elseif(isset($byUser) && $byUser == 2){
			$userArrayById = array();
			$sDate = $strDate;
			$eDate = $endDate;
			$allUsers = $em->createQuery("SELECT u.id, u.fname, u.lname FROM Application\Entity\User u where u.isactive = 1 AND u.needdailyreport = 1");
			$allUsers = $allUsers->getResult();
			if(isset($allUsers) && sizeof($allUsers) > 0) {
				foreach ($allUsers as $user){
					$userArrayById[$user['id']]['name'] = ucfirst($user['fname']).' '.ucfirst($user['lname']);
					$userArrayById[$user['id']]['dates'] = ' ';
				}

				while($sDate<$eDate+86400){
					date("D",($sDate-$offset)) == "Sun" ? $isSunday = 1 : $isSunday = 0;
					if(!$isSunday){
						$dayEnd = $sDate+86399;//23:59:59
						$tempDates = array();
						for($u=0;$u<sizeof($allUsers);$u++){
							$isPresent = $em->createQuery("SELECT count(log.id) as login_count FROM Application\Entity\Login log WHERE log.user_id=".$allUsers[$u]['id']." AND log.created_date =".$sDate)->getResult();
							if(isset($isPresent[0]['login_count']) && $isPresent[0]['login_count'] > 0){
								$anyActivity = $em->createQuery("SELECT count(actlog.id) as activity_count,sum(actlog.seconds_spent) as total_seconds_spent FROM Application\Entity\ActivityLog actlog WHERE actlog.user_id=".$allUsers[$u]['id']." AND actlog.activity_date =".$sDate)->getResult();
								if($anyActivity[0]['activity_count'] > 0 && $anyActivity[0]['total_seconds_spent'] < 28800){
									if($sDate==$currentTime && $currentTime<$currentDay6PmInStrtoTime){
										continue;
									}
									$userArrayById[$allUsers[$u]['id']]['dates'] .= date('d', ($sDate-$offset)).', &nbsp;';
								}
							}
						}
					}
					$sDate += 86400;
				}
			}
			return new ViewModel(array('userArrayById'=>$userArrayById,'month'=>$month,'year'=>$year,'reportType'=>$byUser));
		}
		else{
			$responsebydate=array();
			$responsebyuser=array();

			for($i=1;$i<=$numc;$i++){
				$date="$year-$month-$i 00:00:00";
				$currentDayName=date("D",strtotime($date));
				if($currentDayName=="Sun"){
					continue;
				}
				$date=explode(" ", $date);
				// 	    $startdate=strtotime($date[0]." 00:00:00")+$offset;
				$startdate=$common->ConvertLocalTimezoneToGMT($date[0]." 00:00:00","Asia/Calcutta","Y-m-d H:i:s");
				$startdate=strtotime($startdate);
				// 		echo "startDate="+$startdate;exit;
				$enddate=strtotime($date[0]." 24:00:00")+$offset;
				$enddate=$common->ConvertLocalTimezoneToGMT($date[0]." 24:00:00","Asia/Calcutta","Y-m-d H:i:s");
				$enddate=strtotime($enddate);

				$todayLoginuser=$em->createQuery("SELECT l.user_id as uid from Application\Entity\Login as l  where l.created_date = $startdate  group by l.created_date,l.user_id")->getResult();
				//echo '<br/><br/>';print_r($todayLoginuser);
				$loginuser="";
				foreach ($todayLoginuser as $rws){
					if ($loginuser=="" && $loginuser==null){
						$loginuser=$rws['uid'];
					}else {
						$loginuser.=",".$rws['uid'];
					}
				}
				if ($loginuser=="" && $loginuser ==null){
					continue;
				}
				$dailyReportNotSend = $em->createQuery("SELECT count(al.id) as alcount,u.id as uid,u.fname as fname,u.email as email,u.lname as lname FROM Application\Entity\User u  LEFT JOIN  Application\Entity\ActivityLog al WITH al.user_id=u.id and al.activity_date = $startdate  where u.isactive=1 and u.needdailyreport=1 and u.id IN ($loginuser) group by u.id having alcount=0");
				// 		$dailyReportNotSend = $dailyReportNotSend->getSql();
				$dailyReportNotSend = $dailyReportNotSend->getResult();
				//echo '<br/>';print_r($dailyReportNotSend);
				$userMapper=array();
				$mailData=array();

				$j=0;
				if(count($dailyReportNotSend)>0){
					foreach ($dailyReportNotSend as $rw){
						array_push($userMapper, $rw['uid']);
						$mailData[$j]['user_id']=$rw['uid'];
						$name=$rw['fname'];
						if($rw['lname']!=""){
							$name.=" ".$rw['lname'];
						}
						if(!in_array( $rw['uid'],$responsebyuser)){
							array_push($responsebyuser,  $rw['uid']);
							$recordIndex=array_search($rw['uid'],$responsebyuser);
						}
						$mailData[$j]['user_name']=$name;
						$mailData[$j]['email']=$rw['email'];
						$mailData[$j]['isNotMailReport']=1;
						$j++;
					}
				}
				$notSendDailyReport="";
				foreach ($mailData as $rw){
					$content="";
					if($rw['isNotMailReport']==1){
						if($notSendDailyReport==""){
							$notSendDailyReport=ucwords($rw['user_name']);
						}
						else{
							$notSendDailyReport.=",".ucwords($rw['user_name']);
						}
					}
				}
				if($i==$numc && $currentTime<$currentDay6PmInStrtoTime){
					continue;
				}
				$responsebydate[$i]['date'] = "$i/$month/$year";
				$responsebydate[$i]['reportNotSendBy'] = $notSendDailyReport;
			}
			$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
			return new ViewModel(array('responsebydate'=>$responsebydate,'users'=>$users,'month'=>$month,'year'=>$year,"viewCompanies"=>$viewCompanies,'reportType'=>0));
		}
	}

	public function projectreportAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity() || $auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$em = $this->getEntityManager();
		$common = new Misc();
		$projectStatusId=0;
		$projectStatusId = $this->getRequest()->getPost('projectstatus');
		$where = "1=1";
		if($projectStatusId != 0){
			$where = "p.status_id=".$projectStatusId;
		}

		$projectsQuery = $em->createQuery("SELECT p.id as id,p.name as name,p.estimated_hours
				as estimatedhours,sum(a.seconds_spent) as totaltime, ps.name as projectstatus FROM
				Application\Entity\Projects p JOIN Application\Entity\ActivityLog a WITH
				p.id=a.project_id JOIN Application\Entity\Projectstatuses ps WITH p.status_id=ps.id
				WHERE $where GROUP BY a.project_id ORDER BY p.name");
		
		$totalrows = $projectsQuery->getResult();
		
		for($r=0;$r<sizeof($totalrows);$r++){
			if($totalrows[$r]['estimatedhours'] < $totalrows[$r]['totaltime']){
				$totalrows[$r]['color'] = 'red';
			}
			else{
				$totalrows[$r]['color'] = 'none';
			}
			$totalrows[$r]['estimatedhours'] = $common->convertSpentTime($totalrows[$r]
				['estimatedhours']);
			$totalrows[$r]['totaltime'] = $common->convertSpentTime($totalrows[$r]['totaltime']);
		}
		
		$pStatusQuery = $em->createQuery("SELECT ps.id as status_id, ps.name as status_name from 
			Application\Entity\ProjectStatuses ps");
		$pStatusQuery = $pStatusQuery->getResult();
		return new ViewModel(array('projects'=>$totalrows,'project_status'=>$pStatusQuery,
			'current_status'=>$projectStatusId));
	}
}