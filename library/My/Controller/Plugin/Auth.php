<?php
class My_Controller_Plugin_Auth extends Zend_Controller_Plugin_Abstract
{   
	
    protected $appData;
	
    /**
     * @var array
     */
    private $_excludeAuthActions = array(
        'index'            => array('index','login','maintenance','under-construction'),
        'teamuser'        => array('login'),
    );

    
    
    protected function setApplicationData(){
    	$data = array(
    		'authController' => 'teamuser',
    	);
    	$this->appData = $data;
    }
    
   
    /**
     * Authentication 
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {


        // Determine which error controller to use for the different modules
        $front = Zend_Controller_Front::getInstance();
        if (!($front->getPlugin('Zend_Controller_Plugin_ErrorHandler')
                instanceof Zend_Controller_Plugin_ErrorHandler)) {
            return;
        }

        $controllerName = $request->getControllerName();
        $actionName     = $request->getActionName();        
        if (array_key_exists($controllerName,$this->_excludeAuthActions)
            && in_array($actionName,$this->_excludeAuthActions[$controllerName])) {
            // no need to check auth
            return;
        }

        if(!Zend_Auth::getInstance()->hasIdentity()) {
//die('dispatchLoopStartup redirect');
            // @todo shouldn't we need to display a no access page instead of the login?
            // no access
            $this->setApplicationData();
            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $redirector->gotoUrl($this->appData['authController'] . '/login');
        }
        

    }
    
}
