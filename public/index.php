<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */

//phpinfo();exit;
date_default_timezone_set('GMT');
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);
chdir(dirname(__DIR__));

defined('APPLICATION_PATH')
|| define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));

defined('APPLICATION_ENV')
|| define('APPLICATION_ENV', "development");
//
defined('ZF2_PATH')
|| define('ZF2_PATH', realpath(dirname(__FILE__) . '/../vendor/zendframework/zendframework/library/Zend/'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
		realpath('/usr/share/php/Doctrine'),
		get_include_path(),
)));
// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
