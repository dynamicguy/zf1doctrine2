<?php

/**
 * @Entity
 * @Table(name="users")
 */
class User_Model_User
{

    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ManyToMany(targetEntity="User_Model_Group")
     * @JoinTable(name="user_group",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    /**
     * @Column(unique=true, nullable=false, length=50)
     */
    protected $username;
    /**
     * @Column(unique=true, nullable=true)
     */
    protected $email;
    /**
     * @Column(nullable=true, type="boolean")
     */
    protected $enabled;
    /**
     * @Column(nullable=true, type="string")
     */
    protected $algorithm;
    /**
     * @Column(nullable=true, type="string", length=40)
     */
    protected $salt;
    /**
     * @Column(nullable=true, type="string", length=128)
     */
    protected $password;
    /**
     * @Column(nullable=true, type="datetime")
     */
    protected $createdAt;
    /**
     * @Column(nullable=true, type="datetime")
     */
    protected $updatedAt;
    /**
     * @Column(nullable=true, type="datetime")
     */
    protected $lastLogin;
    /**
     * @Column(nullable=true, type="string", length=50)
     */
    protected $confirmationToken;

    public function getId()     {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getGroups()
    {
        return $this->groups;
    }

    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    public function setAlgorithm($algorithm)
    {
        $this->algorithm = $algorithm;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
    }

    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }


}
