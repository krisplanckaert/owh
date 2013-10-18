<?php
class My_Validate_Name extends Zend_Validate_Abstract
{
	 const IS_EMPTY = 'isEmpty';
	 const VALID    = 'validFieldName';
	 const INVALID  = 'invalidFieldName';
	 const FAILED   = 'failedFieldName';
	 
	 
  /**
   * @var array
   */
  protected $_messageTemplates = array(
  	self::IS_EMPTY  => 
  		"Value is required and can't be empty",
  	self::INVALID	=>
  		"'%value%' is not valid",
  	self::FAILED	=>
  		'%fieldName% failed',
//    self::MISSING_FIELD_NAME  =>
//      'DEVELOPMENT ERROR: Field name to match against was not provided.',
//    self::INVALID_FIELD_NAME  =>
//      'DEVELOPMENT ERROR: The field "%fieldName%" was not provided to match against.',
//    self::NOT_MATCH =>
//      'Does not match %fieldTitle%.'
  );

  /**
   * @var array
   */
  protected $_messageVariables = array(
    'fieldName' => '_fieldName',
    'fieldTitle' => '_fieldTitle'
  );

  /**
   * Name of the field as it appear in the $context array.
   *
   * @var string
   */
  protected $_fieldName;
  
  
  protected $_isRequired;
  

  /**
   * Title of the field to display in an error message.
   *
   * If evaluates to false then will be set to $this->_fieldName.
   *
   * @var string
  */
  protected $_fieldTitle;

  /**
   * Sets validator options
   *
   * @param  string $fieldName
   * @param  string $fieldTitle
   * @return void
  */
  public function __construct($fieldName, $fieldRequired = FALSE, $fieldTitle = NULL) {
    $this->setFieldName($fieldName);
    $this->setFieldTitle($fieldTitle);
    
    $this->_isRequired = (bool)$fieldRequired;
  }

  /**
   * Returns the field name.
   *
   * @return string
  */
  public function getFieldName() {
    return $this->_fieldName;
  }

  /**
   * Sets the field name.
   *
   * @param  string $fieldName
   * @return Zend_Validate_IdenticalField Provides a fluent interface
  */
  public function setFieldName($fieldName) {
    $this->_fieldName = $fieldName;
    return $this;
  }

  /**
   * Returns the field title.
   *
   * @return integer
  */
  public function getFieldTitle() {
    return $this->_fieldTitle;
  }

  /**
   * Sets the field title.
   *
   * @param  string:null $fieldTitle
   * @return Zend_Validate_IdenticalField Provides a fluent interface
  */
  public function setFieldTitle($fieldTitle = NULL) {
    $this->_fieldTitle = $fieldTitle ? $fieldTitle : $this->_fieldName;
    return $this;
  }

    
/**
   * Defined by Zend_Validate_Interface
   *
   * Returns true if and only if a field name has been set, the field name is available in the
   * context, and the value of that field name matches the provided value.
   *
   * @param  string $value
   * @param  $context
   * @return boolean
  */
    public function isValid($value, $context = NULL)
    {
       	$firstName = $lastName = '';
        if (is_array($value)){     	//array
    		$firstName = isset($value['firstName']) ? $value['firstName'] : '';
    		$lastName  = isset($value['lastName']) ? $value['lastName'] : '';
    	}
    	else { 
    	//string
    		if (!empty($value)){ 
    			$data = explode(' ',$value);
    			$firstName = isset($data[0]) ? $data[0] : '';
    			$lastName  = isset($data[1]) ? $data[1] : '';
    		}    		    		
    	}
    	//firstname and last name are required
		//if ($this->_isRequired && (empty($firstName) || empty($lastName))){
                if ($this->_isRequired && empty($lastName)){
			$this->_error(self::IS_EMPTY);
			return FALSE;
		}
  	    	
        return TRUE;

    }    
}
