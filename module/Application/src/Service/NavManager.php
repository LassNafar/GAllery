<?php
namespace Application\Service;
/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;
    
    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;
    
    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper) 
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
    }
    
    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems() 
    {
        $url = $this->urlHelper;
        $items = [];
        
        $items[] = [
            'id' => 'gallery',
            'label' => 'Gallery',
            'link'  => $url('gallery')
        ];
        
        $items[] = [
            'id' => 'alboms',
            'label' => 'AddAlbom',
            'link'  => $url('alboms', ['action'=>'add'])
        ];

        
        // Display "Login" menu item for not authorized user only. On the other hand,
        if (!$this->authService->hasIdentity()) {
            $items[] = [
                'id' => 'login',
                'label' => 'Sign in',
                'link'  => $url('login'),
                'float' => 'right'
            ];
            $items[] = [
            'id' => 'registration',
            'label' => 'Registration',
            'link'  => $url('registration')
            ];
        } else {
            
            $items[] = [
                'id' => 'logout',
                'label' => $this->authService->getIdentity(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'logout',
                        'label' => 'Sign out',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }
        
        return $items;
    }
}