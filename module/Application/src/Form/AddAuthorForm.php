<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\FileInput;
use Application\Entity\Albom;

/**
 * Эта форма используется для сбора данных о посте.
 */
class AddAuthorForm extends Form
{
    /**
     * Конструктор.     
     */
    public function __construct()
    {
        // Определяем имя формы.
        parent::__construct('add-author-form');
     
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
                
        // Добавляем поле "author"
        $this->add([           
            'type'  => 'text',
            'name' => 'author',
            'attributes' => [
                'id' => 'author'
            ],
            'options' => [
                'label' => 'Author Name',
            ],
        ]);
        
        // Добавляем кнопку отправки формы
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Add',
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
                'name'     => 'author',
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