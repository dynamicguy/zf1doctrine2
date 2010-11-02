<?php

class Blog_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $this->view->posts = $postDao->getAllPosts();
    }

    public function setupAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $postDao->insertDummyPosts(20);
        $this->_redirect('/');
    }

}