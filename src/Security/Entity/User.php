<?php

namespace Security\Entity;


class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $password;
    private $email;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Message
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public function setFirstname($text)
    {
        $this->text = $text;
        return $this;
    }

   
        /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public function setLastname($text)
    {
        $this->text = $text;
        return $this;
    }
    
        /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public function setEmail($text)
    {
        $this->text = $text;
        return $this;
    }
    
        /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     * @return Message
     */
    public function setPassword($text)
    {
        $this->text = $text;
        return $this;
    }
}
