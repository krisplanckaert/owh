<?php

class My_Acl extends Zend_Acl 
{
    protected $authUser;
    protected $userRoles;

    /**
     *
     * Constructor
     * @param array $authUser
     */
    public function __construct($authUser) {
        $this->authUser = $authUser; 
        $this->add(new Zend_Acl_Resource(My_Resources::EDIT_TEAM));

        // roles
        $this->addRole(new Zend_Acl_Role(My_Roles::ADMIN));
        $this->addRole(new Zend_Acl_Role(My_Roles::TEAMUSER));

        // permissions
        $this->allow(My_Roles::ADMIN, My_Resources::EDIT_TEAM); //admin can edit a dealer
      
        Zend_Registry::set('acl',$this);

        $userModel = new Application_Model_Teamuser();
        $this->user = $userModel->getOne();
        $this->setUserRoles();        
    }
        
    protected function setUserRoles(){
        $this->userRoles = null;
        if (empty($this->user)){
            return false;
            //throw new Exception ("User must have at least 1 permission");
        }
        
        switch($this->user['ID_Permission']){
            case 1:
                $this->userRoles[] = My_Roles::ADMIN;
                break;
            case 2:
                $this->userRoles[] = My_Roles::TEAMUSER;
                break;
        }
    }
    
    public function hasRole($role){
        if (is_array($this->userRoles) && in_array($role,$this->userRoles)){
            return TRUE;
        }
        return FALSE;
    }
        
    public function getUserRoles() {
        return $this->userRoles;
    }


    /**
     *
     * User is allowed
     * @param mixed $role
     * @param mixed $resource
     * @param mixed $privilege
     * @return boolean
     */
    public function userIsAllowed($role = null, $resource = null, $privilege = null){
        if (empty($this->userRoles)){
            return FALSE;
        }
        if (empty($resource) && empty($privilege)){
            //we only need to check the role
            return in_array($role,$this->userRoles) ? true : false;
        }

        foreach($this->userRoles as $userRole){
            if ($this->isAllowed($userRole,$resource,$privilege)){
                return TRUE;
            }
        }
        return FALSE;
    }
}
?>