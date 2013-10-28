<?php
namespace Application\Controller;

use Zend\Http\Header\IfMatch;

use Doctrine\ORM\Query\Expr\Select;
use IntranetUtils\Common;
use IntranetUtils\Encryption;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use	Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Doctrine\ORM\Query\Expr;
use Application\Entity\ActivityLog;
use Application\Entity\Leaves;
use Application\Entity\Sentmail;
use Application\Entity\User;
use Zend\Validator\EmailAddress;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;
use Zend\Config\Reader\Ini;
use Application\Entity\Preferences;

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class LeavesController extends AbstractActionController
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
			
		$em=$this->getEntityManager();

		if($this->getRequest()->isXmlHttpRequest())
		{

			$validator = new EmailAddress();
			$response = array();
			$response['data']['emailto'] = '';
			$startDate = $this->getRequest()->getPost('start_date');
			$endDate = $this->getRequest()->getPost('end_date');
			$emailSubject = $this->getRequest()->getPost('email_subject');
			$emailTo = $this->getRequest()->getPost('email_to');
			$emailCc = $this->getRequest()->getPost('email_cc');
			$emailBcc = $this->getRequest()->getPost('email_bcc');
			$emailBody = $this->getRequest()->getPost('email_body');

			if($startDate == '')
			{
				$response['data']['startdate'] = 'null';
			}
			if($endDate == '')
			{
				$response['data']['enddate'] = 'null';
			}
			if($emailSubject == '')
			{
				$response['data']['emailsubject'] = 'null';
			}
			if($emailBody == '')
			{
				$response['data']['emailbody'] = 'null';
			}

			if(strtotime($startDate)>strtotime($endDate))
			{
				$response['data']['enddate'] = 'invalid';
				$response['data']['startdate'] = 'invalid';
			}

			$toString = $emailTo;
			$ccString = $emailCc;
			$bccString = $emailBcc;
			$toarray = array();
			$toarray['name'] = array();
			$ccarray = array();
			$ccarray['name'] = array();
			$bccarray = array();
			$bccarray['name'] = array();

			if($emailTo!="")
			{
				$emailTo=trim($emailTo,",");
				$emailTo=trim($emailTo);
				$temp=explode(",",$emailTo);
				foreach ($temp as $value)
				{
					if((!empty($value) || $value!="" ))
					{
						if(preg_match_all("/\<(.*?)\>/",$value,$matches))
						{
							if($validator->isValid($matches[1][0]))
							{
								array_push($toarray, $matches[1][0]);
							}
						}
						else
						{
							if($validator->isValid($value))
							{
								$toDetails = $em->getRepository('Application\Entity\User')->findBy(array('email' => "$value"));
								if(count($toDetails)>0){
									array_push($toarray, $value);
								}
							}
						}
						if(preg_match_all("/\"(.*?)\"/",$value,$matches))
						{
							array_push($toarray['name'], $matches[1][0]);
						}
						elseif (preg_match_all("/\`(.*?)\`/",$value,$matches)){
							array_push($toarray['name'], $matches[1][0]);
						}
						elseif (preg_match_all("/\'(.*?)\'/",$value,$matches)){
							array_push($toarray['name'], $matches[1][0]);
						}
					}
				}
			}
			else
			{
				$response['data']['emailto']="null";
			}

			if(count($toarray)==0 && $toString!="")
			{
				$response['data']['emailto']="invalid";
			}

			if($emailCc!="")
			{
				$emailCc=trim($emailCc,",");
				$emailCc=trim($emailCc);
				$temp=explode(",",$emailCc);
				foreach ($temp as $value)
				{
					if((!empty($value) || $value!="" ))
					{
						if(preg_match_all("/\<(.*?)\>/",$value,$matches))
						{
							if($validator->isValid($matches[1][0]))
							{
								array_push($ccarray, $matches[1][0]);
							}
						}
						else
						{
							if($validator->isValid($value))
							{
								array_push($ccarray, $value);
							}
						}
						if(preg_match_all("/\"(.*?)\"/",$value,$matches))
						{
							array_push($ccarray['name'], $matches[1][0]);
						}
						elseif (preg_match_all("/\`(.*?)\`/",$value,$matches))
						{
							array_push($ccarray['name'], $matches[1][0]);
						}
						elseif (preg_match_all("/\'(.*?)\'/",$value,$matches))
						{
							array_push($ccarray['name'], $matches[1][0]);
						}
					}
				}
			}
			if(count($ccarray)==0 && $ccString!="")
			{
				$response['data']['emailcc']="invalid";
			}

			if($emailBcc!="")
			{
				$emailBcc=trim($emailBcc,",");
				$emailBcc=trim($emailBcc);
				$temp=explode(",",$emailBcc);
				foreach ($temp as $value)
				{
					if((!empty($value) || $value!="" ))
					{
						if(preg_match_all("/\<(.*?)\>/",$value,$matches))
						{
							if($validator->isValid($matches[1][0]))
							{
								array_push($bccarray, $matches[1][0]);
							}
						}
						else
						{
							if($validator->isValid($value))
							{
								array_push($bccarray, $value);
							}
						}
						if(preg_match_all("/\"(.*?)\"/",$value,$matches))
						{
							array_push($bccarray['name'], $matches[1][0]);
						}
						elseif(preg_match_all("/\`(.*?)\`/",$value,$matches))
						{
							array_push($bccarray['name'], $matches[1][0]);
						}
						elseif(preg_match_all("/\'(.*?)\'/",$value,$matches))
						{
							array_push($bccarray['name'], $matches[1][0]);
						}
					}
				}
			}
			if(count($bccarray)==0 && $bccString!="")
			{
				$response['data']['emailbcc']="invalid";
			}

			if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data'])))
			{
				$response['returnvalue']="invalid";
				print_r(json_encode($response));
				exit;
			}
			else
			{
				$response=array();
			}
			//CALCULATE NUMBER OF DAYS OF LEAVE
			$common = new Common();
			$numDays = $common->timedifference($startDate,$endDate,'days');
			//	print_r($numDays);exit;
			//RENDERING LEAVE EMAIL TEMPLATE
			$phpRenderer=new PhpRenderer();
			$resolver = new Resolver\AggregateResolver();
			$phpRenderer->setResolver($resolver);
			$map = new Resolver\TemplateMapResolver(array(
					'templates/leavetemplate' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'leavetemplate.phtml',
			));
			$stack = new Resolver\TemplatePathStack(array(
					'script_paths' => array(
							__DIR__ . '/../../view',
					)
			));
			$resolver->attach($map)    // this will be consulted first
			->attach($stack);

			//COLLECTING DETAILS TO BE SENT IN LEAVE EMAIL
			$auth = new AuthenticationService();
			$loggedInUserId = $auth->getIdentity()->id;
			$senderDetails = $em->getRepository('Application\Entity\User')->find($loggedInUserId);
			$senderName = ucwords($senderDetails->__get('fname')." ".$senderDetails->__get('lname'));
			$senderEmail = $senderDetails->__get('email');			
			
			$dateInYmd=date("Y-m-d");
			$dateinymdHis=date("Y-m-d H:i:s");
			$createdDate=strtotime($dateInYmd);
			$createdTime=strtotime($dateinymdHis)-strtotime($dateInYmd);

			//SAVE EMAIL DETAILS IN DB
			$newMail = new Leaves();
			$newMail->setTitle($emailSubject);
			$newMail->setStartDate(strtotime($startDate));
			$newMail->setEndDate(strtotime($endDate));
			$newMail->setNoOfDays($numDays['days']);
			$newMail->setDescription($emailBody);
			$newMail->setIsSanctioned(2);
			$newMail->setCreatedDate($createdDate);
			$newMail->setCreatedTime($createdTime);
			$newMail->setUserId($auth->getIdentity()->__get('id'));

			try{
				$em->persist($newMail);
				$em->flush();
				$newMailId = $newMail->getId();
			}

			catch (Exception $e){
				echo $e->getMessage();
				echo $e->getTraceAsString();exit;
			}
			
			$to = array(); $cc = array(); $bcc = array();
			$to[0]['email'] = $toarray[0];
			$to[0]['name'] = $toarray['name'][0];
			if(isset($ccarray[0]) && sizeof($ccarray[0])>0 && isset($ccarray['name'][0]) && sizeof($ccarray['name'][0])>0) {
			$cc[0]['email'] = $ccarray[0];
			$cc[0]['name'] = $ccarray['name'][0];
			}
			
			if(isset($bccarray[0]) && sizeof($bccarray[0])>0 && isset($bccarray['name'][0]) && sizeof($bccarray['name'][0])>0) {
				$bcc[0]['email'] = $bccarray[0];
				$bcc[0]['name'] = $bccarray['name'][0];
			}
			
			$sentMail = new Sentmail();
			$sentMail->setParentId($newMailId);
			$sentMail->setTableName('leave');
			$sentMail->setTypeId(1);
			$sentMail->setMailTo($toString);
			$sentMail->setCc($ccString);
			$sentMail->setBcc($bccString);
			$sentMail->setMailFrom($senderEmail);
			$sentMail->setContent($emailBody);
			$sentMail->setCreatedDatetTime(strtotime(date("Y-m-d H:i:s")));
			$sentMail->setSentDateTime(strtotime(date("Y-m-d H:i:s")));
			try{
				$em->persist($sentMail);
				$em->flush();
			}
			catch (Exception $e){
				echo $e->getMessage();exit;
			}

			//FETCH MAILID FROM DB AND ENCRYPT IT
			$sentMailId = $sentMail->getId();
			$encrypt = new Encryption();
			$encryptedId = $encrypt->encode($sentMailId);

			//SEND EMAIL
			$leaveEmailDetails = array('sender'=>$senderName,'body'=>$emailBody,'startdate'=>$startDate,
					'numdays'=>$numDays,'isSender'=>false,'enddate'=>$endDate,'mailid'=>$encryptedId);
			$leaveEmailTemplate = new ViewModel($leaveEmailDetails);

			try{
				$leaveEmailTemplate->setTemplate("templates/leavetemplate");
				$htmlOutputforLeave = $phpRenderer->render($leaveEmailTemplate);
				$common->sendEmail($emailSubject, $htmlOutputforLeave,$to,$senderName,$cc,$bcc,NULL);

				$leaveEmailTemplate->setVariable('isSender',true);
				$htmlOutputforLeave = $phpRenderer->render($leaveEmailTemplate);
				$common->sendEmail($emailSubject, $htmlOutputforLeave,$to,$senderName,NULL,NULL);

				//SAVE FULL EMAIL IN DB
				$sentMail = $em->find('Application\Entity\Sentmail', $sentMailId);
				$sentMail->setContent($htmlOutputforLeave);
				$leaveId = $sentMail->getParentId();
				$leavesRow = $em->find('Application\Entity\Leaves', $leaveId);
				$leavesRow->setDescription($htmlOutputforLeave);
				$em->persist($leavesRow);
				$em->persist($sentMail);
				$em->flush();

				$response['returnvalue'] = "valid";
			}

			catch (Exception $e){
				echo $e->getMessage();
				$response['returnvalue'] = "invalid";
			}
			print_r(json_encode($response));
			exit;
		} else {
			$preferences = $em->getRepository('Application\Entity\Preferences')->findOneBy(array('user_id'=>$auth->getIdentity()->id));
			$leaveContact = $preferences->getLeaveContact();
			$leaveContactArray = array();
			$leaveContactArray['to'] = $leaveContact;
			$viewModel = new ViewModel($leaveContactArray);
			return $viewModel;
		}
	}

	public function approveleaveAction()
	{
		$reader = new Ini();
		$data =$reader->fromFile(__DIR__."/../../../../../config/application.ini");
		$em=$this->getEntityManager();
		$encryptedId = $this->params('id');
		$flag = $this->params('flag');
	
		//DECRYPT SENT MAIL ID
		$decrypt = new Encryption();
		$sentMailId = $decrypt->decode($encryptedId);
	
		//COLLECTING DETAILS TO BE SENT IN LEAVE RESPONSE EMAIL
		$sentMailRow = $em->find('Application\Entity\Sentmail', $sentMailId);
		$emailTo = $sentMailRow->getMailTo();
		$emailCc = $sentMailRow->getCc();
		$emailBcc = $sentMailRow->getBcc();
		$mailSentDate = $sentMailRow->getSentDateTime();
		$mailSender = $sentMailRow->getMailFrom();
		$leaveId = $sentMailRow->getParentId();
	
	
		$toString = $emailTo;
		$ccString = $emailCc;
		$bccString = $emailBcc;
		$toarray = array();
		$toarray['name'] = array();
		$ccarray = array();
		$ccarray['name'] = array();
		$bccarray = array();
		$bccarray['name'] = array();
		$validator = new EmailAddress();
		if($emailTo!="")
		{
			$emailTo=trim($emailTo,",");
			$emailTo=trim($emailTo);
			$temp=explode(",",$emailTo);
			foreach ($temp as $value)
			{
				if((!empty($value) || $value!="" ))
				{
					if(preg_match_all("/\<(.*?)\>/",$value,$matches))
					{
						if($validator->isValid($matches[1][0]))
						{
							array_push($toarray, $matches[1][0]);
						}
					}
					else
					{
						if($validator->isValid($value))
						{
							$toDetails = $em->getRepository('Application\Entity\User')->findBy(array('email' => "$value"));
							if(count($toDetails)>0){
								array_push($toarray, $value);
							}
						}
					}
					if(preg_match_all("/\"(.*?)\"/",$value,$matches))
					{
						array_push($toarray['name'], $matches[1][0]);
					}
					elseif (preg_match_all("/\'(.*?)\'/",$value,$matches)){
						array_push($toarray['name'], $matches[1][0]);
					}
				}
			}
		}
		else
		{
			$response['data']['emailto']="null";
		}
	
		if(count($toarray)==0 && $toString!="")
		{
			$response['data']['emailto']="invalid";
		}
	
		if($emailCc!="")
		{
			$emailCc=trim($emailCc,",");
			$emailCc=trim($emailCc);
			$temp=explode(",",$emailCc);
			foreach ($temp as $value)
			{
				if((!empty($value) || $value!="" ))
				{
					if(preg_match_all("/\<(.*?)\>/",$value,$matches))
					{
						if($validator->isValid($matches[1][0]))
						{
							array_push($ccarray, $matches[1][0]);
						}
					}
					else
					{
						if($validator->isValid($value))
						{
							array_push($ccarray, $value);
						}
					}
					if(preg_match_all("/\"(.*?)\"/",$value,$matches))
					{
						array_push($ccarray['name'], $matches[1][0]);
					}
				}
			}
		}
		if(count($ccarray)==0 && $ccString!="")
		{
			$response['data']['emailcc']="invalid";
		}
	
		if($emailBcc!="")
		{
			$emailBcc=trim($emailBcc,",");
			$emailBcc=trim($emailBcc);
			$temp=explode(",",$emailBcc);
			foreach ($temp as $value)
			{
				if((!empty($value) || $value!="" ))
				{
					if(preg_match_all("/\<(.*?)\>/",$value,$matches))
					{
						if($validator->isValid($matches[1][0]))
						{
							array_push($bccarray, $matches[1][0]);
						}
					}
					else
					{
						if($validator->isValid($value))
						{
							array_push($bccarray, $value);
						}
					}
					if(preg_match_all("/\"(.*?)\"/",$value,$matches))
					{
						array_push($bccarray['name'], $matches[1][0]);
					}
				}
			}
		}
			
		$leavesRow = $em->find('Application\Entity\Leaves', $leaveId);
	
		if($flag == 'approve')
		{
			$leavesRow->setIsSanctioned(1);
			$tablename="approval";
		}
		else
		{
			$leavesRow->setIsSanctioned(0);
			$tablename="disapproval";
		}
	
		$em->persist($leavesRow);
	
		$responseMail = new Sentmail();
		$responseMail->setTypeId(1);
		$responseMail->setParentId($leaveId);
		//no any tabl by nane "approval" use for differenciate only
		$responseMail->setTableName($tablename);
		$responseMail->setMailTo($mailSender);
		$responseMail->setCc($emailTo);
		$responseMail->setBcc($emailBcc);
		$responseMail->setMailFrom($data['mail_sender']['from']);
		$responseMail->setContent(' ');
		$responseMail->setCreatedDatetTime(strtotime(date("Y-m-d H:i:s")));
		$responseMail->setSentDateTime(strtotime(date("Y-m-d H:i:s")));
		$em->persist($responseMail);
	
		try
		{
			$em->flush();
		}
		catch (Exception $e)
		{
			echo $e->getMessage();exit;
		}
	
	
		$to = array(); $cc = array(); $bcc = array();
		if(isset($toarray[0]) && sizeof($toarray[0])>0 && isset($toarray['name'][0]) && sizeof($toarray['name'][0])>0) {
		$to[0]['email'] = $toarray[0];
		$to[0]['name'] = $toarray['name'][0];
		}
		if(isset($ccarray[0]) && sizeof($ccarray[0])>0 && isset($ccarray['name'][0]) && sizeof($ccarray['name'][0])>0) {
		$cc[0]['email'] = $ccarray[0];
		$cc[0]['name'] = $ccarray['name'][0];
		}
		if(isset($bccarray[0]) && sizeof($bccarray[0])>0 && isset($bccarray['name'][0]) && sizeof($bccarray['name'][0])>0) {
			$bcc[0]['email'] = $bccarray[0];
			$bcc[0]['name'] = $bccarray['name'][0];
		}
			
		//RENDERING LEAVE RESPONSE EMAIL TEMPLATE
		$phpRenderer=new PhpRenderer();
		$resolver = new Resolver\AggregateResolver();
		$phpRenderer->setResolver($resolver);
		$map = new Resolver\TemplateMapResolver(array(
				'templates/leaveresponsetemplate' => __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'view'.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'leaveresponsetemplate.phtml',
		));
		$stack = new Resolver\TemplatePathStack(array(
				'script_paths' => array(
						__DIR__ . '/../../view',
				)
		));
		$resolver->attach($map)    // this will be consulted first
		->attach($stack);
	
		//CONVERT TIMESTAMP TO LOCAL DATE
		$common = new Common();
		$mailSentDate = $common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$mailSentDate), "Asia/Calcutta");
	
		$leaveTitle = $leavesRow->getTitle();
		//SEND EMAIL
		if($flag == 'approve')
		{
			$leaveResponseDetails = array('isapproved'=>1,'sentdate'=>$mailSentDate,'subject'=>$leaveTitle);
		}
		else
		{
			$leaveResponseDetails = array('isapproved'=>0,'sentdate'=>$mailSentDate,'subject'=>$leaveTitle);
		}
		$leaveResponseTemplate = new ViewModel($leaveResponseDetails);
		$defaultEmail = array();
		$defaultEmail[0]['email'] = $data['mail_sender']['from'];
		$defaultEmail[0]['name'] = $data['mail_sender']['name'];
		try
		{
			$leaveResponseTemplate->setTemplate("templates/leaveresponsetemplate");
			$htmlOutputforLeaveResponse = $phpRenderer->render($leaveResponseTemplate);
			$common->sendEmail("Re:$leaveTitle", $htmlOutputforLeaveResponse, $mailSender,$data['mail_sender']['name'],$cc,$bcc, $data['mail_sender']['from']);
			//SAVE FULL RESPONSE EMAIL IN DB
			$responseMailId = $responseMail->getId();
			$responseMailRow = $em->find('Application\Entity\Sentmail', $responseMailId);
			$responseMailRow->setContent($htmlOutputforLeaveResponse);
			$em->persist($responseMailRow);
			$em->flush();
	
			$response['mail'] = "valid";
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
			$response['mail'] = "invalid";
		}
		//print_r(json_encode($response));
		echo "The response to Leave Application has been sent successfully.";
		exit;
	}
	
	
	
	public function leavelistAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity())
		{
			return $this->redirect()->toRoute('home');
		}
		if($this->getRequest()->isXmlHttpRequest())
		{
			$em = $this->getEntityManager();
			$currentUserId = $auth->getIdentity()->__get('id');
			$isUserAdmin = $auth->getIdentity()->__get('isadmin');

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
			if($sidx=="" ||$sidx=="title"){
				$sidx="l.title";
			}
			else if($sidx=="start_date"){
				$sidx="l.start_date";
			}
			else if($sidx=="end_date"){
				$sidx="l.end_date";
			}
			else if($sidx=="no_of_days"){
				$sidx="l.no_of_days";
			}
			else if($sidx=="is_sanctioned"){
				$sidx="l.is_sanctioned";
			}
			else if($sidx=="created_date"){
				$sidx="l.created_date";
			}

			$where="";
			if(!$isUserAdmin)
			{
				$leaveRecords = $em->getRepository('Application\Entity\Leaves')->getuserLeaveList($currentUserId);
			}
			else
			{
				$leaveRecords = $em->getRepository('Application\Entity\Leaves')->getLeaveList();
			}
			if($where=="")
			{
				$where="1=1";
			}
			$totalPages = 0;
			if (count($leaveRecords)>0){
				$totalPages = ceil(count($leaveRecords)/ $limit);
			}
			$response = array();
			$response['page'] = $page;
			$response['total'] = $totalPages;
			$response['records'] = count($leaveRecords);
			$i=0;
			if(!$isUserAdmin)
			{
				$leaveRecords = $em->createQuery("Select l.id as id,l.title as title,l.start_date as start_date,l.end_date as end_date,l.description as description,l.is_sanctioned as is_sanctioned,l.created_date as created_date,l.created_time as created_time,l.user_id as userid from Application\Entity\Leaves l where l.user_id=$currentUserId")
				               ->setFirstResult( $start )
		                       ->setMaxResults( $limit )
				               ->getResult();
			}
			else
			{
				$leaveRecords = $em->createQuery("Select l.id as id,l.title as title,l.start_date as start_date,l.end_date as end_date,l.description as description,l.is_sanctioned as is_sanctioned,l.created_date as created_date,l.created_time as created_time,l.user_id as userid from Application\Entity\Leaves l ")
				               ->setFirstResult( $start )
							   ->setMaxResults( $limit )
				               ->getResult();
			}
			
			foreach ($leaveRecords as $lr){
				$common =new Common();
				$sentMailRecord = $em->getRepository('Application\Entity\Sentmail')->findOneBy(array('parent_id' => $lr['id'],'table_name'=>'leave'));
				$user=$em->getRepository('Application\Entity\User')->findOneBy(array('id'=>$lr['userid']));
				$name=ucwords($user->__get('fname')." ".$user->__get('lname'));
				if (count($sentMailRecord)>0){
					$sentMailId = $sentMailRecord->getId();
					$encrypt = new Encryption();
					$encryptedId = $encrypt->encode($sentMailId);
					if($isUserAdmin)
					{
						$action="<a onclick='javascript:leavedetail(".$lr['id'].")'><i class='icon-file'></i></a>&nbsp;<a href='/leave/approveleave/approve/".$encryptedId."'><i class='icon-ok'></i></a>&nbsp;<a href='/leave/approveleave/disapprove/".$encryptedId."'><i class='icon-remove'></i></a>";
					}
					else
					{
						$action="<a onclick='javascript:leavedetail(".$lr['id'].")'><img src='/images/view.png' alt='View Detail' title='View Detail'/></a>";
					}
				}else{
					$action="Not available";
				}
				If ($lr['is_sanctioned']==1){
					$status="Approved";
				}
				elseif ($lr['is_sanctioned']==0){
					$status="Disapproved";
				}elseif ($lr['is_sanctioned']==2){
					$status="pending";
				}
				$dateofapply=$lr['created_date']+$lr['created_time'];
				$response['rows'][$i]['id'] = $lr['id'];
				$response['rows'][$i]['cell']=array(ucwords($lr['title']),
						$name,
						date("d/m/Y H:i",$lr['start_date']),
						date("d/m/Y H:i",$lr['end_date']),
						//$lr->getNoOfDays(),
				       
						$common->ConvertGMTToLocalTimezone(date("Y-m-d H:i:s",$dateofapply), "Asia/Calcutta","d/m/Y H:i"),
						$status,
						$action);
				$i++;
			}
			header("Content-type: application/json");
			echo json_encode($response);
			exit;
		}
	}

	public function viewleaveAction()
	{
		$auth = new AuthenticationService();
		if(!$auth->hasIdentity()) {
			return $this->redirect()->toRoute('home');
		}
		$leaveId = $this->params('id');
		if(isset($leaveId)) {
			$em = $this->getEntityManager();
			$currentUserId = $auth->getIdentity()->__get('id');
			$isUserAdmin = $auth->getIdentity()->__get('isadmin');
			$leaveRecords = $em->getRepository('Application\Entity\Leaves')->find($leaveId);
			$sendmail=$em->getRepository('Application\Entity\Sentmail')->findBy(array('parent_id' => $leaveId));
			$to="";
			$cc="";
			$replyfrom="";
			$startStamp = $leaveRecords->getStartDate();
			$endStamp = $leaveRecords->getEndDate();
			$createdStamp = $leaveRecords->getCreatedDate();
			$leaveRecords->setStartDate(date("d/m/Y",$startStamp));
			$leaveRecords->setEndDate(date("d/m/Y",$endStamp));
			$leaveRecords->setCreatedDate(date("d/m/Y",$createdStamp));
			if($leaveRecords->getIsSanctioned()==1){
				$leaveRecords->setIsSanctioned("Approved");
			}elseif ($leaveRecords->getIsSanctioned()==0){
				$leaveRecords->setIsSanctioned("Disapproved");
			}elseif ($leaveRecords->getIsSanctioned()==2){
				$leaveRecords->setIsSanctioned("Pendding");
			}
			$leaveRecords->setDescription(strip_tags($leaveRecords->getDescription()));

			$response = array();
			$emailConversations=array();
			$i=0;
			foreach ($sendmail as $rws){
				if($rws->getTablename() =="leave"){
					$to=$rws->getMailTo();
					$cc=$rws->getCc();
				}	else {
					$createddateTime=$rws->getCreatedDatetTime();
					$replyfrom=$rws->getMailFrom();
					$content=$rws->getContent();
					$emailConversations[$i]['from']=$replyfrom;
					$emailConversations[$i]['content']=$content;
					$emailConversations[$i]['createdatetime']=date("d-m-Y H:i",$createddateTime);
					$i++;
				}
			}
				
			$response['title'] = $leaveRecords->getTitle();
			$response['startDate'] = $leaveRecords->getStartDate();
			$response['endDate'] = $leaveRecords->getEndDate();
			$response['noOfDays'] = $leaveRecords->getNoOfDays();
			$response['createdDate'] = $leaveRecords->getCreatedDate();
			$response['isSanctioned'] = $leaveRecords->getIsSanctioned();
			$response['description'] = $leaveRecords->getDescription();
			$response['to'] = $to;
			$response['cc'] = $cc;
			$response['emailConversations'] = $emailConversations;
		
			$viewLeave = new ViewModel($response);
			return $viewLeave;
		}
	}

}