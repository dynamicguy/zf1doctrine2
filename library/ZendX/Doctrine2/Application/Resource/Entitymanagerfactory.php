<?php

/**
 * Doctrine2 application resource
 *
 * @example
 * <pre>
 *      resources.entitymanagerfactory.lazyLoad = false
 *      resources.entitymanagerfactory.cache = "Doctrine\Common\Cache\ArrayCache"
 *      resources.entitymanagerfactory.metadata.classDirectory = APPLICATION_PATH "/models/"
 *      resources.entitymanagerfactory.metadata.driver = "annotation"
 *      resources.entitymanagerfactory.proxyDir = APPLICATION_PATH "/proxies/"
 *      resources.entitymanagerfactory.proxyNamespace = "Application_Proxies"
 *      resources.entitymanagerfactory.autoGenerateProxyClasses = true
 *      resources.entitymanagerfactory.connectionOptions.driver = "pdo_sqlite"
 *      resources.entitymanagerfactory.connectionOptions.path = APPLICATION_PATH "/../database/database.sqlite"
 * </pre>
 *
 * @uses       Zend_Application_Resource_ResourceAbstract
 * @category   ZendX
 * @package    ZendX_Application
 * @subpackage Resource
 * @author     Giorgio Sironi <NakedPhp>
 * @author     Nurul Ferdous <nurul.ferdous@gmail.com>
 */
class ZendX_Doctrine2_Application_Resource_Entitymanagerfactory extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Create Entity Manager or Factory
     *
     * @return ZendX_Doctrine2_EntityManagerFactory
     */
    public function init()
    {
        $options = $this->getOptions();

        $emf = new ZendX_Doctrine2_EntityManagerFactory($options);
        $emf->registerAutoload();

        if (isset($options['lazyLoad']) && $options['lazyLoad'] == true) {
            return $emf;
        } else {
            return $emf->createEntityManager();
        }
    }

}

/**
 * Entity Manager Factory for Moduler/Non-Moduler Entity Manager Creation
 *
 * @category   ZendX
 * @package    ZendX_Application
 * @author     Nurul Ferdous <nurul.ferdous@gmail.com>
 */
class ZendX_Doctrine2_EntityManagerFactory
{
    /**
     * @var array
     */
    protected $_options = array();

    /**
     * @var bool
     */
    protected $_registeredAutoloader = false;
    private static $_metadataCacheImpl = null;
    private static $_queryCacheImpl = null;

    /**
     * @param array|Zend_Config $options
     */
    public function __construct($options)
    {
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        } else if (!is_array($options)) {
            throw new ZendX_Doctrine2_Exception("Invalid Options for EntityManager Factory");
        }
        $this->_options = $options;
    }

    /**
     * add metadata path
     *
     * @param string $path
     */
    public function addMetadataPath($path)
    {
        $this->_options['metadata']['paths'][] = $path;
        return $this;
    }

    /**
     * Create the entity manager instance.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function createEntityManager()
    {
        return $this->_createEntityManager();
    }

    /**
     * @param array $paths
     * @return \Doctrine\ORM\Mapping\Driver\AnnotationDriver
     */
    protected function createAnnotationDriver($paths = array(), $alias = null)
    {
        if (version_compare(\Doctrine\Common\Version::VERSION, '3.0.0', '>=')) {
            $reader = new \Doctrine\Common\Annotations\CachedReader(
                            new \Doctrine\Common\Annotations\AnnotationReader(), new ArrayCache()
            );
        } else if (version_compare(\Doctrine\Common\Version::VERSION, '2.2.0-DEV', '>=')) {
            // Register the ORM Annotations in the AnnotationRegistry
            $reader = new \Doctrine\Common\Annotations\SimpleAnnotationReader();
            $reader->addNamespace('Doctrine\ORM\Mapping');
            $reader = new \Doctrine\Common\Annotations\CachedReader($reader, new ArrayCache());
        } else if (version_compare(\Doctrine\Common\Version::VERSION, '2.1.0-BETA3-DEV', '>=')) {
            $reader = new \Doctrine\Common\Annotations\AnnotationReader();
            $reader->setIgnoreNotImportedAnnotations(true);
            $reader->setEnableParsePhpImports(false);
            if ($alias) {
                $reader->setAnnotationNamespaceAlias('Doctrine\ORM\Mapping\\', $alias);
            } else {
                $reader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
            }
            $reader = new \Doctrine\Common\Annotations\CachedReader(
                            new \Doctrine\Common\Annotations\IndexedReader($reader), new ArrayCache()
            );
        } else {
            $reader = new \Doctrine\Common\Annotations\AnnotationReader();
            if ($alias) {
                $reader->setAnnotationNamespaceAlias('Doctrine\ORM\Mapping\\', $alias);
            } else {
                $reader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
            }
        }
        \Doctrine\Common\Annotations\AnnotationRegistry::registerFile("Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php");
        return new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader, (array) $paths);
    }

    /**
     * Creates an EntityManager for testing purposes.
     *
     * NOTE: The created EntityManager will have its dependant DBAL parts completely
     * mocked out using a DriverMock, ConnectionMock, etc. These mocks can then
     * be configured in the tests to simulate the DBAL behavior that is desired
     * for a particular test,
     *
     * @return Doctrine\ORM\EntityManager
     */
    protected function _getEntityManager($conn = null, $conf = null, $eventManager = null, $withSharedMetadata = true)
    {
        $metadataCache = $withSharedMetadata ? self::getSharedMetadataCacheImpl() : new \Doctrine\Common\Cache\ArrayCache;

        $config = new \Doctrine\ORM\Configuration();

        $config->setMetadataCacheImpl($metadataCache);
        $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver());
        $config->setQueryCacheImpl(self::getSharedQueryCacheImpl());
        $config->setProxyDir(__DIR__ . '/Proxies');
        $config->setProxyNamespace('Doctrine\Tests\Proxies');

        if ($conn === null) {
            $conn = array(
                'driverClass' => 'Doctrine\Tests\Mocks\DriverMock',
                'wrapperClass' => 'Doctrine\Tests\Mocks\ConnectionMock',
                'user' => 'john',
                'password' => 'wayne'
            );
        }

        if (is_array($conn)) {
            $conn = \Doctrine\DBAL\DriverManager::getConnection($conn, $config, $eventManager);
        }

        return \Doctrine\Tests\Mocks\EntityManagerMock::create($conn, $config, $eventManager);
    }

    private static function getSharedMetadataCacheImpl()
    {
        if (self::$_metadataCacheImpl === null) {
            self::$_metadataCacheImpl = new \Doctrine\Common\Cache\ArrayCache;
        }

        return self::$_metadataCacheImpl;
    }

    private static function getSharedQueryCacheImpl()
    {
        if (self::$_queryCacheImpl === null) {
            self::$_queryCacheImpl = new \Doctrine\Common\Cache\ArrayCache;
        }

        return self::$_queryCacheImpl;
    }

    /**
     * Setup the metadata driver if necessary options are set. Otherwise Doctrine defaults are used (AnnotationReader).
     *
     * @param array $options
     * @param Doctrine\ORM\Configuration $config
     * @param Doctrine\Common\Cache\AbstractCache $cache
     * @param Doctrine\DBAL\Connection $conn
     */
    protected function _setupMetadataDriver($options, $config, $cache, $conn)
    {
        $driver = false;

        if (isset($options['metadata'])) {
            if (isset($options['metadata']['driver'])) {
                $driverName = $options['metadata']['driver'];
                switch (strtolower($driverName)) {
                    case 'annotation':
                        $driverName = 'Doctrine\ORM\Mapping\Driver\AnnotationDriver';
                        break;
                    case 'yaml':
                        $driverName = 'Doctrine\ORM\Mapping\Driver\YamlDriver';
                        break;
                    case 'xml':
                        $driverName = 'Doctrine\ORM\Mapping\Driver\XmlDriver';
                        break;
                    case 'php':
                        $driverName = 'Doctrine\ORM\Mapping\Driver\PhpDriver';
                        break;
                    case 'database':
                        $driverName = 'Doctrine\ORM\Mapping\Driver\DatabaseDriver';
                        break;
                }

                if (!class_exists($driverName)) {
                    throw new ZendX_Doctrine2_Exception("MetadataDriver class '" . $driverName . "' does not exist");
                }

                if (in_array('Doctrine\ORM\Mapping\Driver\AbstractFileDriver', class_parents($driverName))) {
                    if (!isset($options['metadata']['paths'])) {
                        throw new ZendX_Doctrine2_Exception("Metadata Driver is file based, but no config file paths were given.");
                    }
                    if (!isset($options['metadata']['mode'])) {
                        $options['metadata']['mode'] = \Doctrine\ORM\Mapping\Driver\AbstractFileDriver::FILE_PER_CLASS;
                    }
                    $driver = new $driverName($options['metadata']['paths'], $options['metadata']['mode']);
                } elseif ($driverName == 'Doctrine\ORM\Mapping\Driver\AnnotationDriver') {
//                    $reader = new \Doctrine\Common\Annotations\AnnotationReader($cache);
//                    $reader->setDefaultAnnotationNamespace('Doctrine\ORM\Mapping\\');
//                    $driver = new \Doctrine\ORM\Mapping\Driver\AnnotationDriver($reader);

                    $reader = new \Doctrine\Common\Annotations\SimpleAnnotationReader();
                    $reader->addNamespace('Doctrine\ORM\Mapping');
                    $driver = new \Doctrine\Common\Annotations\CachedReader($reader, $cache);

                    if (isset($options['metadata']['classDirectory'])) {
                        $driver->addPaths(array($options['metadata']['classDirectory']));
                    } else {
                        throw new ZendX_Doctrine2_Exception("Doctrine Annotation Driver requires to set a class directory for the entities.");
                    }
                } elseif ($driverName == 'Doctrine\ORM\Mapping\Driver\DatabaseDriver') {
                    $schemaManager = $conn->getSchemaManager();
                    $driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver($schemaManager);
                }

                if (!($driver instanceof \Doctrine\ORM\Mapping\Driver\Driver)) {
                    throw new ZendX_Doctrine2_Exception("No metadata driver could be loaded.");
                }

                $config->setMetadataDriverImpl($driver);
            }
        }
    }

    /**
     * Create entity manager
     *
     * @param array $options
     * @return \Doctrine\ORM\EntityManager
     */
    protected function _createEntityManager()
    {
        $options = $this->_options;

        $cache = $this->_setupCache($options);

        if (!isset($options['proxyDir']) || !file_exists($options['proxyDir'])) {
            throw new ZendX_Doctrine2_Exception("No Doctrine2 'proxyDir' option was given, but is required.");
        }

        if (!isset($options['proxyNamespace'])) {
            $options['proxyNamespace'] = 'MyProject/Proxies';
        }

        if (!isset($options['autoGenerateProxyClasses'])) {
            $options['autoGenerateProxyClasses'] = true;
        }

        if (!isset($options['useCExtension'])) {
            $options['useCExtension'] = false;
        }

        $eventManager = new \Doctrine\Common\EventManager();

        $config = new \Doctrine\ORM\Configuration;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir($options['proxyDir']);
        $config->setProxyNamespace($options['proxyNamespace']);

        if (!isset($options['connectionOptions']) || !is_array($options['connectionOptions'])) {
            throw new ZendX_Doctrine2_Exception("Invalid Doctrine DBAL Connection Options given.");
        }
        $connectionOptions = $options['connectionOptions'];

        if (isset($options['sqllogger'])) {
            if (is_string($options['sqllogger']) && class_exists($options['sqllogger'])) {
                $logger = new $options['sqllogger']();
                if (!($logger instanceof \Doctrine\DBAL\Logging\SqlLogger)) {
                    throw new ZendX_Doctrine2_Exception("Invalid SqlLogger class specified, has to implement \Doctrine\DBAL\Logging\SqlLogger");
                }
                $config->setSqlLogger($logger);
            } else {
                throw new ZendX_Doctrine2_Exception("Invalid SqlLogger configuration specified, have to give class string.");
            }
        }

        $conn = \Doctrine\DBAL\DriverManager::getConnection($connectionOptions, $config, $eventManager);

        $this->_setupMetadataDriver($options, $config, $cache, $conn);

        $em = \Doctrine\ORM\EntityManager::create($conn, $config, $eventManager);

        return $em;
    }

    /**
     * Setup Cache Driver
     *
     * @param array $options
     * @return Doctrine\Common\Cache\Cache
     */
    protected function _setupCache(array $options)
    {
        if (!isset($options['cache'])) {
            throw new ZendX_Doctrine2_Exception("No Cache Class Implementation was given.");
        }
        if (!class_exists($options['cache'], true)) {
            throw new ZendX_Doctrine2_Exception("Given Cache Class '" . $options['cache'] . "' does not exist!");
        }
        $cache = new $options['cache'];
        return $cache;
    }

    /**
     * Register a Doctrine Autoloader with the SPL Autoload Stack.
     *
     * On consecutive calls this will do nothing.
     *
     * @return void
     */
    public function registerAutoload()
    {
        if ($this->_registeredAutoloader == false) {
            $config = $this->_options;
            if (isset($config['classLoader'])) {
                require_once $config['classLoader']['loaderFile'];
                $classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
                //$classLoader->setBasePath($config['classLoader']['loaderFile']);
                $classLoader->register(); // register on SPL autoload stack
            } else {
                // Assume Doctrine is somewhere in the Include Path
                require_once 'Doctrine/Common/ClassLoader.php';
                $classLoader = new \Doctrine\Common\ClassLoader('Doctrine');
                $classLoader->register(); // register on SPL autoload stack
            }
            $this->_registeredAutoloader = true;
        }
    }

    /**
     * Register Doctrine Class Loaders
     *
     * @param array $config Doctrine Class Loader configuration
     */
    private function registerClassLoaders(array $config = array())
    {
        $classLoaderClass = $config['loaderClass'];
        $classLoaderFile = $config['loaderFile'];

        require_once $classLoaderFile;

        foreach ($config['loaders'] as $loaderItem) {
            if (!isset($loaderItem['includePath'])) {
                $loaderItem['includePath'] = null;
            }

            $classLoader = new $classLoaderClass($loaderItem['namespace'], $loaderItem['includePath']);
            $classLoader->register();
        }
    }

}