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
use Zend\Soap\Client\Common;
use IntranetUtils\AuthAdapter as AuthAdapter;
use IntranetUtils\Common as Misc;
use Zend\Config\Reader\Ini;
use DoctrineModule\Authentication\Adapter as DoctrineAuthAdapter;
use Zend\Authentication\Result;
use Doctrine\ORM\Query\Expr;
use Application\Entity\Login;
use Application\Entity\Holiday;
use Application\Entity\Preferences;
use Zend\Authentication\Storage;
use Application\Entity\Projects;
use Application\Entity\Milestones;
use Zend\Authentication\Storage\Session as SessionStorage;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Application\Entity\Company;
use Application\Entity\Contact;
use IntranetUtils\Common as IntraCommon;




/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */


class ScriptController extends AbstractActionController
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
	
	
//action to add milestone by default name if project don't have milestone 	
	public function milestoneforallprojectAction(){
		
		$auth = new AuthenticationService();
		$em=$this->getEntityManager();
		if(!$auth->hasIdentity()){
			return $this->redirect()->toRoute('home');
		}
		if($auth->getIdentity()->isadmin!=1){
			return $this->redirect()->toRoute('home');
		}
		
		$milestones=$em->CreateQuery("Select m.id as id,m.project_id as pid from Application\Entity\Milestones m group by m.project_id")->getResult();
		$milestonesid='';
		if (count($milestones)>0){
			foreach ($milestones as $rws){
				if ($milestonesid==''){
				$milestonesid=$rws['pid'];
				}else{
					$milestonesid.=",".$rws['pid'];
				}
			}
		}
		if ($milestonesid==''){
			$project=$em->CreateQuery("Select p.status_id as status_id,p.id as pid,p.estimated_startdate as esd,p.estimated_enddate as eed,p.actual_startdate as asd,p.actual_enddate as aed from Application\Entity\Projects p ")->getResult();
		}else {
			$project=$em->CreateQuery("Select p.status_id as status_id,p.id as pid,p.estimated_startdate as esd,p.estimated_enddate as eed,p.actual_startdate as asd,p.actual_enddate as aed from Application\Entity\Projects p where p.id  not in ($milestonesid)")->getResult();
		}
		
		if (count($project)>0){
			foreach ($project as $rw){
				$addMilestone=new Milestones();
				$addMilestone->setProject_id($rw['pid']);
				$addMilestone->setName("default");
				$addMilestone->setEstimated_startdate($rw['esd']);
				$addMilestone->setEstimated_enddate($rw['eed']);
				$addMilestone->setActual_startdate($rw['asd']);
				$addMilestone->setActual_enddate($rw['aed']);
				$addMilestone->setStatus_id($rw['status_id']);
	     		$addMilestone->setIsdefault("1");
				$em->persist($addMilestone);
				$em->flush();
			}
		}
	}
	
	
}
?>