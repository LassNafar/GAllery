<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\AlbomManager;
use Application\Service\ImageManager;
use Application\Controller\AlbomController;

/**
 * Это фабрика для AlbomController. Ее целью является инстанцирование
 * контроллера.
 */
class AlbomControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                           $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $albomManager = $container->get(AlbomManager::class);
        $imageManager = $container->get(ImageManager::class);
        
        // Инстанцируем контроллер и внедряем зависимости
        return new AlbomController($entityManager, $albomManager, $imageManager);
    }
}
