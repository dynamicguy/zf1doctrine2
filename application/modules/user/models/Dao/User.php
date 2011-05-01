<?php

class User_Model_Dao_User
{
    const NOT_FOUND = 1;
    const WRONG_PW = 2;

    /**
     * @var instance of Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function __construct()
    {
        $this->_em = User_Api_Util_Bootstrap::getResource('Entitymanagerfactory');
    }

    /**
     * Perform authentication of a user
     * @param string $username
     * @param string $password
     */
    public function authenticate($username, $password)
    {
        //'SHA1(CONCAT(?,salt))'
        $user = $this->_em->getRepository('User_Model_User')->findOneBy(array('username' => $username));

        if ($user) {
            if ($user->getPassword() == self::_getSecret($password, $user->getSalt()))
                return $user;

            throw new Exception(self::NOT_FOUND);
        }
        throw new Exception(self::WRONG_PW);
    }
    
    private static function _getSecret($password, $salt)
    {
        //'SHA1(CONCAT(?,salt))'
        return sha1($password.$salt);
    }

}