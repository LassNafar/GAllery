<?php
namespace Application\Service;

use Application\Entity\Albom;
use Application\Entity\Author;
use Zend\Filter\StaticFilter;

// Сервис The AlbomManager, отвечающий за добавление новых альбомов.
class AlbomManager 
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
  
    // Конструктор, используемый для внедрения зависимостей в сервис.
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    // Этот метод добавляет новый альбом.
    public function addNewAlbom($data) 
    {
        // Создаем новую сущность Albom.
        $albom = new Albom();
        $albom->setName($data['name']);
        $albom->setImage($data['image']);
        $albom->setPriority($data['priority']);      
        
        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($albom);
        
        // Добавляем теги к посту.
        $this->addAuthorsToAlbom($data['authors'], $albom);
        
        // Применяем изменения к базе данных.
        $this->entityManager->flush();
    }
  
    // Добавляет/обновляет авторов в заданном альбоме.
    private function addAuthorsToAlbom($authorsStr, $albom) 
    {
        // Удаляем связи авторов (если таковые есть)
        $authors = $albom->getAuthors();
        foreach ($authors as $author) {            
            $albom->removeAuthorAssociation($author);
        }
        
        // Добавляем авторов к альбому
        $authors = explode(',', $authorsStr);
        foreach ($authors as $authorName) {
            
            $authorName = StaticFilter::execute($authorName, 'StringTrim');
            if (empty($authorName)) {
                continue; 
            }
            
            $author = $this->entityManager->getRepository(Author::class)
                      ->findOneByName($authorName);
            if ($author == null)
                $author = new Author();
            $author->setName($authorName);
            $author->addAlbom($albom);
            
            $this->entityManager->persist($author);
            
            $albom->addAuthor($author);
        }
    }    
}