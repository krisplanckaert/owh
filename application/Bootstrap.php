<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initView()
    {    
        $options = $this->getOptions(); // get contents of application.ini 
        if (isset($options['resources']['view']))
        {
                $view = new Zend_View($options['resources']['view']);
        } else
        {
                $view = new Zend_View();
        }      
        
        //enable jQuery, 2 methods possible
		    //$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
		    ZendX_JQuery::enableView($view);	
		   //$jqueryTheme = 'ui-lightness';
		   // $jqueryTheme = 'pepper-grinder'; 
		    $jqueryTheme = 'smoothness';
		    $view->jQuery()->setLocalPath('/base/js/jquery/jquery.min.js')
		                   ->setUiLocalPath('/base/js/jquery/jquery-ui.min.js')                                   
       	//				   ->addStyleSheet('/base/js/jquery/css/'.$jqueryTheme.'/jquery.ui.all.css')		                   
		                   ->addStyleSheet('/base/js/jquery/css/'.$jqueryTheme.'/jquery-ui.custom.css')
		                   ;    
        //$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        //$view->setHelperPath( APPLICATION_PATH .'views/helpers');
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
        //view helpers
        $view->addHelperPath(APPLICATION_PATH . '/views/helpers');
        $view->addHelperPath('ZendX/JZendXQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $view->addHelperPath('My/View/Helper/', 'My_View_Helper');      
        
        return $view;         
    }
}

