<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Albom;

class IndexController extends AbstractActionController
{
    
    /**
     * Менеджер сущностей.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    // Метод конструктора, используемый для внедрения зависимостей в контроллер.
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
    // Действие по умолчанию "index". Оно отображает страницу 
    // содержащую альбомы в нужном порядке.
    public function indexAction() 
    {
        // Получаем альбомы в нужном порядке.
        $alboms = $this->entityManager->getRepository(Albom::class)
                     ->findBy(['priority'=> 'DESC'], 
                              ['id'=>'DESC']);
        
        // Визуализируем шаблон представления.
        return new ViewModel([
            'alboms' => $alboms
        ]);
    }
}
