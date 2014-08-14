<?php
class GameController extends My_Controller_Action 
{   
    public function processAction()
    {
    	if (!$this->getRequest()->isPost()) {
    	 	throw new Exception('Only form post processing');
    	}	
    	$this->id = (int)$this->_getParam('id'); // = gameId
    	$tabName  = $this->_getParam('tab','game');
    	$data     = $this->getRequest()->getPost();
        //Zend_Debug::dump($data);exit;
    	if (isset($data['btn_detailListUpdate']) ){
            $gameModel = new Application_Model_Game();
            $totalUpdate = $gameModel->saveGame((array)$data['Referee1'],(array)$data['Referee2'],(array)$data['RefereeHead'], (array)$data['Black'], (array)$data['White']);
            $this->flashMessenger->addMessage((int)$totalUpdate.' rows updated');      
            $targetUrl =  '/championshipday/detail/id/' . $this->id . '/tab/'.$tabName.'/page/list';     
            $this->_redirect($targetUrl); exit; 		    		
    	}    	
    }
}