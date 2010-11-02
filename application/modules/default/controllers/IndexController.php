<?php

class Default_IndexController extends Zend_Controller_Action
{

    /*
     * @var instance of Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function init()
    {
        $this->_em = Default_Api_Util_Bootstrap::getResource('Entitymanagerfactory');
    }

    public function indexAction()
    {
        // create new default article
//        $defaultArticle = new Default_Model_Article();
//        $defaultArticle->setTitle('default article');
//        $this->_em->persist($defaultArticle);
//        $this->_em->flush();

        // retrieve a collection of articles
        $articles = $this->_em->getRepository('Default_Model_Article')->findAll();
        var_dump($articles);
//die;
//
//        // delete all default articles
//        $this->_em->remove($defaultArticle);
//        $this->_em->flush();
    }



    public function testAction()
    {
        $option = Default_Api_Util_Bootstrap::getOption('user');
        var_dump($option);
        exit;
    }


}

