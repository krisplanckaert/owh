<?php
class My_Form extends Zend_Form
{
    protected $tr; // translator
    protected $model;
    
    protected $modelData;
    
    public $isModelData;
		
    protected $updateMode;
    
    // children
    	//protected $isChild;
    	//protected $childId;
    protected $authUser;
    
    protected $defaultDecorator;
    
    protected $defaultDecoratorOptions;
    
	protected $elementDecorators = array(
		'ViewHelper',
		'Errors',
		'Description',
		//array('Description' => array('tag' => 'span')),
		array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element',)),
		array('label', array('tag' => 'th')),
		array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
		);

	protected $buttonDecorators = array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
		array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
		array(array('row' => 'HtmlTag'), array('tag' => 'tfoot')),
	);  

		protected $divDecorators = array(
		'ViewHelper',
		array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'element')),
		//array(array('label' => 'HtmlTag'), array('tag' => 'td', 'placement' => 'prepend')),
		//array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
		//array(array('row' => 'HtmlTag'), array('tag' => 'tfoot')),
	);
    
    protected $data;
    
    protected $acl;
    
    //protected $selectedProductGroup;
    protected $dealerRow; //dealer of current user

    
     /**
     * Create a new form
     *
     * @param $boolean $updateMode TRUE = update, FALSE = create
     * @param mixed $options
     */
    public function __construct($id = NULL, $options = NULL, $params = NULL)
    {
    	$this->tr = $this->getTranslator();
    	//$this->selectedProductGroup = Zend_Registry::get('selectedProductgroup');
    	//echo 'is child => ' . (int)$this->isChild;
    	if (!isset($this->isChild) || !$this->isChild){ 
          if (isset($options['action'])) {
            if (!is_null($id)) {
                if (strpos(rtrim($options['action'], '/'), '/', 1) == FALSE) {
                    $options['action'] .= "/detail/id/$id";
                } else {
                    $options['action'] .= "/id/$id";
                }
            } else {
                if (strpos(rtrim($options['action'], '/'), '/', 1) == FALSE) {
                    $options['action'] .= "/detail";
                }
            }
         }
    	}
    	
    	$this->authUser = (array)Zend_Auth::getInstance()->getIdentity();
    	$this->acl = new My_Acl($this->authUser);

    	//var_dump($this->authUser);
        
        
         $this->defaultDecoratorOptions = array(
            'ViewHelper',
            'Errors',
            array(array('data' => 'HtmlTag'), array('tag' => 'td', 'class' => 'element')),
        );
        
        // decorator for all elements:
        
        	$this->defaultDecorator = new My_Form_Decorator_Default();
        	/*
        	$this->addElementPrefixPath('My_Form_Decorator',
                            'My/Form/Decorator/',
                            'decorator');
        */
        //Zend_Debug::dump($options,'form constructor options');
        parent::__construct($options);

        //if ($this->isChild){
        	//echo 'childId = ' . $this->childId;
        //	$this->updateMode = !empty($this->childId) ? TRUE : FALSE;
        //}
        //else {
        	//default
        	//var_dump($id);
        	$this->updateMode = (!empty($id) && $id>0) ? TRUE : FALSE;
        	//$this->updateMode = !is_null($id);
        //}


        
        //Remove the outer html tag, we use our forms inside
        //a custom fieldset tag with custom decorators       
        //$this->removeDecorator('HtmlTag');

        //if ($this->isUpdate() && !$this->isChild) {
        if ($this->isUpdate()) {
        	if ($this->isChild){
        		// a child is always linked to a parent
//        			$elem = new Zend_Form_Element_Hidden('id', array(
//						'decorators' => $this->elementDecorators,
//						'value'      => (int)$this->parentId,
//        			));
        			$hiddenElem = new Zend_Form_Element_Hidden('id');
					$hiddenElem ->setDecorators(array('ViewHelper'))
								->setValue((int)$this->parentId);
        	}
        	else {
            	//default
        			$hiddenElem = new Zend_Form_Element_Hidden('id');
					$hiddenElem  ->setDecorators(array('ViewHelper'))
						->setValue((int)$id);            	
//				$elem = new Zend_Form_Element_Hidden('id', array(
//					'decorators' => $this->elementDecorators,
//					'value'      => (int)$id)
//				);
        	}
              //  $elem->removeDecorator('label');
              //  $elem->removeDecorator('htmlTag');
              //  $elem->addDecorator('htmlTag', array('tag' => 'p', 'class' => 'hidden'));			
            $this->addElement($hiddenElem);            
        }

         // errors decorator
         
        $this->addDecorator(new My_Form_Decorator_FormErrors(array(
            'placement' => Zend_Form_Decorator_Abstract::PREPEND,
            'message' => 'Er werden fouten vastgesteld bij het verwerken van uw verzoek',
        )));
    }
    
    /**
     * 
     * Set model / data
     * @param object $model
     * @param array $data
     */
    public function setModel($model,$data = null){
    	if (!$model instanceof My_Model){
    		return;
    	}
    	$this->model = $model;
    	$this->modelData = $data;
    }		
	
    /*
     * set model data from model
     * @param int $id : model row id (primary key)
     */
    public function setModelData($id){
        $this->modelData = null;
        if (empty($id) || empty($this->model) || !is_object($this->model) ){
            return;
        }
        $this->modelData =  $this->model->getOne((int)$id); 		
    }
    
    

    public function setData($data){
    	$this->data = $data;
    }
    
    
    /**
     * Check if form is in update mode
     * @return boolean
     */
    public function isUpdate()
    {
        return $this->updateMode;
    }

    /**
     * Check if form is in create mode
     * @return boolean
     */
    public function isCreate()
    {
        return !$this->updateMode;
    }
    
    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table' ,'class' => 'frm_01')),
            //'FormHiddenElements',
            'Form',
        ));
    }    
 
}
