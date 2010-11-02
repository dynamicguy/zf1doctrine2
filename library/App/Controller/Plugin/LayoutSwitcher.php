<?php

class App_Controller_Plugin_LayoutSwitcher extends Zend_Layout_Controller_Plugin_Layout
{

    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->getLayout()->setLayoutPath(
                Zend_Controller_Front::getInstance()->getModuleDirectory(
                        $request->getModuleName()
                ) . '/layouts/scripts'
        );
        $this->getLayout()->setLayout('layout');
    }

}