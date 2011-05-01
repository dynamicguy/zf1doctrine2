<?php

final class Tms_Service_Domain extends Tms_Service_Abstract
{

    public function __construct($username, $password)
    {
        $this->initClient($username, $password);
    }

    protected function initClient($username, $password)
    {
        $this->setClient($username, $password);
    }

    public function getDomains(array $params = array())
    {
        $_query = array();

        foreach ($params as $key => $param) {
            $_query[$key] = $param;
        }
        $response = $this->_client->restGet('/api/domains.' . $this->_responseType, $_query);

        switch ($this->_responseType) {
            case 'json':
                return Zend_Json::decode($response->getBody());
                break;
            case 'atom':
                return Zend_Feed::importString($response->getBody());
                break;
        }

        return;
    }

}