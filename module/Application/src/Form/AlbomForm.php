<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Application\Entity\Albom;

/**
 * Эта форма используется для сбора данных о посте.
 */
class AlbomForm extends Form
{
    /**
     * Конструктор.     
     */
    public function __construct()
    {
        // Определяем имя формы.
        parent::__construct('post-form');
     
        // Задает для этой формы метод POST.
        $this->setAttribute('method', 'post');
        
        $this->setAttribute('enctype', 'multipart/form-data');  
        
        $this->addElements();
        $this->addInputFilter();         
    }
    
    /**
     * Этот метод добавляет элементы к форме (поля ввода и кнопку отправки формы).
     */
    protected function addElements() 
    {
                
        // Добавляем поле "name"
        $this->add([           
            'type'  => 'text',
            'name' => 'name',
            'attributes' => [
                'id' => 'name'
            ],
            'options' => [
                'label' => 'Albom Name',
            ],
        ]);
        
        // Добавляем поле "content"
        $this->add([
            'type'  => 'file',
            'name' => 'image',
            'attributes' => [               
                'id' => 'image'
            ],
            'options' => [
                'label' => 'Image',
            ],
        ]);
        
        // Добавляем поле "tags"
        $this->add([
            'type'  => 'text',
            'name' => 'authors',
            'attributes' => [                
                'id' => 'authors'
            ],
            'options' => [
                'label' => 'Authors',
            ],
        ]);
        
        // Добавляем поле "priority"
        $this->add([
            'type'  => 'text',
            'name' => 'priority',
            'attributes' => [                
                'id' => 'priority'
            ],
            'options' => [
                'label' => 'Priority',
            ],
        ]);
        
        // Добавляем кнопку отправки формы
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Create',
                'id' => 'submitbutton',
            ],
        ]);
    }
    
    /**
     * Этот метод создает фильтр входных данных (используется для фильтрации/валидации).
     */
    private function addInputFilter() 
    {
        
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
                'name'     => 'name',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 1024
                        ],
                    ],
                ],
            ]);
        
        $inputFilter->add([
                'type'     => FileInput::class,
                'name'     => 'file',
                'required' => true,                           
                'validators' => [
                    ['name'    => 'FileUploadFile'],
                    [
                        'name'    => 'FileMimeType',                        
                        'options' => [                            
                            'mimeType'  => ['image/jpeg', 'image/png']
                        ]
                    ],
                    ['name'    => 'FileIsImage'],                          
                    [
                        'name'    => 'FileSize',                        
                        'options' => [                            
                            'max'  => '10MB',
                        ]
                    ],                    
                ],
                'filters'  => [                    
                    [
                        'name' => 'FileRenameUpload',
                        'options' => [  
                            'target'=>'./data/upload',
                            'useUploadName'=>true,
                            'useUploadExtension'=>true,
                            'overwrite'=>true,
                            'randomize'=>false
                        ]
                    ]
                ],     
            ]);   
        
        $inputFilter->add([
                'name'     => 'authors',
                'required' => true,
                'filters'  => [                    
                    ['name' => 'StringTrim'],
                    ['name' => 'StripTags'],
                    ['name' => 'StripNewlines'],
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 1,
                            'max' => 1024
                        ],
                    ],
                ],
            ]);
    }
}
