<?php

class Application_Form_Game extends My_Form
{
    private $childId; 
    protected $isChild = TRUE;
    protected $parentId;
    protected $productGroup;
    protected $tournement;    
    protected $_defaultFormOptions = array(
                           'name' => 'frmDetail',
                           'method' => 'post',
     );
	

    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
        $id = $this->childId = (int)$id; //child ID
    	if ($this->isChild && isset($params['parentId']) && !empty($params['parentId'])){
    		$this->parentId =  (int)$params['parentId']; // = ID_Variabele
    		$this->updateMode = !empty($this->childId) ? TRUE : FALSE;
    	}        
        
        $this->_defaultFormOptions['action'] = '/game/detail'; //@todo: build action automatic, based on application and controller
        $options = !empty($options) ? array_merge($this->_defaultFormOptions,(array)$options) : $this->_defaultFormOptions;
        parent::__construct($id, $options);
    
        $playerModel = new Application_Model_Player();
        $players  = $playerModel->buildSelect(array('order' => 'Name', 'value' => 'Name'));
/*        
    	//referee1
        $referee1 = new Zend_Form_Element_Select('ID_PlayerReferee1');
        $referee1->setLabel('Referee 1')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($players)
                ->addValidator('NotEmpty', TRUE)
        ;        

    	//referee2
        $referee2 = new Zend_Form_Element_Select('ID_PlayerReferee2');
        $referee2->setLabel('Referee 2')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($players)
                ->addValidator('NotEmpty', TRUE)
        ;       
        
   	//refereeHead
        $refereeHead = new Zend_Form_Element_Select('ID_PlayerRefereeHead');
        $refereeHead->setLabel('Referee Head')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($players)
                ->addValidator('NotEmpty', TRUE)
        ; 
 * 
 */         
        
        $teamModel = new Application_Model_Team();
        $teams  = $teamModel->buildSelect(array('order' => 'Description'));        
        
    	//teamWhite
        $teamWhite = new Zend_Form_Element_Select('ID_TeamWhite');
        $teamWhite->setLabel('Team White')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($teams)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;

    	//teamBlack
        $teamBlack = new Zend_Form_Element_Select('ID_TeamBlack');
        $teamBlack->setLabel('Team Black')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($teams)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;     

    	//teamReferee1
        $teamReferee1 = new Zend_Form_Element_Select('ID_TeamReferee1');
        $teamReferee1->setLabel('Team Referee 1')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($teams)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;
    	//teamReferee2
        $teamReferee2 = new Zend_Form_Element_Select('ID_TeamReferee2');
        $teamReferee2->setLabel('Team Referee 2')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($teams)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;
    	//teamRefereeHead
        $teamRefereeHead = new Zend_Form_Element_Select('ID_TeamRefereeHead');
        $teamRefereeHead->setLabel('Team Referee Head')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($teams)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;        
        //Field
        $fieldModel = new Application_Model_Field();
        $fields  = $fieldModel->buildSelect(array('order' => 'Description'));        
        $field = new Zend_Form_Element_Select('ID_Field');
        $field->setLabel('Field')
                 ->setAttribs(array(
                                'class' => 'w_large'
                        ))
                ->setMultiOptions($fields)
                ->setRequired()
                ->addValidator('NotEmpty', TRUE)
        ;
/*        
       	//ScoreWhite
         $scoreWhite = new Zend_Form_Element_Text('ScoreWhite');
         $scoreWhite->setLabel('ScoreWhite')
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->setValue(NULL)
        ;
       	//ScoreBlack
         $scoreBlack = new Zend_Form_Element_Text('ScoreBlack');
         $scoreBlack->setLabel('ScoreBlack')
                        ->addFilters(array('StringTrim','StripTags','StringToLower'))
                        ->setValue(NULL)
        ;
 * 
 */
        // Time
        $time = new Zend_Form_Element_Text('Time');
        $time->setLabel('Time')
                ->setRequired()
        ;
        // save
        $submit=new Zend_Form_Element_Submit('Save');
       	$submit->setRequired(false)
               ->setIgnore(true)
               ->setDecorators($this->buttonDecorators)
        ;

        //$this->addElements(array($field, $time, $teamWhite, $teamBlack, $teamReferee1, $teamReferee2, $teamRefereeHead, $referee1, $referee2, $refereeHead, $scoreWhite, $scoreBlack));
        $this->addElements(array($field, $time, $teamWhite, $teamBlack, $teamReferee1, $teamReferee2, $teamRefereeHead));        
        $this->setElementDecorators($this->elementDecorators);
                
        $displayGroupElements = array($submit);
        
        $this->addDisplayGroup($displayGroupElements, 'buttons')
        ->setDisplayGroupDecorators(array(array('ViewScript',array('viewScript'=>'forms/_tableFootButtons.phtml','class' => 'footer'))))
        ;
        // -----------------------------------
        // Hidden fields
        $tournement = new Zend_Form_Element_Hidden('ID_Tournement');
        $tournement->setDecorators(array('ViewHelper'))
                ->setValue((int) $this->parentId);        
        $tab = new Zend_Form_Element_Hidden('tabName');
        $tab->setDecorators(array('ViewHelper'))
                ->setValue($params['tabName']);   
        $child	= new Zend_Form_Element_Hidden('childId');
        $child->setDecorators(array('ViewHelper'))
              ->setValue((int)$this->childId);
        
        $hiddenElems = array($tournement, $tab, $child);
        $this->addElements($hiddenElems);         
    }
    
}

