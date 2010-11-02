<?php

class Blog_IndexController extends Zend_Controller_Action
{

    public function init()
    {

    }

    public function indexAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        //$postDao->insertDummyPosts(10);
        $this->view->posts = $postDao->getAllPosts();
    }

}

