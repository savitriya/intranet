<?php
namespace Application\Controller;
use Zend\Filter\File\LowerCase;
use Doctrine\ORM\EntityRepository;
use Zend\Db\Sql\Where;
use Application\Entity\Projectstatuses;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Zend\Barcode\Object\Upca;
use IntranetUtils\Common;
use	Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Decoder;
use Zend\Authentication\AuthenticationService;
use Application\Entity\Milestones;
use Application\Entity\Projects;
use Application\Entity\Projecttypes;
use Application\Entity\Company;

use Application\Entity\MilestonesAssignee;
use Zend\Validator\EmailAddress;
use Application\Entity\ActivityLog;
use Application\Entity\Activities;
use Application\Entity\ResourceAllocation;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ProjectsController extends AbstractActionController
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
		$em = $this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$status=$em->createQuery('Select ps from Application\Entity\Projectstatuses ps Order by ps.name ASC')->getArrayResult();
		$ptype=$em->createQuery('Select pt from Application\Entity\Projecttypes pt Order by pt.name ASC')->getArrayResult();
		return new ViewModel(array('status' =>$status,'ptype'=>$ptype));
		
	}

	public function gridprojectsAction(){
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
			$sidx="p.estimated_startdate";
		}
		else if($sidx=="name"){
			$sidx="p.name";
		}		
		else if($sidx=="esthours"){
			$sidx="p.estimated_hours";
		}
		else if($sidx=="esd"){
			$sidx="p.estimated_startdate";
		}		
		else if($sidx=="eed"){
			$sidx="p.estimated_enddate";
		}
		else if($sidx=="asd"){
			$sidx="p.actual_startdate";
		}
		else if($sidx=="aed"){
			$sidx="p.actual_enddate";
		}
		else if($sidx=="company"){
			$sidx="p.company_id";
		}

		$em = $this->getEntityManager();
		$projectCountQuery = $em->createQuery('SELECT p.id as id FROM Application\Entity\Projects p JOIN Application\Entity\Projectstatuses ps WITH p.status_id=ps.id JOIN Application\Entity\Projecttypes pt WITH p.type_id=pt.id');
		$totalRecords = $projectCountQuery->getResult();
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$projectsQuery = $em->createQuery("SELECT p.id as id,p.name as name,p.estimated_hours as estimatedhours,
				      p.estimated_startdate as esd,p.estimated_enddate as eed,p.actual_startdate as asd,p.actual_enddate as aed,
				      ps.id as statusid,ps.name as statusname,ps.color as statuscolor,pt.id as typeid,pt.name as typename,
				      pt.color as typecolor,c.name as companyName 
				      FROM Application\Entity\Projects p LEFT JOIN Application\Entity\Projectstatuses ps WITH p.status_id=ps.id 
				      JOIN Application\Entity\Projecttypes pt WITH p.type_id=pt.id
				      LEFT JOIN Application\Entity\Company c WITH p.company_id=c.id order by $sidx $sord")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$totalrows = $projectsQuery->getResult();		
		$projectIds="";
		foreach ($totalrows as $rws){
			if($projectIds==""){
				$projectIds=$rws['id'];
			}
			else{
				$projectIds.=",".$rws['id'];
			}
		}
		$totalActivityTime=array();
		if($projectIds!=""){
			$projectTotalTime = $em->createQuery("SELECT a.project_id as projectid,sum(a.seconds_spent) as totaltime FROM Application\Entity\ActivityLog a WHERE a.project_id in ($projectIds) group by a.project_id");
			$totalProjectTime = $projectTotalTime->getResult();
		}
		$i=0;
		$common=new Common();
		foreach ($totalrows as $rws){
			$common =new Common();
			$projectspenttime='';
			foreach ($totalProjectTime as $rrr){
				if($rws['id'] == $rrr['projectid']){
					$projectspenttime=$common->convertSpentTime($rrr['totaltime']);
					break;
				}
			}
			$esd='';
			$eed='';
			$asd='';
			$aed='';
			$esthours=$common->convertSpentTime($rws['estimatedhours']);

			if(isset($rws['esd']) && $rws['esd']>0){
				$esd =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['esd']),'Asia/Calcutta','d/m/Y');
			}
			if(isset($rws['eed']) && $rws['eed']>0){
				$eed =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['eed']),'Asia/Calcutta','d/m/Y');
			}
			if(isset($rws['asd']) && $rws['asd']>0){
				$asd =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['asd']),'Asia/Calcutta','d/m/Y H:i:s');
			}
			if(isset($rws['aed']) && $rws['aed']>0){
				$aed =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['aed']),'Asia/Calcutta','d/m/Y H:i:s');
			}
			$action="<a href='/viewprojectsdetail/".$rws['id']."'><i class='icon-file'></i></a>&nbsp;&nbsp&nbsp;<a href='javascript:addProject(".$rws['id'].')'."'><i class='icon-edit'></i></a>&nbsp;&nbsp&nbsp;<a href='javascript:deleteProjects(".$rws['id'].')'."'><i class='icon-trash'></i></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['companyName']),ucwords($rws['name']),ucwords($rws['statusname']),ucwords($rws['typename']),$esd,$eed,$asd,$aed,$esthours,$projectspenttime,$action);

			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}

	public function addprojectsAction(){
		$common=new Common();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
			$em=$this->getEntityManager();
			$flag=$this->getRequest()->getPost('flag');
			$id=$this->getRequest()->getPost('id');
			if($this->getRequest()->isPost()){
			if($flag!="view"){
				$response=array();
				$name=$this->getRequest()->getPost('name');
				$companyId=$this->getRequest()->getPost('company');
				$name=strtolower($name);
				$typeid=$this->getRequest()->getPost('type');
				$esd=$this->getRequest()->getPost('altesd');
				$eed=$this->getRequest()->getPost('alteed');
				$asd=$this->getRequest()->getPost('altasd');
				$aed=$this->getRequest()->getPost('altaed');
				$esthours=$this->getRequest()->getPost('esthours');
				$statusid=$this->getRequest()->getPost('status');
				$mid=$this->getRequest()->getPost('milestone');
				
				$bDeveloper=$this->getRequest()->getPost('bussinessDeveloper');
				$manager=$this->getRequest()->getPost('escalationManager');
				$pCoordinator=$this->getRequest()->getPost('projectCoordinator');
				
				if($id==""){
					$duplicateproject=$em->createQuery("SELECT p.id as id from Application\Entity\Projects p Where p.name='$name' AND p.company_id='$companyId' ")->getResult();
					
				}else {
					$duplicateproject=array();
				}
				$spentTime=0;
				$spentTimeInArray=explode(":",$esthours);
				if($esthours=="" ){
					$response['data']['esthours']="null";
				}
				else if($esthours!=""){
					$response['data']['esthours']="valid";
					if (!floatval($esthours))
					{
						//$response['data']['spent']="invalid";
					}
				}
				
				if($companyId==""){
					$response['data']['company']="null";
				}else {
					$response['data']['company']="valid";
				}
				
				if($bDeveloper==""){
					$response['data']['bussinessDeveloper']="null";
				}else {
					$response['data']['bussinessDeveloper']="valid";
				}
				if($manager==""){
					$response['data']['escalationManager']="null";
				}else {
					$response['data']['escalationManager']="valid";
				}
				if($pCoordinator==""){
					$response['data']['projectCoordinator']="null";
				}else {
					$response['data']['projectCoordinator']="valid";
				}
				if($name==""){
					$response['data']['name']="null";
				} else {
					$response['data']['name']="valid";
				}
				if(!isset($typeid) || $typeid==""){
					$response['data']['type']="null";
				}else {
					$response['data']['type']="valid";
				}
				if($esd==""){
					$response['data']['esd']="null";
				}else{
					$response['data']['esd']="valid";
				}

				if($statusid==""){
					$response['data']['status']="null";
				}else {
					$response['data']['status']="valid";
				}
				if($eed != "" && strtotime($esd) > strtotime($eed)) {
					$response['data']['eed']="invalid";
				}else {
					$response['data']['eed']="valid";
				}
				if($aed != "" && strtotime($asd) > strtotime($aed)) {
					$response['data']['aed']="invalid";
				}
				if (count($duplicateproject)>0){
					$response['data']['projectname']="duplicate";
				}
				if (count($spentTimeInArray)!=2 && $esthours!=""){
					$response['data']['esthours']="invalid";
				}
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']) || in_array("duplicate", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				
				if(count($response)==0 ){
					$esd=strtotime($esd)+$offset;
					$newRecord=new \stdClass();
					$isNewRecord=false;
					if($id!="" && $id>0){
						$newRecord=$em->find('Application\Entity\Projects', $id);
					}
					else{
						$newRecord=new Projects();
						$isNewRecord=true;
					}
					$spentTime=$spentTimeInArray[0]*3600;
					$spentTime=$spentTime+$spentTimeInArray[1]*60;
					if ($spentTime>0){
						$newRecord->setEstimated_hours($spentTime);
					}
					$newRecord->setEntityManager($em);
					$newRecord->setCompany_id($companyId);
					$newRecord->setName($name);
					$newRecord->setEstimated_startdate($esd);
					$newRecord->setStatus_id($statusid);
					$newRecord->setType_id($typeid);
					$newRecord->setBd($bDeveloper);
					$newRecord->setManager($manager);
					$newRecord->setCoordinator($pCoordinator);
					
					if($eed!=""){
						$eed=strtotime($eed)+$offset;
						$newRecord->setEstimated_enddate($eed);
					}
					if($asd!=''){
						$asd=strtotime($asd)+$offset;
						$newRecord->setActual_startdate($asd);
					}
					if($aed!=''){
						$aed=strtotime($aed)+$offset;
						$newRecord->setActual_enddate($aed);
					}
					try{
						$em->persist($newRecord);
						$em->flush();
						$projectId=$newRecord->getId();
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
						echo $e->getMessage();exit;
					}
					
					try{
						$em->persist($newRecord);
						$em->flush();
						$projectId=$newRecord->getId();
							
						if($isNewRecord){
							$addMilestone=new Milestones();

							$addMilestone->setProject_id($projectId);
							if ($spentTime>0){
								$addMilestone->setEstimated_hours(0);
							}
							$addMilestone->setName("default");
							$addMilestone->setEstimated_startdate($esd);
							if($eed!=""){
								$addMilestone->setEstimated_enddate($eed);
							}
							if($asd!=''){
								$addMilestone->setActual_startdate($asd);
							}
							if($aed!=''){
								$addMilestone->setActual_enddate($aed);
							}
							$addMilestone->setStatus_id($statusid);
							$addMilestone->setProject_id($projectId);
							$addMilestone->setIsdefault("1");
							$em->persist($addMilestone);
							$em->flush();
						}
						$response['returnvalue']="valid";
					}
					catch (Exception $e)
					{
						$response['returnvalue']="exception";
						echo $e->getMessage();exit;
					}
				}
				echo json_encode($response);
				exit;
			}
			else{
			
				$comanys = $em->createQuery("SELECT c.id as id,c.name as name FROM Application\Entity\Company c order by c.name ASC")->getArrayResult();
				$status=$em->createQuery('Select ps from Application\Entity\Projectstatuses ps Order by ps.name ASC')->getArrayResult();
				$defaultStatus=$em->createQuery("Select ps.id as status_id from Application\Entity\Projectstatuses ps WHERE ps.name='Open'")->getResult();
				$ptype=$em->createQuery('Select pt from Application\Entity\Projecttypes pt Order by pt.name ASC')->getArrayResult();
				$users=$em->getRepository('Application\Entity\User')->getUserByName('ASC');
				$valuesToSend=array('comanys'=>$comanys,'status' =>$status,'ptype'=>$ptype,'users'=>$users,'defaultStatus'=>$defaultStatus);
				if(isset($id) && $id>0){
					$project=$em->find('Application\Entity\Projects',$id);
					$valuesToSend['project']=$project;
			
				}
				$viewModel=new ViewModel($valuesToSend);
			    $viewModel->setTerminal(true);
				return $viewModel;
			}
			}
	
	}
	
	public function deleteprojectsAction(){
		if($this->getRequest()->isPost()){			
			$id=$this->getRequest()->getPost('id');			
			$em=$this->getEntityManager();
		
			$milestone=$em->getRepository('Application\Entity\Milestones')->findBy(array('project_id' => $id));
			if ($milestone) {
				foreach($milestone as $delmilestone){
				$this->getEntityManager()->remove($delmilestone);
				$this->getEntityManager()->flush();
				}
			}
			$activity=$em->getRepository('Application\Entity\Activities')->findBy(array('project_id' => $id));
			if ($activity) {
				foreach($activity as $activity){
					$activitylog=$em->getRepository('Application\Entity\ActivityLog')->findBy(array('project_id' => $id));

					if ($activitylog) {
						foreach($activitylog as $activitylog){
						
								
							$this->getEntityManager()->remove($activitylog);
							$this->getEntityManager()->flush();
						}
					}
					
					$this->getEntityManager()->remove($activity);
					$this->getEntityManager()->flush();
				}
			}			
			$delete =$em->find('Application\Entity\Projects', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}
	
	public function getProjectByCompanyAction(){
		$em=$this->getEntityManager();
		$companyId=$this->getRequest()->getPost('companyid');
		$projects =$em->CreateQuery("Select p.id as id,p.name as name FROM Application\Entity\Projects p JOIN Application\Entity\Projectstatuses ps  WITH p.status_id =ps.id WHERE p.company_id='$companyId' AND ps.name LIKE '%Open%' ORDER BY p.name ASC")->getArrayResult();
		$response=array();
		if(count($projects)>0){
			for($i=0;$i<count($projects);$i++){
				$response['data'][$i]['id']=$projects[$i]['id'];
				$response['data'][$i]['name']=$projects[$i]['name'];
			}
			$response['returnvalue']="valid";
		}
		else{
			$response['returnvalue']="invalid";
		}
		echo json_encode($response);
		exit;		
	}


	public function milestonesAction(){
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()){			
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		
	}

	public function gridmilestonesAction(){
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
			$sidx="m.name";
		}
		else if($sidx=="name"){
			$sidx="m.name";
		}
		else if($sidx=="esd"){
			$sidx="m.estimated_startdate";
		}
		else if($sidx=="eed"){
			$sidx="m.estimated_enddate";
		}
		else if($sidx=="asd"){
			$sidx="m.actual_startdate";
		}
		else if($sidx=="aed"){
			$sidx="m.actual_enddate";
		}
		else if($sidx=="status"){
			$sidx="ps.name";
		}
		else if($sidx=="projects"){
			$sidx="p.name";
		}
		else if($sidx=="esthours"){
			$sidx="m.estimated_hours";
		}
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery('SELECT m.id as id,m.project_id as pid,m.estimated_startdate as esd,m.estimated_enddate as eed,m.actual_startdate as asd,m.actual_enddate as aed,m.status_id as status FROM Application\Entity\Milestones m ');
		$totalRecords = $countQuery->getResult();

		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);
		$em = $this->getEntityManager();
		$countQuery = $em->createQuery("SELECT m.isdefault as isdefault,m.id as id,ps.name as statusname,p.name as projectname,m.name as name,m.estimated_hours as estimatedhours,m.project_id as pid,m.estimated_startdate as esd,m.estimated_enddate as eed,m.actual_startdate as asd,m.actual_enddate as aed,m.status_id as status FROM Application\Entity\Milestones as m JOIN Application\Entity\Projectstatuses as ps WITH m.status_id=ps.id JOIN Application\Entity\Projects as p WITH p.id=m.project_id order by $sidx $sord")
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$totalrows = $countQuery->getResult();
		$i=0;
		$common=new Common();
		foreach ($totalrows as $rws){
			$esd='';
			$eed='';
			$asd='';
			$aed='';
			$common =new Common();
			$esthours=$common->convertSpentTime($rws['estimatedhours']);

			if(isset($rws['esd']) && $rws['esd']>0){
				$esd =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['esd']),'Asia/Calcutta','d/m/Y');
			}
			if(isset($rws['eed']) && $rws['eed']>0){
				$eed =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['eed']),'Asia/Calcutta','d/m/Y');
			}
			if(isset($rws['asd']) && $rws['asd']>0){
				$asd =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['asd']),'Asia/Calcutta','d/m/Y H:i:s');
			}
			if(isset($rws['aed']) && $rws['aed']>0){
				$aed =$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$rws['aed']),'Asia/Calcutta','d/m/Y H:i:s');
			}			
			$action="<a href='/addmilestones/".$rws['id']."'><i class='icon-edit'></i></a>&nbsp;<a href='javascript:deletemilestone(".$rws['id'].')'."'><i class='icon-trash'></i>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($rws['projectname'],$rws['name'],$esd,$eed,$asd,$aed,$rws['statusname'],$esthours,$rws['isdefault'],$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	
	public function addmilestonesAction(){
		$auth = new AuthenticationService();
		$common = new Common();
		$offset = $common->get_timezone_offset('Asia/Calcutta','GMT');
		$em = $this->getEntityManager();
		if($this->getRequest()->isPost()){
			$flag = $this->getRequest()->getPost('flag');
			$id = $this->getRequest()->getPost('id');
			//if($flag != "view"){
				$response = array();
				$name = $this->getRequest()->getPost('name');
				$name = strtolower($name);
				$projectId = $this->getRequest()->getPost('projectid');
				$project = $em->getRepository('Application\Entity\Projects')->findBy(array('id' => $projectId));
				$esd = $this->getRequest()->getPost('altesd');
				$eed = $this->getRequest()->getPost('alteed');
				$asd = $this->getRequest()->getPost('altasd');
				$aed = $this->getRequest()->getPost('altaed');
				$esthours = $this->getRequest()->getPost('esthours');
				$status = $this->getRequest()->getPost('status');
				$description = $this->getRequest()->getPost('description');
				
				if ($id == '' && $id == ""){
					$duplicateMilestones = $em->createQuery("SELECT m.id as id from Application\Entity\Milestones m Where m.name='$name' ")->getResult();
				}else {
					$duplicateMilestones = $em->createQuery("SELECT m.id as id from Application\Entity\Milestones m Where m.name='$name' and m.id!=$id")->getResult();
				}
				
				$spentTime = 0;
				$spentTimeInArray = explode(":",$esthours);
				if($esthours == ""){
					$response['data']['esthours'] = "null";
				}
				else if($esthours != ""){
					$response['data']['esthours'] = "valid";
					if(!floatval($esthours)){
						//$response['data']['spent']="invalid";
					}
				}
				if (count($spentTimeInArray) != 2 && $esthours != ""){
					$response['data']['esthours'] = "invalid";
				}
				if ( count($spentTimeInArray) != 2){
					$response['data']['esthours'] = "invalid";
				}
				if($name == ""){ $response['data']['name'] = "null";
				}else { $response['data']['name'] = "valid";}
					
				if($projectId == ""){$response['data']['type'] = "null";
				}else {
					$response['data']['type'] = "valid";
					$spentTime = $spentTimeInArray[0]*3600;
					if (count($spentTimeInArray) == 2){
					$spentTime = $spentTime+$spentTimeInArray[1]*60;
					}
					if ($id != '' && $id > 0){ $where="m.id!=$id";
					}else {$where = "1=1";}
					
					$milestonesEst = $em->CreateQuery("Select sum(m.estimated_hours) as mest,p.estimated_hours as pest,p.name as pname from Application\Entity\Projects as p Left JOIN Application\Entity\Milestones m WITH p.id=m.project_id AND $where AND m.project_id=$projectId where p.id=$projectId group By p.id")->getResult();
				    $spentTime1 = 0;
					if (isset($milestonesEst[0]['mest']) && $milestonesEst[0]['mest']>0){
						$spentTime1 = $milestonesEst[0]['mest'];
					}
					if ($milestonesEst[0]['pest'] >= $spentTime1+$spentTime){
						//$response['data']['type']="valid";
						//echo "iff";exit;
					}else {
						$response['data']['esthoursmore'] = "estimatemore";
					}
				}
				if($esd == ""){
					$response['data']['esd'] = "null";
				}else {
					$response['data']['esd'] = "valid";
				}

				if($status == ""){
					$response['data']['status'] = "null";
				}else {
					$response['data']['status'] = "valid";
				}
				
				if ( count($duplicateMilestones) >= 1 && $esthours != ""){
					$response['data']['milestonesname'] = "duplicate";
				}
				
			    $milestoneAssignedUser = $this->getRequest()->getPost('assigneduser');

				if ($milestoneAssignedUser == ""){
					$response['data']['assigneduser'] = "null";
				}else{
					$response['data']['assigneduser'] = "valid";
				}
				
				$toarray = array();
				$validator = new EmailAddress();
				if($milestoneAssignedUser != ""){
					$milestoneAssignedUser = trim($milestoneAssignedUser,",");
					$milestoneAssignedUser = trim($milestoneAssignedUser);
					$temp = explode(",",$milestoneAssignedUser);
					foreach ($temp as $value){
						if((!empty($value) || $value != "" ) ){
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
						if($toEmailString != ""){
							$toEmailString.=","."'".$toarray[$i]."'";
						}
						else{
							$toEmailString="'".$toarray[$i]."'";
						}
						$selected = $em->createQuery("Select u.id as id,u.fname as fname from Application\Entity\User u Where u.email IN ('$toarray[$i]')");
						$countUserFound = $selected->getResult();
				
						if(count($countUserFound) == 0){
							$response['data']['assigneduser'] = "invalid";
							break;
						}
					}
				}
				
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data'])  || in_array("duplicate", $response['data'])) || in_array("estimatemore", $response['data'])){
					$response['returnvalue'] = "invalid";
				}
				else{
					$response = array();
				}
				if(count($response)==0){
					$esd = strtotime($esd)+$offset;
					$newRecord = new \stdClass();
					if($id != "" && $id > 0){
						$newRecord = $em->find('Application\Entity\Milestones', $id);
					}
					else{
						$newRecord = new Milestones();
					}
					
					if($spentTime != ""){
						$newRecord->setEstimated_hours($spentTime);
					}
					$newRecord->setName($name);
					$newRecord->setEstimated_startdate($esd);
					$newRecord->setStatus_id($status);
					$newRecord->setProject_id($projectId);
					if($eed != ""){
						$eed = strtotime($eed)+$offset;
						$newRecord->setEstimated_enddate($eed);
					}
					if($asd != ''){
						$asd = strtotime($asd)+$offset;
						$newRecord->setActual_startdate($asd);
					}
					if($aed != ''){
						$aed = strtotime($aed)+$offset;
						$newRecord->setActual_enddate($aed);
					}
					if($description != ""){
						$newRecord->setDescription($description);
					}

					try{
						$em->persist($newRecord);
						$em->flush();
						$milestoneAssignedId = $newRecord->getId();
						$response['returnvalue']="valid";
						}
					catch (Exception $e)
					{
						$response['data']['returnvalue'] = "exception";
						echo $e->getMessage();exit;
					}
					
					$newMilestonAssignee = new \stdClass();
					$newUsersMapper = array();
					$newUsers = array();
					if($toEmailString != ""){
						$selected = $em->createQuery("Select u.id as id,u.fname as fname from Application\Entity\User u Where u.email IN ($toEmailString)");
						$newUsers = $selected->getResult();
					}
					foreach($newUsers as $user){
						array_push($newUsersMapper,$user['id']);
					}
					$oldUsersMapper = array();
				
					$oldAssignee = $em->getRepository('Application\Entity\MilestonesAssignee')->findBy(array('milestone_id' => $id));
					
					foreach($oldAssignee as $user){
						array_push($oldUsersMapper,$user->getUser_id());
					}
					
					$needToBeDeletedUsers = array();
					$needToBeDeletedUsers = array_diff($oldUsersMapper,$newUsersMapper);
					if(count($needToBeDeletedUsers)>0){
						foreach($oldAssignee as $assignee){
							if(in_array($assignee->getUser_id(),$needToBeDeletedUsers)){
								$activitylog = $em->getRepository('Application\Entity\ActivityLog')->findBy(array('milestone_id' => $id,'user_id'=>$assignee->getUser_id()));
							if(count($activitylog)>0){
							$em->remove($assignee);
							$em->flush();
							}
							}
						}
					}
					
					$needToBeInsertedUsers = array();
					$needToBeInsertedUsers = array_diff($newUsersMapper,$oldUsersMapper);
					if(count($needToBeInsertedUsers)>0){
						foreach ($needToBeInsertedUsers as $newUser){
							$newMilestonAssignee = new MilestonesAssignee();
							
							$newMilestonAssignee->setProject_id($projectId);
							if($milestoneAssignedId != "" && $milestoneAssignedId > 0){
							$newMilestonAssignee->setMilestone_id($milestoneAssignedId);
							}
							else {
								$newMilestonAssignee->setMilestone_id($id);
							}
							$newMilestonAssignee->setUser_id($newUser);
							$em->persist($newMilestonAssignee);
							$em->flush();
						}
						
						$to = "";
						foreach($needToBeInsertedUsers as $sendMailToinserted){
					
							$sendmail = $em->createQuery('Select u.fname as fname,u.lname as lname,u.email as email from Application\Entity\User u Where u.id='.$sendMailToinserted);
							$mailtoinsertedassignee = $sendmail->getResult();
							$fname = $mailtoinsertedassignee[0]['fname'];
							$lname = $mailtoinsertedassignee[0]['lname'];
							$username = $fname." ".$lname;
							$assignedBy = $auth->getIdentity()->fname." ".$auth->getIdentity()->lname;
							if($milestoneAssignedId != "" && $milestoneAssignedId > 0){
								$newMilestonAssignee = $milestoneAssignedId;
							}
							else {
								$newMilestonAssignee = $id;
							}
							$to = array();
							$to[0]['email'] = $mailtoinsertedassignee[0]['email'];
							$to[0]['name'] = $username;
					
							try{
					
								$subject = "Notification for the Assigned Milestone";
								$projectName = $project[0]->getName();
								$content = "Dear $username,<br/> You have been assigned new Milestone by $assignedBy with name <b>$name</b> in $projectName";
								
								if (APPLICATION_ENV == "development")
								{
								  $common->sendEmail("Testing Purpose Notification for the Assigned Milestone \"$name\"","$content",$to,$assignedBy);
								}else {
									$common->sendEmail("Notification for the Assigned Milestone \"$name\"","$content",$to,$assignedBy);
								}	
									$response['returnvalue']="valid";
							}
							catch (Exception $e){
					
								echo $e->getMessage();exit;
							}
					
						}
					}
				}
				echo json_encode($response);
				exit;
			//}
		}
		else{
			$flag = $this->params('flag');
			$id = $this->params('id');
			if(isset($id) && $id > 0){
				$status = $em->createQuery('Select ps from Application\Entity\Projectstatuses ps Order by ps.name ASC')->getArrayResult();
				$projects = $em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
				$valuesToSend = array('status' =>$status,'projects'=>$projects);
				$milestonesAssignee = $em->createQuery('Select u.fname as fname,u.lname as lname,u.email as email from Application\Entity\MilestonesAssignee a JOIN Application\Entity\User u WITH u.id=a.user_id Where a.milestone_id='.$id);
				$assigneeRecord = $milestonesAssignee->getResult();
				$auName = '';
				for($j=0;$j<count($assigneeRecord);$j++){
					$name = '"'.$assigneeRecord[$j]['fname'];
					if(isset($assigneeRecord[$j]['lname'])){
						$name .= " ".$assigneeRecord[$j]['lname'].'"';
					}
					if(isset($assigneeRecord[$j]['email'])){
						$name .= "<".$assigneeRecord[$j]['email'].'>';
					}
					if($auName == ""){
						$auName = ucwords($name);
					}
					else{
						$auName .= ",".ucwords($name);
					}
				}
				$valuesToSend['auName'] = $auName;
				$milestones = $em->find('Application\Entity\Milestones',$id);
				$valuesToSend['milestones'] = $milestones;
				$viewModel = new ViewModel($valuesToSend);
				//$viewModel->setTerminal(true);
				return $viewModel;
			}
			else {
				$project = $em->getRepository('Application\Entity\Projects')->getProjectsByName('ASC');
				$status = $em->createQuery('Select a from Application\Entity\Activitystatuses a Order by a.name ASC')->getArrayResult();
				$valuesToSend = array('status'=>$status,'projects'=>$project);
				$viewModel = new ViewModel($valuesToSend);
				return $viewModel;
			}
		}
	}
	
	public function deletemilestonesAction(){
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$response=array();
			$activity=$em->getRepository('Application\Entity\Activities')->findBy(array('milestone_id' => $id));
			
			if(count($activity)>0){
				$response['returnvalue']="relatedactivityspresent";
			}
			else{
				
			$deleteMilestonAssignee=$em->getRepository('Application\Entity\MilestonesAssignee')->findBy(array('milestone_id' => $id));
			foreach ($deleteMilestonAssignee as $assignee){
				$this->getEntityManager()->remove($assignee);
				$this->getEntityManager()->flush();
			}
			
			$delete=$em->CreateQuery("SELECT m.id FROM Application\Entity\Milestones m where m.id=$id AND m.isdefault!=1");
			$delete =$em->find('Application\Entity\Milestones', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
			$response['returnvalue']="valid";
		}
		
			
			}
	
		echo json_encode($response);
		exit();
	}
	
	
	public function viewprojectsdetailAction() {
		$em = $this->getEntityManager();
		$common=new Common();
		$projectId=$this->params('id');
		$viewModel = new ViewModel();
		$getProjectQuery = $em->createQuery(
			    "SELECT p.id as projectid,p.name as name, p.type_id as typeid, p.estimated_startdate as estartdate,
				p.estimated_enddate as eenddate, p.actual_startdate as astartdate, p.actual_enddate as aenddate,
				p.status_id as statusid, p.estimated_hours as esthours,ps.name as status, pt.name as type
				FROM Application\Entity\Projects p
				JOIN Application\Entity\Projectstatuses ps
				WITH ps.id=p.status_id
				JOIN Application\Entity\Projecttypes pt
				WITH pt.id=p.type_id
				WHERE p.id =". $projectId);
		
		$getProjectQueryResult = $getProjectQuery->getResult();		
		if(isset($getProjectQueryResult[0]['estartdate']) && $getProjectQueryResult[0]['estartdate'] > 0){
			$getProjectQueryResult[0]['estartdate'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectQueryResult[0]['estartdate']),"Asia/Calcutta");
		}
		if(isset($getProjectQueryResult[0]['eenddate']) && $getProjectQueryResult[0]['eenddate'] > 0){
			$getProjectQueryResult[0]['eenddate'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectQueryResult[0]['eenddate']),"Asia/Calcutta");
		}
		if(isset($getProjectQueryResult[0]['astartdate']) && $getProjectQueryResult[0]['astartdate'] > 0){
			$getProjectQueryResult[0]['astartdate'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectQueryResult[0]['astartdate']),"Asia/Calcutta");
		}
		if(isset($getProjectQueryResult[0]['aenddate']) && $getProjectQueryResult[0]['aenddate'] > 0){
			$getProjectQueryResult[0]['aenddate'] = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectQueryResult[0]['aenddate']),"Asia/Calcutta");
		}
		if(isset($getProjectQueryResult[0]['esthours']) && $getProjectQueryResult[0]['esthours'] > 0){
			$getProjectQueryResult[0]['esthours'] = $common->convertSpentTime($getProjectQueryResult[0]['esthours']);
		}
		$viewModel->getProject = $getProjectQueryResult;

		$getProjectLogQuery = $em->createQuery(
				"SELECT al.project_id, al.user_id as userid, u.fname as fname, u.lname as lname,
				SUM(al.seconds_spent) as total_seconds, p.name
				FROM Application\Entity\ActivityLog al
				JOIN Application\Entity\User u
				WITH u.id=al.user_id
				JOIN Application\Entity\Projects p
				WITH p.id=al.project_id
				WHERE al.project_id =". $projectId."
				GROUP BY al.user_id");
		$getProjectLogQueryResult = $getProjectLogQuery->getResult();		
		for($i=0;$i<sizeof($getProjectLogQueryResult);$i++)
		{
			$getProjectLogQueryResult[$i]['uname'] = $getProjectLogQueryResult[$i]['fname']." ".$getProjectLogQueryResult[$i]['lname'];
		}
		$viewModel->getProjectLog = $getProjectLogQueryResult;

		$getProjectHistoryQuery = $em->createQuery(
				"SELECT ph.description, u.fname as fname, u.lname as lname, ph.created_date as cdate,
				ph.created_time as ctime FROM Application\Entity\Projecthistory ph
				JOIN Application\Entity\User u
				WITH u.id=ph.activity_by_id
				WHERE ph.project_id =". $projectId);
		$getProjectHistoryQueryResult = $getProjectHistoryQuery->getResult();
		for($i=0;$i<sizeof($getProjectHistoryQueryResult);$i++)
		{
			$projectHistoryCreatedDate='';
			if($getProjectHistoryQueryResult[$i]['cdate']>0){
				if($getProjectHistoryQueryResult[$i]['ctime']>0){
					$projectHistoryCreatedDate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectHistoryQueryResult[$i]['cdate']+$getProjectHistoryQueryResult[$i]['ctime']),"Asia/Calcutta");
				}
				else{
					$projectHistoryCreatedDate=$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s", $getProjectHistoryQueryResult[$i]['cdate']),"Asia/Calcutta");
				}
			}
			$getProjectHistoryQueryResult[$i]['cdate'] =$projectHistoryCreatedDate;
			$getProjectHistoryQueryResult[$i]['name'] = $getProjectHistoryQueryResult[$i]['fname']." ".$getProjectHistoryQueryResult[$i]['lname'];
		}
		$viewModel->getProjectHistory = $getProjectHistoryQueryResult;
		$viewModel->projectId = $projectId;
		return $viewModel;
	}
	public function getspenthoursbyprojectAction() {
		$em = $this->getEntityManager();
		$common=new Common();
		$response=array();
		$projectId=$this->getRequest()->getPost('projectid');
		$startDate=$this->getRequest()->getPost('startdate');
		$endDate=$this->getRequest()->getPost('enddate');
		if($startDate!=""){
			$startDate= $common->ConvertLocalTimezoneToGMT($startDate,'Asia/Calcutta','Y-m-d H:i:s');
			$startDate= strtotime($startDate);
		}
		if($endDate!=""){
			$endDate= $common->ConvertLocalTimezoneToGMT($endDate,'Asia/Calcutta','Y-m-d H:i:s');
			$endDate= strtotime($endDate);
		}
		$where="al.project_id =". $projectId;
		
		if($startDate!="" and $endDate!=""){
			$where.=" and al.activity_date between $startDate and $endDate";
		}
		else if($startDate!=""){
			$where.=" and al.activity_date > $startDate";
		}
		else if($endDate!=""){
			$where.=" and al.activity_date < $endDate";
		}		
		$response['returnvalue']="invalid";
		$getProjectLogQuery = $em->createQuery(
				"SELECT al.project_id, al.user_id as userid, u.fname as fname, u.lname as lname,
				SUM(al.seconds_spent) as total_seconds, p.name
				FROM Application\Entity\ActivityLog al
				JOIN Application\Entity\User u
				WITH u.id=al.user_id
				JOIN Application\Entity\Projects p
				WITH p.id=al.project_id
				WHERE $where GROUP BY al.user_id");
		//echo $getProjectLogQuery->getSQL();exit;
		$getProjectLogQueryResult = $getProjectLogQuery->getResult();
		if(count($getProjectLogQueryResult)>0){
			$response['returnvalue']="valid";
			$totaltime=0;
			for($i=0;$i<sizeof($getProjectLogQueryResult);$i++)
			{
				$response['data'][$i]['uname'] = ucwords($getProjectLogQueryResult[$i]['fname']." ".$getProjectLogQueryResult[$i]['lname']);
				$response['data'][$i]['userid']=$getProjectLogQueryResult[$i]['userid'];
				$totaltime=$totaltime+$getProjectLogQueryResult[$i]['total_seconds'];
				$response['data'][$i]['time']=$getProjectLogQueryResult[$i]['total_seconds'];
			}
			$response['totaltime']=$totaltime;
		}
		$spentHoursDetail=array('response'=>$response);
		$viewModel= new ViewModel($spentHoursDetail);
		$viewModel->setTerminal(true);
		return $viewModel;
		/*header("Content-type: application/json");
		echo json_encode($response);
		exit;*/
	}
	public function getprojectallocationdetailAction(){
		
		$em = $this->getEntityManager();
		$common=new Common();
		$response=array();
		$projectId=$this->getRequest()->getPost('projectid');
		

		$projectResult = $em->createQuery(
				"SELECT al.project_id,SUM(al.seconds_spent) as total_seconds, p.estimated_hours as estimatedHours
				FROM Application\Entity\Projects p
				LEFT JOIN Application\Entity\ActivityLog al
				WITH al.project_id=p.id
				WHERE al.project_id ='$projectId'")->getArrayResult();
		$resourceAllocation=$em->CreateQuery("SELECT SUM(ra.duration) as allocated FROM Application\Entity\ResourceAllocation ra WHERE ra.project_id=$projectId")->getArrayResult();
		
	 foreach ($projectResult as $row){
	 	$response['returnvalue']="valid";
	 	$response['data']=$row;
	 	$response['allocated']=$resourceAllocation[0]['allocated'];
	 	$response['estimatedHours']=$common->convertSpentTime($row['estimatedHours']);
	 	$response['totalSpent']=$common->convertSpentTime($row['total_seconds']);
	 }
	 echo json_encode($response);
	 exit;
	}
	
	public function getuserallocationdetailbyprojectAction(){
		$em = $this->getEntityManager();
		$common=new Common();
		$response=array();
		$projectId=$this->getRequest()->getPost('projectid');
		$userId=$this->getRequest()->getPost('userid');
		

		$projectResult = $em->createQuery(
				"SELECT al.project_id,SUM(al.seconds_spent) as total_seconds, p.estimated_hours as estimatedHours
				FROM Application\Entity\ActivityLog al
				JOIN Application\Entity\Projects p
				WITH p.id=al.project_id
				WHERE al.project_id =".$projectId." AND al.user_id='$userId'
				GROUP BY al.user_id")->getArrayResult();
		
		$resourceAllocation=$em->CreateQuery("SELECT SUM(ra.duration) as allocated FROM Application\Entity\ResourceAllocation ra WHERE ra.project_id=$projectId AND ra.user_id=$userId")->getArrayResult();
		if(count($resourceAllocation)>0)
		$response['allocated']=$resourceAllocation[0]['allocated'];
	 foreach ($projectResult as $row){
	 	
	 	$response['data']=$row;
	 
	 	$response['estimatedHours']=$common->convertSpentTime($row['estimatedHours']);
	 	$response['totalSpent']=$common->convertSpentTime($row['total_seconds']);
	 }
	 $response['returnvalue']="valid";
	 echo json_encode($response);
	 exit;
	}
}