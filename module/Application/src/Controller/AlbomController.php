<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AlbomForm;
use Application\Form\UpdateAlbomForm;
use Application\Form\AddAuthorForm;
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
     * Менеджер изображений.
     * @var Application\Service\ImageManager;
     */
    private $imageManager;
    
    /**
     * Конструктор, используемый для внедрения зависимостей в контроллер.
     */
    public function __construct($entityManager, $albomManager, $imageManager) 
    {
        $this->entityManager = $entityManager;
        $this->albomManager = $albomManager;
        $this->imageManager = $imageManager;
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
            
            // Получаем POST-данные и FILE.
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Заполняем форму данными.
            $form->setData($data);
            if ($form->isValid()) {
                                
                // Получаем валидированные данные формы.
                $data = $form->getData();
                //return new ViewModel(['fff' => $data]);
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
    
    // Это действие отображает страницу, позволяющую отредактировать альбом.
    public function editAction() 
    {
        // Создаем форму.
        $form = new UpdateAlbomForm();
    
        // Получаем ID поста.    
        $albomId = $this->params()->fromRoute('id', -1);
    
        // Находим существующий пост в базе данных.    
        $albom = $this->entityManager->getRepository(Albom::class)
                ->findOneById($albomId);        
        if ($albom == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
        
        // Проверяем, является ли пост POST-запросом.
        if ($this->getRequest()->isPost()) {
            
            // Получаем POST-данные и FILE.
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Заполняем форму данными.
            $form->setData($data);
            if ($form->isValid()) {
                
                // Получаем валидированные данные формы.
                $data = $form->getData();
                
                // Используем менеджер постов, чтобы добавить новый пост в базу данных.                
                $this->albomManager->updateAlbom($albom, $data);
                
                // Перенаправляем пользователя на страницу "admin".
                return $this->redirect()->toRoute('gallery');
            }
        } else {
            $data = [
               'name' => $albom->getName(),
               'image' => $albom->getImage(),
               'priority' => $albom->getPriority()
            ];
            
            $form->setData($data);
        }
        
        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form,
            'albom' => $albom
        ]);  
    }
    
    public function addAuthorAction() 
    {
        // Создаем форму.
        $form = new AddAuthorForm();
    
        // Получаем ID поста.    
        $albomId = $this->params()->fromRoute('id', -1);
    
        // Находим существующий пост в базе данных.    
        $albom = $this->entityManager->getRepository(Albom::class)
                ->findOneById($albomId);        
        if ($albom == null) {
            $this->getResponse()->setStatusCode(404);
            return;                        
        } 
        
        // Проверяем, является ли пост POST-запросом.
        if ($this->getRequest()->isPost()) {
            
            // Получаем POST-данные.
            $data = $this->params()->fromPost();
            
            // Заполняем форму данными.
            $form->setData($data);
            if ($form->isValid()) {
                
                // Получаем валидированные данные формы.
                $data = $form->getData();
                
                // Используем менеджер постов, чтобы добавить новый пост в базу данных.                
                $this->albomManager->addAuthorsToAlbom($data['author'],$albom);
                
                // Перенаправляем пользователя на страницу "home".
                return $this->redirect()->toRoute('gallery');
            }
        }
        
        // Визуализируем шаблон представления.
        return new ViewModel([
            'form' => $form,
            'albom' => $albom
        ]);  
    }
    
    public function fileAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('name', '');
                
        // Check whether the user needs a thumbnail or a full-size image
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);
        
        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }
        
        // Get path to image file
        $fileName = $this->imageManager->getImagePathByName($fileName);
                
        if($isThumbnail) {        
            // Resize the image
            $fileName = $this->imageManager->resizeImage($fileName);
        }
                
        // Get image file info (size and MIME type).
        $fileInfo = $this->imageManager->getImageFileInfo($fileName);        
        if ($fileInfo===false) {
            // Set 404 Not Found status code
            $this->getResponse()->setStatusCode(404);            
            return;
        }
                
        // Write HTTP headers.
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);        
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);
            
        // Write file content        
        $fileContent = $this->imageManager->getImageFileContent($fileName);
        if($fileContent!==false) {                
            $response->setContent($fileContent);
        } else {        
            // Set 500 Server Error status code
            $this->getResponse()->setStatusCode(500);
            return;
        }
        
        if($isThumbnail) {
            // Remove temporary thumbnail image file.
            unlink($fileName);
        }
        
        // Return Response to avoid default view rendering.
        return $this->getResponse();
    }
}