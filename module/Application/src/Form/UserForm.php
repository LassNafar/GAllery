<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilter;
use Application\Validator\UserExistsValidator;

/**
 * This form is used to collect user's email, full name, password and status. The form 
 * can work in two scenarios - 'create' and 'update'. In 'create' scenario, user
 * enters password, in 'update' scenario he/she doesn't enter password.
 */
class UserForm extends Form
{
   
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager 
     */
    private $entityManager = null;
    
    /**
     * Current user.
     * @var Application\Entity\User 
     */
    private $user = null;
    
    /**
     * Constructor.     
     */
    public function __construct($entityManager = null, $user = null)
    {
        // Define form name
        parent::__construct('user-form');
     
        // Set POST method for this form
        $this->setAttribute('method', 'post');
        
        $this->entityManager = $entityManager;
        $this->user = $user;
        
        $this->addElements();
        $this->addInputFilter();          
    }
    
    /**
     * This method adds elements to form (input fields and submit button).
     */
    protected function addElements() 
    {
        // Add "login" field
        $this->add([            
            'type'  => 'text',
            'name' => 'login',
            'options' => [
                'label' => 'Login',
            ],
        ]);
        
        // Add "name" field
        $this->add([            
            'type'  => 'text',
            'name' => 'name',            
            'options' => [
                'label' => 'Name',
            ],
        ]);

        // Add "password" field
        $this->add([            
            'type'  => 'password',
            'name' => 'password',
            'options' => [
                'label' => 'Password',
            ],
        ]);

        // Add "confirm_password" field
        $this->add([            
            'type'  => 'password',
            'name' => 'confirm_password',
            'options' => [
                'label' => 'Confirm password',
            ],
        ]);
        
        // Add the Submit button
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create'
            ],
        ]);
    }
    
    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
                
        // Add input for "login" field
        $inputFilter->add([
                'name'     => 'login',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 128
                        ],
                    ],
                    [
                        'name' => UserExistsValidator::class,
                        'options' => [
                            'entityManager' => $this->entityManager,
                            'user' => $this->user
                        ],
                    ],                    
                ],
            ]);     
        
        // Add input for "name" field
        $inputFilter->add([
                'name'     => 'name',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 512
                        ],
                    ],
                ],
            ]);
            
        // Add input for "password" field
        $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [                        
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);

        // Add input for "confirm_password" field
        $inputFilter->add([
                'name'     => 'confirm_password',
                'required' => true,
                'filters'  => [                        
                ],                
                'validators' => [
                    [
                        'name'    => 'Identical',
                        'options' => [
                            'token' => 'password',                            
                        ],
                    ],
                ],
            ]);
    }
}