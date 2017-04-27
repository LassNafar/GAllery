<?php
namespace Application\Service;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;
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
        $albom->setImage($data['image']['name']);
        if (empty($data['priority']))
            $albom->setPriority('0');      
        else
            $albom->setPriority($data['priority']);
        
        // Добавляем сущность в менеджер сущностей.
        $this->entityManager->persist($albom);
        
        // Добавляем авторов к посту.
        $this->addAuthorsToAlbom($data['authors'], $albom);
        
        // Применяем изменения к базе данных.
        $this->entityManager->flush();
    }

    // Этот метод позволяет обновлять данные одного поста.
    public function updateAlbom($albom, $data) 
    {
        $albom->setName($data['name']);
        if($data['image']['name']!="")
            $albom->setImage($data['image']['name']);
        $albom->setPriority($data['priority']);
        
        $this->entityManager->flush();
    }
        
    // Добавляет автора в заданном альбоме.
    public function addAuthorsToAlbom($authorName, $albom) 
    {
            $authorName = StaticFilter::execute($authorName, 'StringTrim');
            if (!empty($authorName)) {
            
                $author = $this->entityManager->getRepository(Author::class)
                        ->findOneByName($authorName);
                if ($author == null)
                {
                    $author = new Author();
                    $author->setName($authorName);
                    $author->addAlbom($albom);

                    $this->entityManager->persist($author);

                    $albom->addAuthor($author);
                    
                    $this->entityManager->flush();
                }
            }
    }
    
    /**
     * Конвертируем список авторов в строку
     */
    public function convertAuthorsToString($albom) 
    {
        $authors = $albom->getAuthors();
        $authorCount = count($authors);
        $authorsStr = '';
        $i = 0;
        foreach ($authors as $author) {
            $i ++;
            $authorsStr .= $author->getName();
            if ($i < $authorCount) 
                $authorsStr .= ', ';
        }
        
        return $authorsStr;
    }   
}