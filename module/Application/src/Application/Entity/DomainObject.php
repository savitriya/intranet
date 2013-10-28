<?php
namespace Application\Entity;

//require getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/NotifyPropertyChanged.php';
//require getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/PropertyChangedListener.php';
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use Application\Entity\Activitycategories;
use Application\Entity\User;
use Application\Entity\Activitystatuses;
use Zend\Authentication\AuthenticationService;
use Doctrine\Common\NotifyPropertyChanged,
    Doctrine\Common\PropertyChangedListener;
use IntranetUtils\Common;

abstract class DomainObject implements NotifyPropertyChanged
{
	/**
	 * @ORM\Column(type="PropertyChangedListener")
	 */
    private $_listeners = array();
    
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;
	
	protected $ahDescription = "";

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	
	public function getEntityManager()
	{
		
		return $this->em;
	}
	
	public function getDescription()
	{
		return $this->ahDescription;
	}

    /**
	 * Sets the Listner
	 *
	 * @param PropertyChangedListener $listener
	 * @access public
	 * @return void
	 */
    public function addPropertyChangedListener(PropertyChangedListener $listener) {
        $this->_listeners[] = $listener;
    }

    /** Notifies listeners of a change
	 *
	 * @param string $propName, string $oldValue, string $newValue
	 * @access public
	 * @return void
	 */
    protected function _onPropertyChanged($propName, $oldValue, $newValue) {
        if ($this->_listeners) {
        	$em = $this->getEntityManager();
        	$common = new Common();
            foreach ($this->_listeners as $listener) {
            	if($propName == 'category_id')
            	{
					$oldCatgQuery = $em->createQuery("SELECT ac.name FROM Application\Entity\Activitycategories ac where ac.id=$oldValue");
					$oldCatgName = $oldCatgQuery->getResult();
					$newCatgQuery = $em->createQuery("SELECT ac.name FROM Application\Entity\Activitycategories ac where ac.id=$newValue");
					$newCatgName = $newCatgQuery->getResult();
					$this->ahDescription .= "Category is changed from - ".$oldCatgName[0]['name']." to ".$newCatgName[0]['name'].".<br/>";
					
            	}
            	elseif($propName == 'assigned_to_id')
            	{
					$oldAtiQuery = $em->createQuery("SELECT u.fname, u.lname FROM Application\Entity\User u where u.id=$oldValue");
					$oldAtiName = $oldAtiQuery->getResult();
					$newAtiQuery = $em->createQuery("SELECT u.fname, u.lname FROM Application\Entity\User u where u.id=$newValue");
					$newAtiName = $newAtiQuery->getResult();
					$this->ahDescription .= " Assigned User is changed from - ".$oldAtiName[0]['fname']." ".$oldAtiName[0]['lname']." to ".$newAtiName[0]['fname']." ".$newAtiName[0]['lname'].".<br/>";
            	}
            	elseif($propName == 'status_id')
            	{
					$oldSidQuery = $em->createQuery("SELECT ast.name FROM Application\Entity\Activitystatuses ast where ast.id=$oldValue");
					$oldSid = $oldSidQuery->getResult();
					$newSidQuery = $em->createQuery("SELECT ast.name FROM Application\Entity\Activitystatuses ast where ast.id=$newValue");
					$newSid = $newSidQuery->getResult();
					$this->ahDescription .= " Status is changed from - ".$oldSid[0]['name']." to ".$newSid[0]['name'].".<br/>";
            	}
            	elseif($propName == 'subject')
            	{
            		$this->ahDescription .= " Subject is changed from - $oldValue to $newValue.<br/>";
            	}
            	elseif($propName == 'priority')
            	{
            		$this->ahDescription .= " Priority is changed from - $oldValue to $newValue.<br/>";
            	}
            	elseif($propName == 'due_date')
            	{
            		$this->ahDescription .= " Due Date is changed from - ".date("d/m/y",$oldValue)." to ".date("d/m/y",$newValue).".<br/>";
            	}
            	elseif($propName == 'estimated_hours')
            	{
          
            		$oldValue=$common->convertSpentTime($oldValue);
            		$newValue=$common->convertSpentTime($newValue);
            		
            		$this->ahDescription .= " Estimated Hours is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'name')
            	{
            		$this->ahDescription .= " Project Name is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'typeId')
            	{
            		$this->ahDescription .= " Type is changed from - ".$oldValue." to ".$newValue.".<br/>";
            	}
            	elseif($propName == 'project_estimated_hours')
            	{
            		$oldHrMin = $common->convertSpentTime($oldValue);
            		$newHrMin = $common->convertSpentTime($newValue);
            		$this->ahDescription .= " Estimated Hours is changed from - ".$oldHrMin." to ".$newHrMin.".<br/>";
            	}
            	$listener->propertyChanged($this, $propName, $oldValue, $newValue);
            }
        }
    }
}