<?php
namespace Application\Service;

use Application\Entity\User;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * This service is responsible for adding/editing users
 * and changing user password.
 */
class UserManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * Constructs the service.
     */
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Этот метод добавляет нового пользователя.
     */
    public function addUser($data) 
    {
        // Не допускаем создание нескольих пользователей с одинаковыми логинами.
        if($this->checkUserExists($data['login'])) {
            throw new \Exception("User with login " . 
                        $data['$login'] . " already exists");
        }

        // Создаем новую сущность User.
        $user = new User();
        $user->setLogin($data['login']);
        $user->setName($data['name']);        

        // Зашифровываем пароль и храним его в зашифрованном состоянии.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);        
        $user->setPassword($passwordHash);     

        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($user);

        // Применяем изменения к базе данных.
        $this->entityManager->flush();

        return $user;
    }
    
    public function checkUserExists($login) {
        
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByLogin($login);
        
        return $user !== null;
    }
    
    /**
     * Проверяет, что заданный пароль является корректным..
     */
    public function validatePassword($user, $password) 
    {
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();

        if ($bcrypt->verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }
}