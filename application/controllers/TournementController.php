<?php
class TournementController extends My_Controller_Action 
{   
    protected $child = array();
    
    protected $formMultiple = TRUE;
    
    protected $formData	 = null;
    
    protected $tabIndex = array();
    protected $tabIndexCounter = -1;
    protected $selectedTabIndex = 0;    
    
    protected $parentTab = 'tournement';

    public function init() {
    	parent::init();  
        $this->formData = $this->getRequest()->isPost() ? $this->_request->getPost() : null;
        $this->baseLink = '/' . $this->controller . '/' . $this->action;
        $this->child = array(
            'id' => null,
            'model' => null,
            'form' => null,
            'datagrid' => null,
        );    	

    }
    
    public function registrationAction() {
        $data = $this->_getAllParams();
        $registrationModel = new Application_Model_Registration();
        $tournement = $this->model->getOne($data['id']);
        $fields = array(
            'ID_Tournement' => $data['id'],
            'ID_Team' => $this->authUser['ID_Team'],
        );
        $registration = $registrationModel->getOneByFields($fields);
        if($registration) {
            $id = $registration['ID'];
        } else {
            $id = $registrationModel->save($fields);
        }
        $registration = $registrationModel->getOne($id);
        $options = NULL;
        $params['ID'] = $id;
        $params['childId'] = $id;
        $params['parentId'] = $data['id'];
        $params['tabName'] = '';
        
        date_default_timezone_set('UTC');
        $date = date('Y-m-d');
        if($tournement['Date']>$date) {
            $this->view->form = new Application_Form_Registration($id, $options, $params);
            $this->view->form->populate($registration);
        } else {
            $this->view->form = null;
        }
        $this->view->overview = $registrationModel->getOverviewByTournement($data['id']);
    }    
    
    public function detailAction() {
        $data = $this->_getAllParams(); //get all params from all tabs, @todo check!

        $this->view->selectedTabIndex = $this->selectedTabIndex;
        $this->view->loadedTabs = $this->tabIndex;
        
        $this->formMultiple = TRUE;
        $this->id          = (int)$this->_getParam('id'); // get tournementId
        $this->selectedTab = $this->view->selectedTab = $this->_getParam('tab','tournement');

        $this->view->messages = $this->flashMessenger->getMessages();
        
        $this->flashMessenger->setNamespace('Default');

        $data['page'] = isset($data['page']) && !empty($data['page']) ? $data['page'] : 'list';
        $data['childId'] = isset($data['childId']) && !empty($data['childId']) ? (int)$data['childId'] : 0;
        $this->view->data = $data;    	
    	
        if (!empty($this->id)) {
            //update
            $this->modelData = $this->model->getOne($this->id);
            if (!empty($this->modelData)){
                $this->id = $data['id'] = (int) $this->modelData['ID']; //klantId
            }
            $this->baseLink .= '/id/' . $this->id;
            $this->view->tournement = $this->modelData;
        }

        // ---------------
        // Load Tabs
        // ---------------------------------
      	$this->child['id'] = $this->_getParam('childId', NULL);	  
      	$this->view->tabs = array(
			'tournement'   	=> '',
			'registration'  => '',
                        'game'          => '',
        );
    	//tab 1 = parent tab
        $this->tabTournement('tournement',$data);
        if (empty($this->id) || empty($this->modelData)){
            //parent is  unknown, so we can't load other tabs because they are related
            return;
        }
        $this->tabRegistration('registration',$data);
        $this->tabGame('game',$data);        

        //all tabs are initialized, set tabIndex
        //tab index
        $this->view->selectedTabIndex = array_key_exists($this->selectedTab, $this->tabIndex) ? (int)$this->tabIndex[$this->selectedTab] : $this->view->selectedTabIndex ;
    }   
    
    protected function tabTournement($tabName, $data) 
    {
        $this->tabIndex[$tabName] = ++$this->tabIndexCounter;
        $this->view->loadedTabs = $this->tabIndex;
    	
        $formOptions = array(
            //'noActionDetail' => TRUE,
            'action' => '/' . $this->getRequest()->getControllerName() . '/detail/id/' . $this->id . '/tab/'.$tabName.'/page/detail',
        );
        $formParams = array(
            //'noActionDetail' => TRUE,
            'tabName'    => $tabName,
        );	
        
        $options = array(
            'redirect' => $this->getRequest()->getControllerName() . '/detail/id',//'.$this->id.'/tab/1',
            'redirectToId' => TRUE, //TRUE,
        );

        $data['page'] = 'detail'; //always detail

        $this->processDetail($options, $formParams);
    }   
    
    protected function tabRegistration($tabName, $data) {
        $formOptions = array(
            'action' => '/' . $this->getRequest()->getControllerName() . '/detail/id/' . $this->id . '/tab/'.$tabName.'/page/detail',
        );
        $formParams = array(
            'tabName'    => $tabName,
            'parentId'   => $this->id,
            'ID_Tournement' => $this->id,
            'childId'    => $this->child['id'], // orderId
        );	
      
        $registrationId = (int)$this->child['id'];
        $this->child['form']     = $this->getForm('Registration',$registrationId,$formOptions,$formParams);
        $this->child['model']    = new Application_Model_Registration();
        //datagrid
        $datagridParams          = $this->getRequest()->getParams();
        $datagridParams['tab']   = $tabName;
        $this->child['datagrid'] = $this->child['model']->buildDataGrid($this->id,$datagridParams);
        $this->child['data']     = $data;

        $this->buildTab($tabName,$data);
    }     
    
    protected function tabGame($tabName, $data) {
        $formOptions = array(
            'action' => '/' . $this->getRequest()->getControllerName() . '/detail/id/' . $this->id . '/tab/'.$tabName.'/page/detail',
        );
        $formParams = array(
            'tabName'    => $tabName,
            'parentId'   => $this->id,
            'ID_Tournement' => $this->id,
            'childId'    => $this->child['id'], // orderId
        );	
      
        $gameId = (int)$this->child['id'];
        $this->child['form']     = $this->getForm('Game',$gameId,$formOptions,$formParams);
        $this->child['model']    = new Application_Model_Game();
        //datagrid
        $datagridParams          = $this->getRequest()->getParams();
        $datagridParams['tab']   = $tabName;
        $this->child['datagrid'] = $this->child['model']->buildDataGrid($this->id,$datagridParams);
        $this->child['data']     = $data;

        $this->buildTab($tabName,$data);
    }  
    
    private function buildTab($tabName,$data) {
        $this->tabIndex[$tabName] = ++$this->tabIndexCounter;
        $this->view->loadedTabs = $this->tabIndex;

        $data['tabName'] = $tabName;
        $this->child['data'] = $data;

        $this->view->tabs[$tabName] = $this->child;

         if ($this->selectedTab == $tabName && $data['page']=='detail' ) {
            $options = array(
                'redirectToId' => TRUE, //TRUE,
            );
            if ($tabName == 'order'){
            	$options = array(
            		'redirect'     =>  '/' . $this->getRequest()->getControllerName().'/detail/id/'.$this->id.'/tab/'.$this->selectedTab.'/page/detail' ,
            		'redirectToId' => TRUE,
            		'childName' => 'order',
            	);
            }
            $this->processChildDetail($options, $data);
        } else {
            $data['page'] = ($this->selectedTab == $tabName && $data['page'] == 'detail') ? 'detail' : 'list';
            $this->view->tabs[$tabName]['data'] = $data;
            
            $this->view->tabs[$tabName]['datagrid'] = $this->child['datagrid'];   //param = productId array('productId' => $this->id)
        }
    }
    
}