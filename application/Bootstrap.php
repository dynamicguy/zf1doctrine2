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

        // set encoding and doctype
        $this->_view->setEncoding('UTF-8');
        $this->_view->doctype('XHTML1_STRICT');

        // set the content type and language
        $this->_view->headMeta()->appendHttpEquiv('Content-Type', 'text / html;charset=UTF-8');
        $this->_view->headMeta()->appendHttpEquiv('Content-Language', 'en-US');

        //$this->_view->headLink()->appendStylesheet('/css/reset.css');
        //$this->_view->headScript()->appendFile('/js/jquery-1.4.2.min.js');
        // setting the site in the title
        $this->_view->headTitle('phpXperts seminar 2010');
        $this->_view->headTitle()->setSeparator(' - ');
    }

}