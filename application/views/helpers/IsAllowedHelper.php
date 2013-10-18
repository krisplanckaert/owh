<?php 
/**
 * Acl, is allowed
 * Check if user is allowed a role, resource and/or privilege
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_IsAllowedHelper extends Zend_View_Helper_Abstract
{
    
    public $view;
    
    protected $authUser;
    protected $acl;
    
    
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
    
     public function isAllowedHelper( $role = null, $resource = null, $privilege = null)
     {
         $this->authUser = (array)Zend_Auth::getInstance()->getIdentity();
         $this->acl = new My_Acl($this->authUser);
         //$acl = Zend_Registry::get('acl');
         return $this->acl->userIsAllowed($role, $resource, $privilege);
     }
} 

