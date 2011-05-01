<?php

class User_LoginController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $form = new User_Form_Login(NULL, $this->view->user);
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($request->getPost())) {
                $adapter = new User_Model_Auth_Adapter($this->_getParam('username'), $this->_getParam('password'));
                $result = Zend_Auth::getInstance()->authenticate($adapter);
                if (Zend_Auth::getInstance()->hasIdentity()) {
                    $this->_redirect('/');
                } else {
                    $this->view->error = implode(' ', $result->getMessages());
                }
            }
        }
        $this->view->loginForm = $form;
    }

}

