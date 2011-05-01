<?php

/**
 * @Entity
 * @Table(name="groups")
 */
class User_Model_Group
{

    /**
     * @Id
     * @Column(type="integer")
     * @generatedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @Column(unique=true, nullable=true)
     */
    protected $name;
    
    public function getId()     {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
    
}