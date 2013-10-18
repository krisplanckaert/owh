<?php
class My_Validate_Contact extends Zend_Validate_Abstract {
    const IS_EMPTY = 'isEmpty';
    const VALID    = 'validFieldName';
    const INVALID  = 'invalidFieldName';
    const FAILED   = 'failedFieldName';
	 
    protected $_messageTemplates = array(
  	self::IS_EMPTY  => 
  		"Value is required and can't be empty",
  	self::INVALID	=>
  		"'%value%' is not valid",
  	self::FAILED	=>
  		'%fieldName% failed',
    );
    protected $_messageVariables = array(
        'fieldName' => '_fieldName',
        'fieldTitle' => '_fieldTitle'
    );
    protected $_fieldName;
    protected $_isRequired;
    protected $_fieldTitle;

    public function __construct($fieldName, $fieldRequired = FALSE, $fieldTitle = NULL) {
        $this->setFieldName($fieldName);
        $this->setFieldTitle($fieldTitle);

        $this->_isRequired = (bool)$fieldRequired;
    }

    public function getFieldName() {
        return $this->_fieldName;
    }

    public function setFieldName($fieldName) {
        $this->_fieldName = $fieldName;
        return $this;
    }

    public function getFieldTitle() {
        return $this->_fieldTitle;
    }

    public function setFieldTitle($fieldTitle = NULL) {
        $this->_fieldTitle = $fieldTitle ? $fieldTitle : $this->_fieldName;
        return $this;
    }

    public function isValid($value, $context = NULL) {
       	$telefoon = $fax = $gsm = $email = '';
        if (is_array($value)){     	//array
            $telefoon = isset($value['telefoon']) ? $value['telefoon'] : '';
            $fax  = isset($value['fax']) ? $value['fax'] : '';
            $gsm  = isset($value['gsm']) ? $value['gsm'] : '';
            $email  = isset($value['email']) ? $value['email'] : '';
    	} else {
            //string
            if (!empty($value)){
                $data = explode(' ',$value);
                $telefoon = isset($data[0]) ? $data[0] : '';
                $gsm  = isset($data[1]) ? $data[1] : '';
                $email  = isset($data[2]) ? $data[2] : '';
                $fax  = isset($data[3]) ? $data[3] : '';
            }
        }
    	//firstname and last name are required
        if ($this->_isRequired && (empty($telefoon) && empty($fax) && empty($gsm) && empty($email))){
            $this->_error(self::IS_EMPTY);
            return FALSE;
        }
  	    	
        return TRUE;

    }    
}
