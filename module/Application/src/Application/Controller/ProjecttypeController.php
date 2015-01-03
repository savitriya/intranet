<?php
namespace Application\Controller;
use Doctrine\ORM\EntityRepository;
use Application\Entity\Projectstatuses;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use IntranetUtils\Common;
use	Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Decoder;
use Zend\Authentication\AuthenticationService;
use Application\Entity\Milestones;
use Application\Entity\Projects;
use Application\Entity\Projecttypes;
use Application\Entity\User;
use Application\Repository\UserRepository;


/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ProjecttypeController extends AbstractActionController
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
	}

	public function gridprojecttypeAction(){
	
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
			$sidx="pt.name";
		}		

		$em = $this->getEntityManager();
		$totalRecords = $em->getRepository('Application\Entity\Projecttypes')->findAll();
		
		$totalPages = 0;
		if (count($totalRecords)>0){
			$totalPages = ceil(count($totalRecords)/ $limit);
		}
		$response = array();
		$response['page'] = $page;
		$response['total'] = $totalPages;
		$response['records'] = count($totalRecords);

		$projecttypeQuery = $em->createQuery('SELECT pt.id as id,pt.name as name,pt.color as color,pt.is_default as isdefault FROM Application\Entity\Projecttypes pt')
		->setFirstResult( $start )
		->setMaxResults( $limit );
		$totalrows = $projecttypeQuery->getResult();
		$i=0;
		$common=new Common();
		foreach ($totalrows as $rws){
			$name='';
			$color='';
			$isdefault='';
			
			$response['rows'][$i]['id'] = $rws['id'];
			$response['rows'][$i]['cell']=array(ucwords($rws['name']),ucwords($rws['color']),$rws['isdefault']);
			$i++;
		}
		header("Content-type: application/json");
		echo json_encode($response);
		exit;
	}
	public function addprojecttypeAction(){
		
		if($this->getRequest()->isPost()){
			$em=$this->getEntityManager();
			$flag=$this->getRequest()->getPost('flag');
			$id=$this->getRequest()->getPost('id');
		
			
			if($flag!="view"){
			
				$response=array();		
				$name=$this->getRequest()->getPost('name');
				
				$color=$this->getRequest()->getPost('color');
				
				$isdefault=$this->getRequest()->getPost('isdefault');

				if($name==""){
					$response['data']['name']="null";
				}else {
					$response['data']['name']="valid";
				}
				if($color==""){
					$response['data']['color']="null";
				}else {
					$response['data']['color']="valid";
				}
				if($isdefault==""){
					$response['data']['isdefault']="null";
				}else {
					$response['data']['isdefault']="valid";
				}
				
				if(isset($response['data']) && (in_array("null", $response['data']) || in_array("invalid", $response['data']))){
					$response['returnvalue']="invalid";
				}
				else{
					$response=array();
				}
				if(count($response)==0){					
					$newRecord=new \stdClass();
					if($id!="" && $id>0){
						$newRecord=$em->find('Application\Entity\Projecttypes', $id);
					}
					else{
						$newRecord=new Projecttypes();
					}
					
					$newRecord->setName($name);
					$newRecord->setColor($color);
					$newRecord->setIs_default($isdefault);
							
					try{
						$em->persist($newRecord);
						$em->flush();
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
				$valuesToSend=array();
				if(isset($id) && $id>0){
					$projecttype=$em->find('Application\Entity\Projecttypes',$id);
				
					$valuesToSend['projecttype']=$projecttype;
				}
				$viewModel=new ViewModel($valuesToSend);
				$viewModel->setTerminal(true);
				return $viewModel;
			}
		}
		
	}
	public function deleteprojecttypeAction(){
		
		if($this->getRequest()->isPost()){
			$id=$this->getRequest()->getPost('id');
			$em=$this->getEntityManager();
			$delete =$em->find('Application\Entity\Projecttypes', $id);
			if ($delete) {
				$this->getEntityManager()->remove($delete);
				$this->getEntityManager()->flush();
			}
		}exit;
	}

	
}

