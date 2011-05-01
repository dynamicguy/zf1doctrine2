<?php

/**
 * @todo set library path
 * @todo find and replace your current module name. such as "default"
 */
if (!defined('LIB_PATH')){
    $path = realpath(__DIR__);    
    define('LIB_PATH', dirname(dirname(dirname(dirname($path)))).'/library');
}

require_once LIB_PATH . '/Doctrine/Common/ClassLoader.php';

// loading and parsing module config
require_once LIB_PATH.'/ZendX/Doctrine2/parser.php';
$ini = Ini_Struct::parse('../configs/module.ini');


$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', LIB_PATH);
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Symfony', LIB_PATH . '/Doctrine');
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Entities', realpath(__DIR__ . '../models'));
$classLoader->register();
$classLoader = new \Doctrine\Common\ClassLoader('Proxies', realpath(__DIR__ . '../proxies'));
$classLoader->register();

$config = new \Doctrine\ORM\Configuration();
$cache = new $ini['default']['resources']['entitymanagerfactory']['cache'];

$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);

$driverImpl = $config->newDefaultAnnotationDriver(array(realpath(__DIR__ . "/../models")));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(realpath(__DIR__ . '/../proxies'));
$config->setProxyNamespace($ini['default']['resources']['entitymanagerfactory']['proxyNamespace']);

$config->setAutoGenerateProxyClasses($ini['default']['resources']['entitymanagerfactory']['autoGenerateProxyClasses']);

$connectionOptions = $ini['default']['resources']['entitymanagerfactory']['connectionOptions'];

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);