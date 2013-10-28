<?php
//require_once '../../lib/vendor/doctrine-common/lib/Doctrine/Common/ClassLoader.php';
//require_once getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';
require 'Doctrine/ORM/Tools/Setup.php';

require '../module/Application/src/Application/Entity/DomainObject.php';
use \Doctrine\Common\Annotations\AnnotationReader;
use \Doctrine\Common\Annotations\AnnotationRegistry;

// Setup Autoloader (1)
Doctrine\ORM\Tools\Setup::registerAutoloadPEAR();

// Define application environment
define('APPLICATION_ENV', "development");

	
$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__."/../module/Application/src/Application/Entity/");
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__."/../module/Application/src/Application/Entity/");
$classLoader->register();

// configuration (2)
$config = new \Doctrine\ORM\Configuration();

// Proxies (3)
$config->setProxyDir(__DIR__."/../module/Application/src/Application/Entity");
$config->setProxyNamespace('Proxies');

$config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));

//$driverImpl=$config->newDefaultAnnotationDriver(array(__DIR__."/../module/Application/src/Application/Entity"));

AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
$reader = new AnnotationReader();
$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader,array(__DIR__."/../module/Application/src/Application/Entity"));
$config->setMetadataDriverImpl($driverImpl);
	

// Caching Configuration (5)
if (APPLICATION_ENV == "development") {
	//$cache = new \Doctrine\Common\Cache\ArrayCache();
	$cache = new \Doctrine\Common\Cache\ApcCache();
} else {
	$cache = new \Doctrine\Common\Cache\ApcCache();
}
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

/*$connectionOptions = array(
		'driver' => 'pdo_sqlite',
		'path'   => __DIR__."/../data/mydb.sqlite");
*/
$connectionOptions = array(
		'driver' => 'pdo_mysql',
		'host'     => 'localhost',
		'port'     => '3306',
        'user'     => 'root',
		'password' => 'mysqlpim',
		'dbname'   => 'intranet',);
$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
		'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
		'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
/*


use \Doctrine\Common\Annotations\AnnotationReader;
use \Doctrine\Common\Annotations\AnnotationRegistry;

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/../../lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/../../lib/vendor/doctrine-dbal/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', realpath(__DIR__ . '/../../lib/vendor/doctrine-common/lib'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', realpath(__DIR__ . '/../../lib/vendor'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', getcwd()."/module/Application/src/Application/Entity");
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();
*/
/*
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$driverImpl = $config->newDefaultAnnotationDriver(array(getcwd()."/module/Application/src/Application/Entity"));
$config->setMetadataDriverImpl($driverImpl);
*/
/*
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
$reader = new AnnotationReader();
$driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, array(__DIR__ . "/persistent/"));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');

$connectionOptions = array(
    'driver' => 'pdo_sqlite',
    'path' => getcwd().'/data/mydb.sqlite'
);

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
));
*/
