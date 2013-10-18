<?php

class Application_Form_Teamuser extends My_Form
{
    protected $_defaultFormOptions = array(
                           'name' => 'frmDetail',
                           'method' => 'post',
     );
	

    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
        $this->_defaultFormOptions['action'] = '/teamuser/detail'; //@todo: build action automatic, based on application and controller
        $options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options);
    
    	//team
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

            //permissions
            $permissionModel = new Application_Model_Permission();
            $permissions = $permissionModel->getAll();
            foreach($permissions as $permission) {
                $permissionList[$permission['ID']] = $permission['Description']; 
            }

            $permission = new Zend_Form_Element_MultiCheckbox('ID_Permissions');
            $permission->setLabel('Permissie')
                                ->setAttrib('class', 'singleSelect')
                                ->setMultiOptions($permissionList)
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
            //permissions
            $permission = null;
    	}  
            
        // active/inactive   
        $statusModel = new Application_Model_Status();
        $statusList  = $statusModel->buildSelect($options);
        $status = new Zend_Form_Element_Select('ID_Status');
        $status->setLabel('Status')
                ->setValue(1)
                ->setMultiOptions($statusList)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;
        
       	//username == e-mail
         $email = new Zend_Form_Element_Text('Username');
         $email->setLabel('E-mail')
        		->setAttribs(array(
			        'class' => 'w_large'
    				))         
                        ->setRequired()
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->addValidator('NotEmpty', TRUE)
                        ->addValidator('EmailAddress', TRUE)
        ;

        if (!$this->isUpdate()){
            //check if username doesn't exist
            $email->addValidator(new Zend_Validate_Db_NoRecordExists(
                            array(
                                'table' => 'teamusers',
                                'field' => 'Username',
                    )));
        }
         
         $password = new Zend_Form_Element_Password('Password');
         $password->setLabel('Password')
              ->addFilter('StringTrim')
              ->addFilter('StripTags')
              ->addValidator('StringLength',TRUE,array(6,200))
              ->setValue('');

        // Repeat password
        $password2 = new Zend_Form_Element_Password('repeatPassword');
        $password2->setLabel('Confirm password')
                       ->addFilter('StringTrim')
                       ->addFilter('StripTags')
                       ->addValidator('NotEmpty')
                       ->addValidator(new My_Validate_IdenticalField('Password'), FALSE)
                       ->setValue('');
                    ;
        if (!$this->isUpdate()){
            $password ->setRequired(true)
                      ->setLabel('New password');
            $password2->setRequired(true)
                      ->setLabel('Confirm new password');
        }

        // save
        $submit=new Zend_Form_Element_Submit('Save');
       	$submit->setRequired(false)
               ->setIgnore(true)
               ->setDecorators($this->buttonDecorators)
        ;

         $formElements1 = array($status,$team);
         if ($this->acl->userIsAllowed(null,My_Resources::EDIT_TEAM)){
            $formElements1[] = $permission;
         }
         $formElements2 = array($email,$password,$password2);
         $formElements =  array_merge($formElements1,$formElements2);
        
        //
       
        $this->addElements($formElements);
        $this->setElementDecorators($this->elementDecorators);
        
        
        $displayGroupElements = array($submit);
        
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

