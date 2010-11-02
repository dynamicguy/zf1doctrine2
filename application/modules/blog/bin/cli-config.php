<?php

// loading and parsing module config
require_once './parser.php';
$ini = Ini_Struct::parse('../configs/module.ini');

/**
 * @todo set library path
 * @todo find and replace your current module name. such as "blog"
 */
if (!defined('LIB_PATH'))
    define('LIB_PATH', '/home/dynamicguy/public_html/zf1doctrine2/library');

require_once LIB_PATH . '/Doctrine/Common/ClassLoader.php';

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
$cache = new $ini['blog']['resources']['entitymanagerfactory']['cache'];

$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);

$driverImpl = $config->newDefaultAnnotationDriver(array(realpath(__DIR__ . "/../models")));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(realpath(__DIR__ . '/../proxies'));
$config->setProxyNamespace($ini['blog']['resources']['entitymanagerfactory']['proxyNamespace']);

$config->setAutoGenerateProxyClasses($ini['blog']['resources']['entitymanagerfactory']['autoGenerateProxyClasses']);

$connectionOptions = $ini['blog']['resources']['entitymanagerfactory']['connectionOptions'];

$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);