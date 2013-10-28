<?php 
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Entities;
use Application\Entity\Projects;
class ProjectsRepository extends EntityRepository
{
    public function getProjectsByName($orderby="ASC",$id=0){
    	$em=$this->getEntityManager();
    	  $Projects =$em->createQuery("SELECT p FROM Application\Entity\Projects p  Order by p.name $orderby")->getArrayResult();
        return $Projects;
    }
    
    public function getProjectsById($id=0){
    	if ($id!=0 && $id>0){
    		$where="p.id=$id";
    	}else{
    		$where="1=1";
    	}
    	$em=$this->getEntityManager();
    	$Projects =$em->createQuery("SELECT p FROM Application\Entity\Projects p where $where")->getArrayResult();
    	return $Projects;
    	 
    }
}