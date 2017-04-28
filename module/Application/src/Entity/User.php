<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой зарегистрированного пользователя.
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User 
{
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="login")  
     */
    protected $login;
    
    /** 
     * @ORM\Column(name="name")  
     */
    protected $name;

    /** 
     * @ORM\Column(name="password")  
     */
    protected $password;
    
    /**
     * Возвращает ID пользователя.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Задает ID пользователя. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Возвращает логин.     
     * @return string
     */
    public function getLogin() 
    {
        return $this->login;
    }

    /**
     * Задает логин.     
     * @param string $login
     */
    public function setLogin($login) 
    {
        $this->login = $login;
    }
    
    /**
     * Возвращает имя.
     * @return string     
     */
    public function getName() 
    {
        return $this->name;
    }       

    /**
     * Задает имя.
     * @param string $name
     */
    public function setName($name) 
    {
        $this->name = $name;
    }
    
    /**
     * Возвращает пароль.
     * @return string
     */
    public function getPassword() 
    {
       return $this->password; 
    }
    
    /**
     * Задает пароль.     
     * @param string $password
     */
    public function setPassword($password) 
    {
        $this->password = $password;
    }
}
