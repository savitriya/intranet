<?php
namespace Application;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

class loggedInAs extends AbstractHelper{
	
	public function __invoke()
	{
		$auth=new AuthenticationService();
		if($auth->hasIdentity()){
			return $auth->getIdentity()->name;
		}
		return "";
	}
	
}