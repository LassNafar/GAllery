<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Application\Entity\Author;

/**
 * Этот класс представляет собой альбом.
 * @ORM\Entity
 * @ORM\Table(name="albom")
 */
class Albom 
{
    /**
     * @ORM\ManyToMany(targetEntity="\Application\Entity\Author", inversedBy="alboms")
     * @ORM\JoinTable(name="albom_author",
     *      joinColumns={@ORM\JoinColumn(name="albom_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     *      )
     */
    protected $authors;
    
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

    /** 
     * @ORM\Column(name="image")  
     */
    protected $image;

    /** 
     * @ORM\Column(name="priority")  
     */
    protected $priority;
    
    // Конструктор.
    public function __construct() 
    {
        $this->authors = new ArrayCollection();        
    }

    // Возвращает ID данного альбома.
    public function getId() 
    {
        return $this->id;
    }

    // Задает ID данного альбома.
    public function setId($id) 
    {
        $this->id = $id;
    }

    // Возвращает имя альбома.
    public function getName() 
    {
        return $this->name;
    }

    // Задает имя альбома.
    public function setName($name) 
    {
        $this->name = $name;
    }

    // Возвращает путь к картинке.
    public function getImage() 
    {
        return $this->image;
    }

    // Устанавливает путь к картинке.
    public function setImage($image) 
    {
        $this->image = $image;
    }
    
    // Возвращает приритет.
    public function getPriority() 
    {
        return $this->priority; 
    }
    
    // Задает приритет.
    public function setPriority($priority) 
    {
        $this->priority = $priority;
    }
    

    // Возвращает авторов для данного альбома.
    public function getAuthors() 
    {
        return $this->authors;
    }      
    
    // Добавляет нового автора к данному альбому.
    public function addAuthor($author) 
    {
        $this->authors[] = $author;        
    }
    
    // Удаляет связь между этим альбомом и заданным автором.
    public function removeTagAssociation($author) 
    {
        $this->authors->removeElement($author);
    }
}