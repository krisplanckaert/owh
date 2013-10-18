<?php
class My_Filter_OrderReference implements Zend_Filter_Interface
{
	
	
	//public function __construct($options = null){

		
	//}
        public function filter($value)
        {
            // perform some transformation upon $value to arrive on $valueFiltered
        	return preg_replace('/^(\d+)\./','',$value);
        	//$view = new Zend_View();
        	//mp($view->OrderHelper('viewReference',array($value))); exit;
        	//return null;
            //return $view->OrderHelper('viewReference',array($value));
        }

        
        
    }