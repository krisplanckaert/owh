<?php
/**
 * Abstract controller
 * Provides listing, edit and delete functionality
 */
abstract class My_Controller_Action extends Zend_Controller_Action
{
    /**
     * Form
     * @var object
     */
    protected $form;
    
    /**
     * 
     * Controller have multiple forms
     * @var bool
     */
    protected $formMultiple = FALSE;
   
    protected $parentTab;    
    
    protected $formOptions;
    
    protected $acl;

    protected $model;
    
    protected $mail;
    
    protected $flashMessenger = NULL;
    
    protected $module; 
    protected $controller;
    protected $action;
    
    protected $errorMessages;
    
    //protected $dbIntegrityConstraintErrorCode = 23000; //mysql error code
    
    protected $id = NULL;
    
    protected $authUser;
    
    protected $modelData;
    
    /**
     * Do redirect after successfull add/edit
     * set to FALSE if you want to redirect yourself
     */
    protected $doRedirect = TRUE;


    public function preDispatch(){      
    	$this->module     = $this->getRequest()->getModuleName(); 
    	$this->controller = $this->getRequest()->getControllerName();
    	$this->action     = $this->getRequest()->getActionName();
    }


    public function init($options = null)
    {
    	$this->authUser = (array)Zend_Auth::getInstance()->getIdentity();
    	$this->acl  = new My_Acl($this->authUser);
    	
        $this->entity = empty($this->entity) ? strstr(get_class($this), 'Controller', TRUE) : $this->entity;
        if ((isset($options['noModel']) && $options['noModel']) || ucfirst($this->entity)=='Index'){
    		$this->model = null;
    	}
    	else {
    		 $model = 'Application_Model_' . ucfirst($this->entity);
      		 $this->model = new $model();
    	}
        $this->flashMessenger = $this->_helper->getHelper('FlashMessenger');
        $this->setErrorMessages();
    }

   
    protected function setErrorMessages() {
    	$this->errorMessages = array(
    		'db' => array(
    			'23000' => 'Dublicate key : record already exists',    	
    			),    		
    	);    	    	
    }
    
    /**
     * Index listing
     */
    public function indexAction()
    {
        $this->view->messages = $this->flashMessenger->getMessages();
        $this->_forward('list');        
    }
    
    public function listAction()
    {    	
        $this->view->dataGrid = $this->model->buildDataGrid();    		
    }     

    
    
    /**
     * Get form
     * 
     * @param string $name : form name
     * @param int $id : id of record, no id = new ; id = edit
     * @param array $options : form options
     * @param array $params : parameters
     * @return object/void
     */
    protected function getForm($name, $id = NULL,$options = NULL, $params = NULL)
    { 
    	
    	//echo '<br />getForm : ' . var_dump($params); 
    	// multiple forms: we need to initialize every form
        if (empty($this->form) || $this->formMultiple) {
            $formObj = 'Application_Form_' . $name; 
            $this->form = new $formObj($id,$options,$params);
            if ($this->form instanceof My_Form){
            	$this->form->setModel($this->model,$this->modelData);
            }
        }
        // check if this is a jQuery validation call
        if ($this->getRequest()->isXmlHttpRequest() && $this->_helper->hasHelper('Ajax')) {
           $resp = $this->_helper->getHelper('Ajax')->validateJsonForm($this->form);
           $this->_helper->json($resp);
        }
        return $this->form;
    }
    
   
    /**
    * Detail, create or update
    */
    public function detailAction($options = NULL)
    {    	
       $this->id = $this->getRequest()->getParam('id'); 
       $this->processDetail($options);  
//Zend_Debug::dump($this->view->controller, 'processDetail');exit;    
    }    
    
      /**
     * Create/update
     * Update/add a record
     * @param array $options
     * @param array $params
     */
    protected function processDetail($options = NULL,$params = NULL)
    {
        $this->view->messages = $this->flashMessenger->getMessages();
        $form = $this->getForm(ucfirst($this->entity),$this->id,$this->formOptions,$params);
        $this->view->form = $form;
        $form->isModelData = FALSE; 
        if (!$this->getRequest()->isPost() || (!empty($this->parentTab) && $this->selectedTab!=$this->parentTab) ) {
            //no post, populate form with model data
            if (!empty($this->id)){
                $form->isModelData = TRUE;
                $formData=(array)$this->model->getOne($this->id);
                $form->populate($formData);
            } else {
                if (!empty($params)){
                    $form->populate($params);
                }
            }
            return;
        }
        $formData  = $this->_request->getPost();
        if (!empty($this->id) && (isset($formData['btn_softDelete_x']) ||  (isset($formData['btn_softDelete']) && $formData['btn_softDelete']==1))) {
            $totalAffected = $this->model->softDeleteById($this->id);
            $this->flashMessenger->addMessage('Delete OK');
            $this->_redirect('/' . $this->getRequest()->getControllerName().'/index');
            exit;
        }
        //save
        if (!$form->isValid($formData)) { 
            //form not valid, return
            $form->populate($formData);
            return;
        }
        $defaultOptions = array(
            'redirect'     =>  '/' . $this->getRequest()->getControllerName().'/index',
            'redirectToId' => FALSE,
        );
        $options = !empty($options) ? array_merge($defaultOptions,$options) : $defaultOptions ;
        // form is valid, try to save
        try {
            $id = $this->model->save($form->getValues(), $this->id); 
            $options['redirect'] .= !empty($options['redirectToId']) ? '/'.(int)$id : '';
            $this->flashMessenger->addMessage('Save OK');           
            $this->_redirect($options['redirect']);
        }
        catch (Exception $e) {
            $form->addError($e->getCode().' : '.$e->getMessage());
            return;
        }		
    }
    
/**
     * Create/update a child form 
     * A child always have a parent
     * @param array $options
     * @params array $params
     */
    protected function processChildDetail($options = NULL,$params = NULL)
    {
        $this->view->messages = $this->flashMessenger->getMessages();
        $form = $this->child['form']; //$this->getForm(ucfirst($this->child['form']),$this->child['id'],$this->formOptions,$params);
        if (!is_object($form)){
            throw new Exception('Process child detail, form is not an object');
            return;
        }

        $this->view->tabs[$params['tabName']]['form'] = $form;
        $formData  = $this->getRequest()->isPost() ? $this->_request->getPost() : null;
     
        $tabPost   = $this->getRequest()->isPost() && !empty($formData) && $params['tabName'] == $formData['tabName'] ? $formData : null;
        $form->isModelData = FALSE;
        
        if (empty($tabPost)) {
            if (!empty($this->child['id'])){    
                $form->isModelData = TRUE;
                $form->populate((array)$this->child['model']->getOne($this->child['id']));
            } else {
                if (!empty($params)){
                    $form->populate($params);
                }
            }
            return; 
        } 

        //delete?
        if (!empty($this->child['id']) && (isset($formData['btn_softDelete_x']) ||  (isset($formData['btn_softDelete']) && $formData['btn_softDelete']==1))){
            $totalAffected = $this->child['model']->softDeleteById($this->child['id']);
            $this->flashMessenger->addMessage('Delete OK');
            $this->_redirect('/' . $this->getRequest()->getControllerName(). '/detail/id/'.$this->id.'/tab/'.$this->selectedTab);
            exit;
        }
        if (!$form->isValid($formData)) {  
            $form->populate($formData);
            return; 
        }
        $defaultOptions = array(
            'redirect'     =>  '/' . $this->getRequest()->getControllerName().'/detail/id/'.$this->id.'/tab/'.$this->selectedTab ,
            'redirectToId' => TRUE,
            'childName'	   => 'childId',
        );
        $options = !empty($options) ? array_merge($defaultOptions,$options) : $defaultOptions ;
        // form is valid, try to save
        try {        	
            $childId = $this->child['model']->save($form->getValues(), $this->child['id']);
            $options['redirect'] .= !empty($options['redirectToId']) ? '/childId/'.(int)$childId : '';
            $this->flashMessenger->addMessage('Save OK');           
            $this->_redirect($options['redirect']);
        }
        catch (Exception $e) {
            if (APPLICATION_ENV == 'development'){
                $form->addError($e->getCode().' : '.$e->getMessage());
            } else if ($e->getCode() == $this->dbIntegrityConstraintErrorCode){
                //Integrity constraint violation
                $form->addError($this->errorMessages['db'][$this->dbIntegrityConstraintErrorCode]);
            } else {
                $form->addError($e->getCode().' : '.$e->getMessage());
            }
            return; 
        }		
    }  
    
    public function query($sql) {
        if (!empty($sql)){
            $db = $this->model->db;
            $stmt = $db->query($sql);
            $result = $stmt->fetchall();
            return $result;
        }
        else {
            return "";
        }
    }
       
}
