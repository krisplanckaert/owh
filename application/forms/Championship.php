<?php

class Application_Form_Championship extends My_Form
{
    protected $_defaultFormOptions = array(
                           'name' => 'frmDetail',
                           'method' => 'post',
     );
	

    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
        $this->_defaultFormOptions['action'] = '/championship/detail'; //@todo: build action automatic, based on application and controller
        $options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options);

       	//Description
         $description = new Zend_Form_Element_Text('Description');
         $description->setLabel('Description')
        		->setAttribs(array(
			        'class' => 'w_large'
    				))         
                        ->setRequired()
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->addValidator('NotEmpty', TRUE)
        ;
         
        // save
        $submit=new Zend_Form_Element_Submit('Save');
       	$submit->setRequired(false)
               ->setIgnore(true)
                ->setDecorators(array('ViewHelper'))
        ;
        
        $this->addElements(array($description));
        $this->setElementDecorators($this->elementDecorators);
        
        $displayGroupElements = array($submit);
        
        if ($this->isUpdate()) {
            $delete = new Zend_Form_Element_Image('btn_softDelete');
            $delete->setImage('/base/images/icons/crossLarge.png')
            ->setValue(1)
            //->setIgnore(true)
            ->setAttribs(array('style' => 'float:left', 'title' => 'delete', 'onclick' => "return confirmText('Really delete?');"))
            ->setDecorators(array('ViewHelper'))
            ;
            $displayGroupElements = array_merge($displayGroupElements,array($delete));
        }

        $this->addDisplayGroup($displayGroupElements, 'buttons')
            ->setDisplayGroupDecorators(array(array('ViewScript',array('viewScript'=>'forms/_tableFootButtons.phtml','class' => 'footer'))))
        ;        
    }
    
}

