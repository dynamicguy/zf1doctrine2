<?php

class Blog_PostController extends Zend_Controller_Action
{

    public function indexAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $this->view->posts = $postDao->getAllPosts();
    }

    public function viewAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $this->view->post = $postDao->getPostBy($this->_getParam('id', false));
        $this->view->commentForm = new Blog_Form_Comment();

        if ($this->getRequest()->isPost()) {
            if ($this->view->commentForm->isValid($_POST)) {
                $data = $this->view->commentForm->getValues();
                $data['ip'] = $this->getRequest()->getServer('REMOTE_ADDR');
                $postDao->addComment($data, $this->_getParam('id', false));
            }
        }
    }

    public function editAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $this->view->post = $postDao->getPostBy($this->_getParam('id', false));
        $this->view->postForm = new Blog_Form_Post(NULL, $this->view->post);

        if ($this->getRequest()->isPost()) {
            if ($this->view->postForm->isValid($_POST)) {
                $postDao->savePost($this->view->postForm->getValues(), $this->_getParam('id', false));
                $this->_redirect('/blog/post');
            }
        }
    }

    public function deleteAction()
    {
        $postDao = new Blog_Model_Dao_Post();
        $postDao->deletePostBy($this->_getParam('id', false));
        $this->_redirect('/blog/post');
    }

    public function createAction()
    {
        $this->view->postForm = new Blog_Form_Post(NULL, $this->view->post);
        if ($this->getRequest()->isPost()) {
            if ($this->view->postForm->isValid($_POST)) {
                $postDao = new Blog_Model_Dao_Post();
                $postDao->savePost($this->view->postForm->getValues());
                $this->_redirect('/blog/post');
            }
        }
    }

}