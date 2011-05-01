<?php

class User_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->addFormElements();
    }
    
    public function addFormElements()
    {
        $this->setName("login");
        $this->setMethod('post');
        $this->setAttribs(array('class'=>'tms-form shadow rounded'));

        $this->addElement('text', 'username', array(
            'filters' => array('StringTrim', 'StringToLower'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Username:',
        ));

        $this->addElement('password', 'password', array(
            'filters' => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(0, 50)),
            ),
            'required' => true,
            'label' => 'Password:',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore' => true,
            'label' => 'Login',
        ));
    }

    
}

