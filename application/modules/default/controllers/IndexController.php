<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        
    }

    public function indexAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $this->view->posts = $postDao->getAllPosts();
    }

}

