<?php
/**
* Setup view variables
*/
class My_Controller_Plugin_ViewSetup extends Zend_Controller_Plugin_Abstract
{
     protected $auth;
    
     public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
     {
         $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
         $viewRenderer->init();
         // set up variables that the view may want to know
         $viewRenderer->view->module     = $request->getModuleName();
         $viewRenderer->view->controller = $request->getControllerName();
         $viewRenderer->view->action     = $request->getActionName();
         
         $session = new Zend_Session_Namespace('translation');
         //if (Zend_Registry::isRegistered('selected_language')){
         if (isset($session->translate) && !empty($session->translate)){
         	$viewRenderer->view->selectedLang = $session->translate['lang'];  //;Zend_Registry::get('selectedLang');         	
         }


     }

} 
