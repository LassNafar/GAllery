<?php
namespace Application;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Application\Controller\AuthController;
use Application\Service\AuthManager;

class Module
{
    /**
     * Этот метод возвращает путь к файлу module.config.php file.
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
     * Этот метод вызывается после завершения самозагрузки MVC и позволяет
     * регистрировать обработчики событий. 
     */
    public function onBootstrap(MvcEvent $event)
    {
        // Получаем менеджер событий.
        $eventManager = $event->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Регистрируем метод-обработчик. 
        $sharedEventManager->attach(AbstractActionController::class, 
                MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);
    }
    
    /**
     * Метод-обработчик для события 'Dispatch'. Мы обрабатываем событие Dispatch
     * для вызова фильтра доступа. Фильтр доступа позволяет определить,
     * может ли пользователь просматривать страницу. Если пользователь не
     * авторизован, и у него нет прав для просмотра, мы перенаправляем его 
     * на страницу входа на сайт.
     */
    public function onDispatch(MvcEvent $event)
    {
        // Получаем контроллер и действие, которому был отправлен HTTP-запрос.
        $controller = $event->getTarget();
        $controllerName = $event->getRouteMatch()->getParam('controller', null);
        $actionName = $event->getRouteMatch()->getParam('action', null);
        
        // Конвертируем имя действия с пунктирами в имя в верблюжьем регистре.
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        
        // Получаем экземпляр сервиса AuthManager.
        $authManager = $event->getApplication()->getServiceManager()->get(AuthManager::class);
        
        // Выполняем фильтр доступа для каждого контроллера кроме AuthController
        // (чтобы избежать бесконечного перенаправления).
        if ($controllerName!=AuthController::class && 
            !$authManager->filterAccess($controllerName, $actionName)) {
            
            // Запоминаем URL страницы, к которой пытался обратиться пользователь. Мы перенаправим пользователя
            // на этот URL после успешной авторизации.
            $uri = $event->getApplication()->getRequest()->getUri();
            // Делаем URL относительным (убираем схему, информацию о пользователе, имя хоста и порт),
            // чтобы избежать перенаправления на другой домен недоброжелателем.
            $uri->setScheme(null)
                ->setHost(null)
                ->setPort(null)
                ->setUserInfo(null);
            $redirectUrl = $uri->toString();
            
            // Перенаправляем пользователя на страницу "Login".
            return $controller->redirect()->toRoute('login', [], 
                    ['query'=>['redirectUrl'=>$redirectUrl]]);
        }
    }
}