<?php
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

return [
    'doctrine' => [        
        // настройка миграций
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/Migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table'     => 'migrations',
            ],
        ],
    ],
    'session_config' => [
        // Срок действия cookie сессии истечет через 1 час.
        'cookie_lifetime' => 60*60*1,     
        // Данные сессии будут храниться на сервере до 30 дней.
        'gc_maxlifetime'     => 60*60*24*30, 
    ],
    // Настройка менеджера сессий.
    'session_manager' => [
        // Валидаторы сессии (используются для безопасности).
        'validators' => [
            RemoteAddr::class,
            HttpUserAgent::class,
        ]
    ],
    // Настройка хранилища сессий.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
];
