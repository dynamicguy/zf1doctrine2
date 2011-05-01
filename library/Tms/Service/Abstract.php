<?php

abstract class Tms_Service_Abstract
{

    protected $_responseType = 'json';
    protected $_responseTypes = array(
        'atom',
        'json'
    );
    protected $_uri;
    protected $_client;
    protected $_username;
    protected $_password;

    abstract protected function initClient($username, $password);

    protected function setClient($username, $password)
    {
        $this->_username = $username;
        $this->_password = $password;
        $this->_client = new Zend_Rest_Client();
        $this->_client->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');
        $this->_client->setUri("http://ec2-184-73-37-226.compute-1.amazonaws.com");
        //$this->_client->setUri("http://127.0.0.1:8000");
        $this->_client->getHttpClient()->setAuth($this->_username, $this->_password);
        return $this;
    }

    protected function setResponseType($responseType = 'json')
    {
        if (!in_array($responseType, $this->_responseTypes, TRUE)) {
            throw new Tms_Service_Exception('Invalid Response Type');
        }
        $this->_responseType = $responseType;
        return $this;
    }

    protected function getResponseType()
    {
        return $this->_responseType;
    }

}