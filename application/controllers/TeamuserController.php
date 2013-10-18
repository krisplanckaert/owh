<?php
class TeamuserController extends My_Controller_Action 
{   
	
    public function myProfileAction() {

    	$this->id = $this->authUser['ID']; //$this->getRequest()->getParam('id');
    	$this->formOptions['action'] = '/teamuser/my-profile';
    	$this->processDetail();
    }


    public function loginAction() {

        $this->_helper->layout->disableLayout();
        $form = $this->getForm('Login');
        if (!$this->getRequest()->isPost()) {
            $this->view->data = $this->getRequest()->getParams(); //$this->_getParam('page');
            return false;
        }
        
        $this->_helper->viewRenderer->setNoRender();
        $formData = $values = $this->_request->getPost();

        if (!$form->isValid($formData)) {
            $formErrors = array('err' => 1); //$form->getErrors(); //
            $this->_helper->redirector('login',$this->getRequest()->getControllerName(),$this->getRequest()->getModuleName(),$formErrors);
            return;
        }

        // Setup DbTable adapter
        $adapter = new Zend_Auth_Adapter_DbTable(
            Zend_Db_Table::getDefaultAdapter() // set earlier in Bootstrap
        );
        $adapter->setTableName('teamusers'); //table 
        $adapter->setIdentityColumn('Username'); //table username col
        $adapter->setCredentialColumn('Password'); //table password col
        $adapter->setIdentity($values['Username']);//form username
        $adapter->setCredential($values['Password']); //form password
        $adapter->setCredentialTreatment('md5(?) AND ID_Status = 1');

        // authentication attempt
        $auth = Zend_Auth::getInstance();
        //Zend_Debug::dump($adapter);exit;
        $result = $auth->authenticate($adapter); 

        // authentication OK
        if ($result->isValid())
        { //auth OK
            $auth->getStorage()
                ->write($adapter->getResultRowObject(null, "password"));

            $authUser = $auth->getIdentity();
            if (!empty($authUser->initials)) {
                $this->_helper->redirector('home', 'index');
            }
            else {
                $this->_helper->redirector('home', 'index');
            }
        } else { // auth error! Back to the login page!
            $formErrors = array('err' => 1); 
            $this->_helper->redirector('login',$this->getRequest()->getControllerName(),false,$formErrors);
        }
    }

    public function lostAction(){
        $this->_helper->layout->disableLayout();
        if (!$this->getRequest()->isPost()) {
            $this->view->data = $this->getRequest()->getParams();
            return;
        }
        $data =  $this->_request->getPost();
        if (empty($data['username'])){
            return;
        }
        $teamuserModel = new Application_Model_Teamuser();
        $userRow   = $data = $teamuserModel->getOneByField('Username',$data['Username']);
        if (empty($userRow)){
            $params = array('err' => 1);
            $this->_helper->redirector('lost',$this->getRequest()->getControllerName(),false,$params);
        }
        try {
            $templateName = My_Controller_Plugin_Mail::TEMPLATE_LOST_PASSWORD;
            $data['eId'] = $userModel->saveIdentifier($userRow['ID']);
            $data['email'] = $userRow['username'];
            $data['url']   = $this->getFullUrl() .'/teamuser/reset/eId/' . $data['eId'];
            $this->mail->send($templateName,$data);
            $this->_redirect('/Index/Home');
        } catch (Exception $e){
            throw $e;
        }
    }
    
    public function resetAction(){
    	$this->_helper->layout->disableLayout();
    	$data = $this->getRequest()->getParams();
    	$save = false;
    	$eId = $this->getRequest()->getParam('eId');
    	
    	if ($this->getRequest()->isPost()) {    	  
    	    //submit
    	    $data =  $this->_request->getPost();
    	    $save = true;   
    	}
    	$this->view->data = $data;
    	$userModel = new Application_Model_Teamuser();
    	$userRow   = $userModel->getOneByField('eId',(string)$eId);
    	//$userRow   = $userModel->getOneByField('eId',$eId); 
    	//Zend_Debug::dump($userRow); exit;
    	if (empty($userRow)){ //die('oei, user not found');
    	    $url = '/' . $this->getRequest()->getControllerName().'/reset/eId/'.$eId.'/err/3';
    	    $this->_redirect($url);    	    
            return;
    	}
    	$this->view->user = $userRow;
    	if (!$save){
    		return;
    	}
        if (empty($data['password1']) || strlen($data['password1'])<6){
            $url = '/' . $this->getRequest()->getControllerName().'/reset/eId/'.$eId.'/err/1';
            $this->_redirect($url);
            return;
        }
        if ($data['password1'] !==$data['password2']){
            $url = '/' . $this->getRequest()->getControllerName().'/reset/eId/'.$eId.'/err/2';
            $this->_redirect($url);
            $this->_helper->redirector('reset',$this->getRequest()->getControllerName(),false,array('eId' => $eId,'err' => 2));
            return;
        }
    	//save new password
        try{
            $dbFields = array(
                                    'eId' => null,
                                    'password' => md5($data['password1']),
            );
            $total = $userModel->updateById($dbFields,$userRow['ID']);
            $this->_helper->redirector('login',$this->getRequest()->getControllerName(),false,array('msg' => 1));
        }
        catch (Exception $e){
            $url = '/' . $this->getRequest()->getControllerName().'/reset/eId/'.$eId.'/err/3';
            $this->_redirect($url);
        }
    }

    /**
     * Log out a user
     */
    public function logoutAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector('login',$this->getRequest()->getControllerName(),NULL,array('msg' => 'logout'));
    }
      
}