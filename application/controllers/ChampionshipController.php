<?php
class ChampionshipController extends My_Controller_Action 
{   
    protected $child = array();
    
    protected $formMultiple = TRUE;
    
    protected $formData	 = null;
    
    protected $tabIndex = array();
    protected $tabIndexCounter = -1;
    protected $selectedTabIndex = 0;    
    
    protected $parentTab = 'championship';

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
    
    public function detailAction() {
        $data = $this->_getAllParams(); //get all params from all tabs, @todo check!

        $this->view->selectedTabIndex = $this->selectedTabIndex;
        $this->view->loadedTabs = $this->tabIndex;
        
        $this->formMultiple = TRUE;
        $this->id          = (int)$this->_getParam('id'); // get tournementId
        $this->selectedTab = $this->view->selectedTab = $this->_getParam('tab','championship');

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
            $this->view->championship = $this->modelData;
        }

        // ---------------
        // Load Tabs
        // ---------------------------------
      	$this->child['id'] = $this->_getParam('childId', NULL);	  
      	$this->view->tabs = array(
			'championship'	 => '',
			'tournement'   => '',
        );
    	//tab 1 = parent tab
        $this->tabChampionship('championship',$data);
        if (empty($this->id) || empty($this->modelData)){
            //parent is  unknown, so we can't load other tabs because they are related
            return;
        }
        $this->tabTournement('tournement',$data);

        //all tabs are initialized, set tabIndex
        //tab index
        $this->view->selectedTabIndex = array_key_exists($this->selectedTab, $this->tabIndex) ? (int)$this->tabIndex[$this->selectedTab] : $this->view->selectedTabIndex ;
    }   
    
    protected function tabChampionship($tabName, $data) 
    {
        $this->tabIndex[$tabName] = ++$this->tabIndexCounter;
        $this->view->loadedTabs = $this->tabIndex;
    	
        $formOptions = array(
            'action' => '/' . $this->getRequest()->getControllerName() . '/detail/id/' . $this->id . '/tab/'.$tabName.'/page/detail',
        );
        $formParams = array(
            'tabName'    => $tabName,
        );	
        
        $options = array(
            'redirect' => $this->getRequest()->getControllerName() . '/detail/id',//'.$this->id.'/tab/1',
            'redirectToId' => TRUE, //TRUE,
        );

        $data['page'] = 'detail'; //always detail
        $this->processDetail($options, $formParams);
    }   
    
    protected function tabTournement($tabName, $data) {
        $tournementModel = new Application_Model_Tournement();
        $where = 'ID_Championship = ' . $data['id'];
        $this->view->tabs[$tabName] = $tournementModel->getAll($where);
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