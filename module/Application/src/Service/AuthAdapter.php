<?php
namespace Application\Service;

use Zend\Authentication\Adapter\AdapterInterface;
use Zend\Authentication\Result;
use Zend\Crypt\Password\Bcrypt;
use Application\Entity\User;

/**
 * Это адаптер, используемый для аутентификации пользователя. Он принимает логин
 * и пароль, и затем проверяет, есть ли в базе данных пользователь с такими учетными данными.
 * Если такой пользователь существует, сервис возвращает его личность (логин). Личность
 * сохраняется в сессии и может быть извлечена позже.
 */
class AuthAdapter implements AdapterInterface
{
    /**
     * Логин пользователя.
     * @var string 
     */
    private $login;
    
    /**
     * Пароль.
     * @var string 
     */
    private $password;
    
    /**
     * Менеджер сущностей.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
        
    /**
     * Конструктор.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * Задает логин пользователя.     
     */
    public function setLogin($login) 
    {
        $this->login = $login;        
    }
    
    /**
     * Устанавливает пароль.     
     */
    public function setPassword($password) 
    {
        $this->password = (string)$password;        
    }
    
    /**
     * Выполняет попытку аутентификации.
     */
    public function authenticate()
    {                
        // Проверяем, есть ли в базе данных пользователь с таким логином.
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByLogin($this->login);

        // Если такого пользователя нет, возвращаем статус 'Identity Not Found'.
        if ($user == null) {
            return new Result(
                Result::FAILURE_IDENTITY_NOT_FOUND, 
                null, 
                ['Invalid credentials.']);        
        }
        
        // Теперь необходимо вычислить хэш на основе введенного пользователем пароля и сравнить его
        // с хэшем пароля из базы данных.
        $bcrypt = new Bcrypt();
        $passwordHash = $user->getPassword();
        
        if ($bcrypt->verify($this->password, $passwordHash)) {
            // Отлично! Хэши паролей совпадают. Возвращаем личность пользователя для
            // хранения в сессии с целью последующего использования.
            return new Result(
                    Result::SUCCESS, 
                    $this->login, 
                    ['Authenticated successfully.']);        
        }             
        
        // Если пароль не прошел проверку, возвращаем статус ошибки 'Invalid Credential'.
        return new Result(
                Result::FAILURE_CREDENTIAL_INVALID, 
                null, 
                ['Invalid credentials.']);        
    }
}
