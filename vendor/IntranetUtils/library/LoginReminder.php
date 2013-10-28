<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
date_default_timezone_set('GMT');
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
chdir(dirname(__DIR__));

//
defined('ZF2_PATH')
|| define('ZF2_PATH', realpath(dirname(__FILE__) . '/../../vendor/zendframework/zendframework/library/Zend/'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
		realpath('/usr/share/php/Doctrine'),
		get_include_path(),
)));
// Setup autoloading
require __DIR__.'/../../../init_autoloader.php';

use	Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;
use Application\Entity\Login;
use Zend\ServiceManager;

$em=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
print_r($em);
echo "here";exit;