<?php

class Application_Form_Registration extends My_Form
{
    private $childId; 
    protected $isChild = TRUE;
    protected $parentId;
    protected $productGroup;
    protected $chamionshipday;    
    protected $_defaultFormOptions = array(
                           'name' => 'frmDetail',
                           'method' => 'post',
     );
	
    protected function getChapionshipday(){
        return $this->chamionshipday;
    }

    protected function setChampionshipday($id_chamionshipday){
        $championshipdayModel = new Application_Model_Championshipday();
        $this->championshipdat =  $championshipdayModel->getOne((int)$id_chamionshipday);
        if (empty($this->championshipdat)){
                throw new Exception('Championshipdat unknown');
        }
    }
    
    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
        $id = $this->childId = (int)$id; //child ID
    	if ($this->isChild && isset($params['parentId']) && !empty($params['parentId'])){
    		$this->parentId =  (int)$params['parentId']; // = ID_Variabele
    		$this->updateMode = !empty($this->childId) ? TRUE : FALSE;
    	}
       
        $this->_defaultFormOptions['action'] = '/registration/save'; //@todo: build action automatic, based on application and controller
        $options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options, $params);
    
        $registrationModel = new Application_Model_Registration();
        $this->setModel($registrationModel);
        $this->setModelData($params['childId']);

        $championshipdayModel = new Application_Model_Championshipday();
        $championshipday = $championshipdayModel->getOne($this->parentId);
        $championshipdaydescription = new My_Form_Element_Note('ChampionshipDay');
        $championshipdaydescription->setLabel('Championshipdat')
             ->setValue($championshipday['Description'])
             ->setDecorators(array('ViewHelper'))                    
        ;

        $teamModel = new Application_Model_Team();
        $teams  = $teamModel->buildSelect(array('order' => 'Description'));        
        if ($this->acl->userIsAllowed(null,My_Resources::EDIT_TEAM)){       
            //team
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
            $teamName = array_key_exists($this->authUser['ID_Team'],$teams) ? $teams[$this->authUser['ID_Team']]: '-';      
            $team = new My_Form_Element_Note('Team');
            $team->setLabel('Team')
                 ->setValue($teamName)
                 ->setDecorators(array('ViewHelper'))                    
            ;
        }
     
        $playerModel = new Application_Model_Player();
        $options = array(
            'emptyRow' => FALSE,
            'order' => 'Name',
            'value' => 'Name',
        );
        if($this->modelData['ID_Team']) {
            $options['where'] = 'ID_Team = ' . $this->modelData['ID_Team'];
        }
        $playerList = $playerModel->buildSelect($options);
        $player = new Zend_Form_Element_MultiCheckbox('ID_Player');
        $player->setLabel('Players')
                ->setMultiOptions($playerList)
                ->setFilters(array('StringTrim', 'StripTags'))
        ;
        
        $this->addElements(array($championshipdaydescription, $team, $player));
        $this->setElementDecorators($this->elementDecorators);
        
        // save
        $submit=new Zend_Form_Element_Submit('Save');
       	$submit->setRequired(false)
               ->setDecorators(array('ViewHelper'))
        ;

        $displayGroupElements = array($submit);

        if ($this->isUpdate()){
            // soft delete => ID_Status = 2
            $delete = new Zend_Form_Element_Image('btn_softDelete');
            $delete ->setImage('/base/images/icons/crossLarge.png')
                    ->setValue(1)
                    ->setIgnore(false)
                    ->setAttribs(array('style' => 'float:left','title' => 'delete','onclick' => "return confirmText('Really delete?');"))
                    ->setDecorators(array('ViewHelper'))
            ;
            $displayGroupElements = array_merge($displayGroupElements,array($delete));
        }
        

        $this->addDisplayGroup($displayGroupElements, 'buttons')
            ->setDisplayGroupDecorators(array(array('ViewScript',array('viewScript'=>'forms/_tableFootButtons.phtml','class' => 'footer'))))
        ;        
        // -----------------------------------
        // Hidden fields
        $championshipday = new Zend_Form_Element_Hidden('ID_ChampionshipDay');
        $championshipday->setDecorators(array('ViewHelper'))
                ->setValue((int) $this->parentId);        
        $tab = new Zend_Form_Element_Hidden('tabName');
        $tab->setDecorators(array('ViewHelper'))
                ->setValue($params['tabName']);   
        $child	= new Zend_Form_Element_Hidden('childId');
        $child->setDecorators(array('ViewHelper'))
              ->setValue((int)$this->childId);
        
        $hiddenElems = array($championshipday, $tab, $child);
        $this->addElements($hiddenElems);        
    }
    
}

