<?php

class blog_Form_Comment extends Zend_Form
{

    protected $_comment;

    public function __construct($options = null, $comment = null)
    {
        parent::__construct($options);

        $this->_comment = $comment;

        $this->setName('comment');
        $this->setAttrib('id', 'comment');
        $this->setAttrib('class', 'basic');

        $this->_addCommentFields();
        $this->_addSubmitButtons();
    }

    protected function _addCommentFields()
    {
        $author = new Zend_Form_Element_Text('author');
        $author_email = new Zend_Form_Element_Text('author_email');
        $author_url = new Zend_Form_Element_Text('author_url');
        $content = new Zend_Form_Element_Textarea('content');

        $author->setLabel('Your name')
                        ->addFilters(array('StringTrim'))
                        ->setValue(($this->_comment) ? $this->_comment->getAuthor() : '')
                        ->setAttrib('size', 65)
                        ->setRequired(true)
                ->class = 'txt';

        $author_email->setLabel('Email')
                        ->addFilters(array('StringTrim'))
                        ->setValue(($this->_comment) ? $this->_comment->getAuthorEmail() : '')
                        ->setAttrib('size', 65)
                        ->setRequired(true)
                        ->addValidator('EmailAddress')
                ->class = 'txt';

        $author_url->setLabel('Website')
                        ->addFilters(array('StringTrim'))
                        ->setValue(($this->_comment) ? $this->_comment->getAuthorUrl() : '')
                        ->setAttrib('size', 65)
                        ->addValidator('Hostname')
                ->class = 'txt';

        $content->setLabel('Content')
                        ->addFilters(array('StringTrim'))
                        ->setAttrib('rows', 10)
                        ->setAttrib('cols', 50)
                        ->setRequired(true)
                        ->setValue(($this->_comment) ? $this->_comment->getContent() : '')
                ->class = 'txt';

        $this->addElement($author);
        $this->addElement($author_email);
        $this->addElement($author_url);
        $this->addElement($content);
    }

    protected function _addSubmitButtons()
    {
        $submit = new Zend_Form_Element_Submit('submit_comment');
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

