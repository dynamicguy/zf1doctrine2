<?php

class blog_Form_Post extends Zend_Form
{

    protected $_post;

    public function __construct($options = null, $post = null)
    {
        parent::__construct($options);
        $this->_post = $post;
        $this->setName('post');
        $this->setAttrib('id', 'post');
        $this->setAttrib('class', 'basic');

        $this->_addPostFields();
        $this->_addSubmitButtons();
    }

    protected function _addPostFields()
    {
        $title = new Zend_Form_Element_Text('title');
        $content = new Zend_Form_Element_Textarea('content');

        $title->setLabel('Post title')
                        ->addFilters(array('StringTrim'))
                        ->setValue(($this->_post) ? $this->_post->getTitle() : '')
                        ->setAttrib('size', 65)
                        ->setRequired(true)
                ->class = 'txt';
        $content->setLabel('Content')
                        ->addFilters(array('StringTrim'))
                        ->setAttrib('rows', 10)
                        ->setAttrib('cols', 50)
                        ->setRequired(true)
                        ->setValue(($this->_post) ? $this->_post->getContent() : '')
                ->class = 'txt';

        $this->addElement($title);
        $this->addElement($content);
    }

    protected function _addSubmitButtons()
    {
        $submit = new Zend_Form_Element_Submit('submit_post');
        $cancel = new Zend_Form_Element_Button('cancel');

        $submit->setIgnore(true)
                ->setLabel('Save Info');

        $cancel->setIgnore(true)
                        ->setLabel('Cancel')
                ->class = 'button';

        $this->addElement($submit);
        $this->addElement($cancel);
    }

}

