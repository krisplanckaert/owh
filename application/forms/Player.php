<?php

class Application_Form_Player extends My_Form
{
    protected $_defaultFormOptions = array(
                           'name' => 'frmDetail',
                           'method' => 'post',
     );
	

    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
        $this->_defaultFormOptions['action'] = '/player/detail'; //@todo: build action automatic, based on application and controller
        $options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options);
    
       	//Name
         $name = new Zend_Form_Element_Text('Name');
         $name->setLabel('Name')
        		->setAttribs(array(
			        'class' => 'w_large'
    				))         
                        ->setRequired()
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->addValidator('NotEmpty', TRUE)
        ;

       	//Email
         $email = new Zend_Form_Element_Text('Email');
         $email->setLabel('Email')
        		->setAttribs(array(
			        'class' => 'w_large'
    				))         
                        ->setRequired()
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->addValidator('NotEmpty', TRUE)
        ;         
         
        $teamModel = new Application_Model_Team();
        $teams  = $teamModel->buildSelect(array('order' => 'Description'));
        if ($this->acl->userIsAllowed(null,My_Resources::EDIT_TEAM)){
            //user can edit team
            $team = new Zend_Form_Element_Select('ID_Team');
            $team->setLabel('Team')
                     ->setAttribs(array(
                        'class' => 'w_large'
                    ))
                    ->setMultiOptions($teams)
                    ->setRequired()
                    ->addValidator('NotEmpty', TRUE)
            ;
   	} else {
            //user can not edit team
            $teamName = array_key_exists($this->authUser['ID_Team'],$teams) ? $teams[$this->authUser['ID_Team']]: '-';
            $team = new My_Form_Element_Note('TeamName');
            $team->setLabel('Team')
                    ->setValue($teamName)
                    ->setDecorators(array('ViewHelper'))
            ;
    	}  

        $player = new Zend_Form_Element_Checkbox('Player');
        $player->setLabel('Player');        
        $referee = new Zend_Form_Element_Checkbox('Referee');
        $referee->setLabel('Referee');        
        $coach = new Zend_Form_Element_Checkbox('Coach');
        $coach->setLabel('Coach');        
        $captain = new Zend_Form_Element_Checkbox('Captain');
        $captain->setLabel('Captain');        
        
        // save
        $submit=new Zend_Form_Element_Submit('Save');
       	$submit->setRequired(false)
               ->setIgnore(true)
               //->setDecorators($this->buttonDecorators)
                ->setDecorators(array('ViewHelper'))
        ;
        
        $this->addElements(array($name, $email, $team, $player, $referee, $coach, $captain));
        $this->setElementDecorators($this->elementDecorators);
        
        $displayGroupElements = array($submit);
        
/*        $this->addDisplayGroup($displayGroupElements, 'buttons')
            ->setDisplayGroupDecorators(array(array('ViewScript',array('viewScript'=>'forms/_tableFootButtons.phtml','class' => 'footer'))))
        ;*/
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
        
        // -----------------------------------
        // Hidden fields
        if (!$this->acl->userIsAllowed(null,My_Resources::EDIT_TEAM)){
            $teamId = new Zend_Form_Element_Hidden('ID_Team');
            $teamId->setDecorators(array('ViewHelper'))
                     ->setValue((int)$this->authUser['ID_Team']);

            $hiddenElems = array($teamId);
            $this->addElements($hiddenElems);
        }        
    }
    
}

