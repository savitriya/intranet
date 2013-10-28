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


class CompanyController extends AbstractActionController
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
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
	}
	public function getRepository()
	{
		return  $this->getEntityManager()->getRepository('Application\Entity\Activitycategories');
	}
	public function gridcompanyAction(){
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
			$sidx="c.name";
		}
		else if($sidx=="id"){
			$sidx="c.id";
		}
		$em=$this->getEntityManager();
		$comany = $em->createQuery("SELECT c.id as id,c.name as name FROM Application\Entity\Company c order by $sidx $sord")->getResult();
	
		$i=0;
		$response=array();
		foreach ($comany as $rws){
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array($rws['name']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function addcompanyAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$id=$this->getRequest()->getPost('id');
		$company=$this->getRequest()->getPost('company');
		$em=$this->getEntityManager();
		$response=array();
		if ($id!='' && $id>0){
			$add=$em->find('Application\Entity\Company', $id);
		}else {
			$add=new Company();
		}
		$add->__set("name", $company);
		try{
			$em->persist($add);
			$em->flush();
			$response['returnvalue']="valid";
		}
		catch (Exception $e)
		{
			$response['returnvalue']="exception";
			echo $e->getMessage();exit;
		}
		echo json_encode($response);
		exit;
	}
	
	public function deletecompanyAction(){
		$auth = new AuthenticationService();
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		$response=array();
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$user=$em->createQuery("SELECT u.id as uid FROM Application\Entity\User u where u.company_id=$id")->getResult();
			if (count($user)=="" && count($user)==0){
				$delete =$em->find('Application\Entity\Company', $id);
				if ($delete) {
					$this->getEntityManager()->remove($delete);
					$this->getEntityManager()->flush();
					$response['data']='valid';
				}
			}else{
				$response['data']='invalid';
			}
		}
		echo json_encode($response);
		exit;
	
	}
	public function userbycompanyAction(){
		
	}
	public function subgriduserbycompanyAction(){
		$companyid =$this->getRequest()->getPost('id');
		$em = $this->getEntityManager();
		$userbycompany = $em->createQuery("SELECT u.id as id,u.fname as fname,u.lname as lname,u.email as email,u.password as password,u.mobile as mobile,u.dob as dob,u.doy as doy,u.isactive as active,u.isadmin as admin  FROM Application\Entity\User u  WHERE u.company_id =$companyid")->getResult();
	//	print_r($userbycompany);exit;
		$i=0;
		$common=new Misc();
		$response=array();
		foreach ($userbycompany as $rws){
			$dob=$rws['dob'];
			$doy=$rws['doy'];
			$db='';
			if(isset($dob) && $dob!="-" && $dob!="" && isset($doy) && $doy>0){
				$db=$common->ConvertGMTToLocalTimezone($doy."-".$dob,"Asia/Calcutta","d/m/Y");
			}
			$action="<a href='/editcontact/userid/".$rws['id']."'><img alt='Edit Detail' src='/images/edit.png' title='Edit Detail'/></a>&nbsp;<a href='javascript:deleteUser(".$rws['id'].')'."'><img alt='Delete Detail' src='/images/delete.png' title='Delete Detail'/></a>";
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['fname']." ".$rws['lname']),$rws['email'],$rws['mobile'],$db,$rws['active'],$rws['admin'],$action);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
}