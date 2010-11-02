<?php

if (!defined('LIB_PATH'))
    define('LIB_PATH', '/home/dynamicguy/public_html/phpxperts/library');

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
$cache = new \Doctrine\Common\Cache\ArrayCache();

$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);
$config->setResultCacheImpl($cache);

$driverImpl = $config->newDefaultAnnotationDriver(array(realpath(__DIR__ . "/../models")));
$config->setMetadataDriverImpl($driverImpl);

$config->setProxyDir(realpath(__DIR__ . '/../proxies'));
$config->setProxyNamespace('Proxies');

$config->setAutoGenerateProxyClasses(true);

//$connectionOptions = array(
//    'driver' => 'pdo_sqlite',
//    'path' => 'database.sqlite'
//);

$connectionOptions = array(
    'driver' => 'pdo_mysql', // postgresql driver
    'user' => 'root',
    'password' => 'f',
    'host' => 'localhost',
    'dbname' => 'phpxperts'
);



$em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

$helpers = array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
);