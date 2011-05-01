<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initAppAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => 'Application_',
                    'basePath' => dirname(__FILE__),
                ));
        $autoloader->addResourceType('api', 'api/', 'Api');
        return $autoloader;
    }

    protected function _initViewSettings()
    {
        $this->bootstrap('view');
        $this->_view = $this->getResource('view');
        
        ZendX_JQuery::enableView($this->_view);
        $this->_view->jQuery()->enable();
        $this->_view->jQuery()->setLocalPath('/js/jquery/jquery-1.5.2.min.js')
                ->setUiLocalPath('/js/jqueryui/jquery-ui-1.8.12.custom.min.js')
                ->addStyleSheet('/css/themes/flick/jquery-ui-1.8.12.custom.css');

        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');

        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text / html;charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');
        $this->_view->headTitle()->setSeparator(' - ');
        
        $this->_view->headLink()->prependStylesheet('/css/site.css')
                ->prependStylesheet('/css/form.css')
                ->prependStylesheet('/css/960.css')
                ->prependStylesheet('/css/reset.css');

        $this->_view->headTitle('TM Secure');
        $this->_view->headTitle()->setSeparator(' - ');
    }

    protected function _initZFDebug()
    {
        if (APPLICATION_ENV !== 'development')
            return;
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('ZFDebug');

        $options = array(
            'jquery_path' => '/js/jquery/jquery-1.5.2.min.js',
            'plugins' => array('Variables',
                'Html',
                'File' => array('base_path' => realpath(dirname(__FILE__) . '../../')),
                'Memory',
                'Time',
                'Registry',
                'Exception')
        );

        # Instantiate the database adapter and setup the plugin.
        # Alternatively just add the plugin like above and rely on the autodiscovery feature.
        if ($this->hasPluginResource('db')) {
            $this->bootstrap('db');
            $db = $this->getPluginResource('db')->getDbAdapter();
            $options['plugins']['Database']['adapter'] = $db;
        }

        # Setup the cache plugin
        if ($this->hasPluginResource('cache')) {
            $this->bootstrap('cache');
            $cache = $this - getPluginResource('cache')->getDbAdapter();
            $options['plugins']['Cache']['backend'] = $cache->getBackend();
        }

        $debug = new ZFDebug_Controller_Plugin_Debug($options);

        $this->bootstrap('frontController');
        $frontController = $this->getResource('frontController');
        $frontController->registerPlugin($debug);
    }

}