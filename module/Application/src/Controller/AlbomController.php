<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AlbomForm;
use Application\Entity\Albom;

class AlbomController extends AbstractActionController 
{
    /**
     * Менеджер сущностей.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager;
    
    /**
     * Менеджер постов.
     * @var Application\Service\AlbomManager 
     */
    private $albomManager;
    
    /**
     * Конструктор, используемый для внедрения зависимостей в контроллер.
     */
    public function __construct($entityManager, $albomManager) 
    {
        $this->entityManager = $entityManager;
        $this->albomManager = $albomManager;
    }

    /**
     * Это действие отображает страницу "New Albom". Она содержит 
     * форму, позволяющую ввести его навание, изображение и авторов.
     * Когда пользователь нажимает кнопку отправки формы, создается
     * новая сущность Albom.
     */
    public function addAction() 
    {     
        // Создаем форму.
        $form = new AlbomForm();
        
        // Проверяем, является ли альбом POST-запросом.
        if ($this->getRequest()->isPost()) {
            
            // Получаем POST-данные.
            $data = $this->params()->fromPost();
            
            // Заполняем форму данными.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Получаем валидированные данные формы.
                $data = $form->getData();
                
                // Используем менеджер постов для добавления нового поста в базу данных.                
                $this->albomManager->addNewAlbom($data);
                
                // Перенаправляем пользователя на страницу "index".
                return $this->redirect()->toRoute('application');
            }
        }
        
        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form
        ]);
    }   
}