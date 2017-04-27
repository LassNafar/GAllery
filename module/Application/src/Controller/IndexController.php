<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;
//use Application\Form\AlbomForm;
use Application\Entity\Albom;

class IndexController extends AbstractActionController
{
    
    /**
     * Менеджер сущностей.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Post manager.
     * @var Application\Service\PostManager 
     */
    private $albomManager;
    
    // Метод конструктора, используемый для внедрения зависимостей в контроллер.
    public function __construct($entityManager, $albomManager) 
    {
        $this->entityManager = $entityManager;
        $this->albomManager = $albomManager;
    }
    
    // Действие по умолчанию "index". Оно отображает страницу 
    // содержащую альбомы в нужном порядке.
    public function indexAction() 
    {
        $page = $this->params()->fromQuery('page', 1);
    
        // Получаем альбомы в нужном порядке.'priority'=> 'DESC'
        $query = $this->entityManager->getRepository(Albom::class)
                     ->findAlboms();
                          //->findBy([], 
                              //['priority'=> 'DESC','id'=>'DESC']);
        
        $adapter = new DoctrineAdapter(new ORMPaginator($query, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);        
        $paginator->setCurrentPageNumber($page);
        
        // Визуализируем шаблон представления.
        return new ViewModel([
            'alboms' => $paginator,
            'albomManager' => $this->albomManager
        ]);
    }
}
