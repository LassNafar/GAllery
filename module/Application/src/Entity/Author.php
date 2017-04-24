<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Этот класс представляет собой тег.
 * @ORM\Entity
 * @ORM\Table(name="author")
 */
class Author 
{
    
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Albom", mappedBy="authors")
     */
    protected $alboms;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /** 
     * @ORM\Column(name="name") 
     */
    protected $name;

    // Конструктор.
    public function __construct() 
    {        
        $this->authors = new ArrayCollection();        
    }
    
    // Возвращает ID автора.
    public function getId() 
    {
        return $this->id;
    }

    // Задает ID автора.
    public function setId($id) 
    {
        $this->id = $id;
    }

    // Возвращает имя.
    public function getName() 
    {
        return $this->name;
    }

    // Задает имя.
    public function setName($name) 
    {
        $this->name = $name;
    }
    
    // Возвращает посты, связанные с данным тегом.
    public function getAlboms() 
    {
        return $this->alboms;
    }
    
    // Добавляет пост в коллекцию постов, связанных с этим тегом.
    public function addAlbom($albom) 
    {
        $this->alboms[] = $albom;        
    } 
}
