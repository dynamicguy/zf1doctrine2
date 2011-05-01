<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        parent::init();
        if (!Zend_Auth::getInstance()->hasIdentity())
            $this->_redirect('/user/login');
        else {
            $this->view->user = Zend_Auth::getInstance()->getIdentity();
        }        
    }

    public function indexAction()
    {
        $this->view->headTitle()->prepend('Home');
        $postDao = new Blog_Model_Dao_Post();
        $this->view->posts = $postDao->getAllPosts();
        $api = new Tms_Service_Keyword('client', 'phish');
        $this->view->keywords = $api->getKeywords();
        
    }

}