<?php

/**
 * Status helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_StatusHelper extends Zend_View_Helper_Abstract
{
	
    public $view;
	
    protected $model;
	
	
    /**
     * setView
     * @see Zend_View_Helper_Abstract::setView()
     *
     * If a helper class has a setView() method, it will be called when the helper class
     * is first instantiated, and passed to the current view object.
     */
    public function setView(Zend_View_Interface $view){
        $this->view = $view;
    }


    /**
    * Workaround to call multiple methods
    * @param string $method
    * @param array $args
    */
    public function StatusHelper($method,$args=NULL) {
	$thisClass    = get_class();
        $classMethods = get_class_methods($thisClass);
        // case the method exists into this class  //
        if(in_array($method,$classMethods)) {
            $arrCaller = array($thisClass,$method);
            return call_user_func_array($arrCaller, $args );
        }
	//method not found in this class
     	throw new Exception("Method ".$method." doesn't exist in class " .$thisClass."." ); 	
    }
	
 
    public function getName($statusId) {
        //var_dump($data); exit;
        $this->model = new Application_Model_Status();
        $status = $this->model->getOne((int)$statusId);

        //Zend_Debug::dump($status); exit;
        if (!empty($status)){
            return $status['Omschrijving'];
        }
        return FALSE;
    }
/*
 public function getImageLock($statusId)
 {
 	if ($statusId == 2){ ?>
 	    <img src="/base/images/icons/encrypted.png" />
<?php 	}
 }
 */

 public function getImageLock($statusId) {
 	$img = '';
 	if ($statusId == 2){
 	    $img = '<img src="/base/images/icons/encrypted.png" />';
 	}
 	return $img;
 }

 
}

