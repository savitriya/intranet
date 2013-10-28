<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

require 'src/Application/Entity/DomainObject.php';
require_once getcwd().'/vendor/doctrine/common/lib/Doctrine/Common/ClassLoader.php';

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use \Doctrine\Common\Annotations\AnnotationReader;
use \Doctrine\Common\Annotations\AnnotationRegistry;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $classLoader = new \Doctrine\Common\ClassLoader('Entities', "src/Application/Entity/");
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Proxies',"src/Application/Entity/");
        $classLoader->register();
        $config = new \Doctrine\ORM\Configuration();
        $config->setProxyDir("src/Application/Entity");
        $config->setProxyNamespace('Proxies');
        $config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));
        $reader = new AnnotationReader();
        $driverImpl = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader,array("src/Application/Entity"));
        $config->setMetadataDriverImpl($driverImpl);
        
        // Caching Configuration (5)
        $cache = new \Doctrine\Common\Cache\ApcCache();
        /*
        if (APPLICATION_ENV == "development") {
        	$cache = new \Doctrine\Common\Cache\ArrayCache();
        } else {
        	$cache = new \Doctrine\Common\Cache\ApcCache();
        }*/
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
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
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
