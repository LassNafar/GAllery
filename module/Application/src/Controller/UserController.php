<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\User;
use Application\Form\UserForm;

/**
 * This controller is responsible for user management (adding, editing, 
 * viewing users and changing user's password).
 */
class UserController extends AbstractActionController 
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * User manager.
     * @var Application\Service\UserManager 
     */
    private $userManager;
    
    /**
     * Constructor. 
     */
    public function __construct($entityManager, $userManager)
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
    }
    
    /**
     * This is the default "index" action of the controller. It displays the 
     * list of users.
     */
    public function indexAction() 
    {
        // Create user form
        $form = new UserForm($this->entityManager);
        
        // Check if user has submitted the form
        if ($this->getRequest()->isPost()) {
            
            // Fill in the form with POST data
            $data = $this->params()->fromPost();            
            
            $form->setData($data);
            
            // Validate form
            if($form->isValid()) {
                
                // Get filtered and validated data
                $data = $form->getData();
                
                // Add user.
                $user = $this->userManager->addUser($data);
                
                // Redirect to "view" page
                return $this->redirect()->toRoute('gallery');                
            }               
        } 
        
        return new ViewModel([
                'form' => $form
            ]);
    }
}