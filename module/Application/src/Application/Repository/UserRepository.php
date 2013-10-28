<?php 
namespace Application\Repository;

use Doctrine\ORM\EntityRepository;
use Entities;
use Application\Entity\User;
use Application\Entity\Projects;
 
class UserRepository extends EntityRepository
{
    public function getUserByName($orderby="ASC",$Isactive="1"){
    	$em=$this->getEntityManager();
    	  
    	$users = $em->createQuery("SELECT u FROM Application\Entity\User u  Where u.isactive=$Isactive Order by u.fname $orderby")->getResult();
    	//$users = $em->createQuery("SELECT u FROM Application\Entity\User u  Where u.isactive=$Isactive group by u.company_id,u.id ")->getResult();
        return $users;
    }
    public function getUserGroupByCompany($orderby="ASC",$Isactive="1"){
    	$em=$this->getEntityManager();
    	 
    	//$users = $em->createQuery("SELECT u FROM Application\Entity\User u  Where u.isactive=$Isactive Order by u.fname $orderby")->getResult();
    	$users = $em->createQuery("SELECT u FROM Application\Entity\User u  Where u.isactive=$Isactive group by u.company_id,u.id ")->getResult();
    	return $users;
    }
    public function getUserById($userId){
    	$em=$this->getEntityManager();
    	$users = $em->createQuery("SELECT u FROM Application\Entity\User u  Where u.id=$userId")->getResult();
    	return $users;
    }
}