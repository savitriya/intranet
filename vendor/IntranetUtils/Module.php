<?php
namespace IntranetUtils;

//require_once ZF2_PATH.'/Module/Consumer/AutoloaderProvider.php';



class Module 
{
	
	public function getAutoloaderConfig()
	{
		try{
			return array(
					'Zend\Loader\ClassMapAutoloader' => array(
							__DIR__ . '/autoload_classmap.php',
					)
			);
		}
		catch (Exception $e){
			echo $e->getMessage();exit;
		}
	}
}