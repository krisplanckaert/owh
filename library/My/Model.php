<?php

abstract class My_Model extends Zend_Db_Table_Abstract 
{

    protected $errors = array();
    
    public $db;
	
    protected $dataGrid;
    protected $enableDataGrid = FALSE;
    
    
    protected $data;
    
    protected $authUser;
    
    protected $autoCompleteFields = FALSE;
    
    protected $acl;

    protected $authUserId;    
    
    protected $onlyActiveRows = FALSE;
    
 // -----------------------------------------
    public function init()
    {
    	$this->db = $this->getAdapter();
        if ($this->enableDataGrid){
            $dataGrid       = new My_DataGrid();
            $this->dataGrid = $dataGrid->getGrid();
        }    	
        $this->authUser = (array) Zend_Auth::getInstance()->getIdentity();
        if (!empty($this->authUser)){
            if(isset($this->authUser['ID'])) {
                $this->authUserId = $this->dealerGebruikerId = $this->authUser['ID'];
            }

        }
       
        $session = new Zend_Session_Namespace('translation');
   	    if (isset($session->translate) && !empty($session->translate)){
         	$this->selectedLang = $session->translate;       	
        }   
    }
    
    public function __construct($config = array())
    {
        parent::__construct($config);      
    }    
    
    public function getAuthUser(){
    	return $this->authUser;    	
    }
    
// --------------------------
// ACL
// --------------------------
    public function setAcl(My_Acl $acl)
    {
    	$this->acl = $acl;
    }
    
    public function getAcl()
    {
    	if (null === $this->acl) {
    		$this->setAcl(new My_Acl($this->authUser));
    	}
    	return $this->acl;
    }

 // -------------------------
 // CRUD
    public function getOne($id,$colName = 'ID')
    {
        $where  = $this->onlyActiveRows ? 'ID_Status = 1 AND ' : '';
    	$where .= $colName . ' = ' .(int)$id;
        $row = parent::fetchRow($where);            
        if (!$row) {
            return FALSE; 
        }
        $this->data = $row->toArray();
        return $this->data;
    }
    
    public function getOneByField($fieldName,$fieldValue){
    	$where  = $this->onlyActiveRows ? 'ID_Status = 1 AND ' : '';
    	$where .= $fieldName .' = ' .$this->db->quote($fieldValue);  
    	$row = parent::fetchRow($where);    
    	      
        if (!$row) {
            return FALSE; 
        }
        return $row->toArray();    	
    }
    
    
    public function getOneByFields(array $fields,$operator = 'AND', $onlyActiveRows = null){
        if($onlyActiveRows === null) {
            $where = $this->onlyActiveRows ? 'ID_Status = 1' : '0 = 0'; 
        } else {
            $where = $onlyActiveRows ? 'ID_Status = 1' : '0 = 0';             
        }
    	foreach($fields as $k=>$v){
    		$where .= ' '. $operator . ' ' . $k . '=' . $this->db->quote($v);
    	}
    	$row = parent::fetchRow($where);            
        if (!$row) {
            return FALSE; 
        }
        return $row->toArray();    	
    }    
    
    public function getAll($where=null,$order=null)
    {
    	if ($this->onlyActiveRows){
    		$whereBase = 'ID_Status = 1';
    		$where     = !empty($where) ? $whereBase . ' AND ' . $where : $whereBase;
    	}
    	$data = $this->fetchAll($where,$order);
        return $data->toArray();
    }    

    public function getData(){
    	return $this->data;
    }
    
    public function setData(array $data){
    	$this->data = $data;
    }
    

    
	/**
     * 
     * Soft Delete by id
     * @param integer $id
     * @param string $primaryKey : name of primary key, default id specified in model
     * @return total affected
     * Update ID_Status
     */
    public function softDeleteById($id,$primaryKey = '')
    {
       $primaryKey = !empty($primaryKey) ? $primaryKey : $this->_id;
       if (empty($id)){
       		return FALSE;
       }
       $dbFields = array(
       		'ID_Status' => 2, // 1 = active, 2 = delete
       );	
       return parent::update($dbFields,$primaryKey . ' = ' . (int)$id);
    }
    
    /**
     * 
     * Delete by id
     * @param mixed array|integer $id
     * @param string $primaryKey : name of primary key, default id specified in model
     * @param int : number of deleted rows
     */
    public function deleteById($id,$primaryKey = '')
    {
       $primaryKey = !empty($primaryKey) ? $primaryKey : $this->_id;
       if (!is_array($id)){
       		$id = array((int)$id);       	
       }
       if (empty($id)){
       		return FALSE;
       }       
       return parent::delete($primaryKey . ' IN (' . implode(',',$id) . ')');
    }
    

    public function buildSelectFromQuery($sql,$options = NULL){
      	$defaultOptions = array(
    		'key'      => $this->_id,
    		'value'    => 'Omschrijving',
    		'emptyRow' => TRUE,
    		'where'    => NULL,
    		'order'	   => NULL,
    	);
        $options = !empty($options) && is_array($options) ? array_merge($defaultOptions,(array)$options) : $defaultOptions;
    	$data = $this->db->fetchAll($sql);
    	if (empty($data)){
    		return array();
    	}
    	$returnData = array();
    	if ($options['emptyRow']){
    		$returnData[''] = '';
    	}
    	foreach($data as $row){
    		$returnData[$row[$options['key']]] = $row[$options['value']];
    	}    	
    	return $returnData;  	
    	
    }
    
    public function buildSelect($options = NULL){
    	$defaultOptions = array(
    		'key'      => $this->_id,
    		'value'    => 'Description',
    		'emptyRow' => TRUE,
    		'where'    => NULL,
    		'order'	   => NULL,
    	);
        $options = !empty($options) && is_array($options) ? array_merge($defaultOptions,(array)$options) : $defaultOptions;
    	$data = $this->getAll($options['where'],$options['order']);
    	if (empty($data)){
    		return array();
    	}
    	$returnData = array();
    	if ($options['emptyRow']){
    		$returnData[''] = '';
    	}
    	foreach($data as $row){
            $returnData[$row[$options['key']]] = $row[$options['value']];
    	}    	
    	return $returnData;
    }   
    
    public function buildSelectFromArray($data = array(),$options = NULL){
    	$defaultOptions = array(
    		'key'      => $this->_id,
    		'value'    => 'Omschrijving',
    		'emptyRow' => TRUE,
    	);
   		$options = !empty($options) && is_array($options) ? array_merge($defaultOptions,(array)$options) : $defaultOptions;
    	//$data = $this->getAll();
    	if (empty($data)){
    		return array();
    	}
    	$returnData = array();
    	if ($options['emptyRow']){
    		$returnData[''] = '';
    	}
    	foreach($data as $row){
    		$returnData[$row[$options['key']]] = $row[$options['value']];
    	}    	
    	return $returnData;
    }      
  	
 // -------------------------   
    public function getTable()
    {    
    	return $this->getTableName();
    }
    
    public function getTableName()
    {    
    	return $this->_name;
    }    
 
    public function fetchSearchResults($keyword)
    {
        $result = $this->getTable()->fetchSearchResults($keyword);
        return $result;
    } 
    
    /**
     * Check on errors
     *
     * @return bool
     */
    public function hasErrors()
    {
        if (!empty($this->errors)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set 1 error message
     *
     * @param string $msg
     */
    public function addError($msg)
    {
        if (!empty($msg)) {
            $this->errors = (string) $msg;
        }
    }

    /**
     * Set error messages
     *
     * @param array $msg
     */
    public function addErrors($msg)
    {
        if (!empty($msg) && is_array($msg)) {
            $this->errors = array_merge($this->errors, $msg);
        }
    }

    /**
     * Checks if 2 arrays are equal
     * @param array $a, array 1
     * @param array $b, array 2
     * @param bool $strict, true if you want to type check
     */
    public function array_equal($a, $b, $strict = FALSE)
    {
        if (count($a) !== count($b)) {
            return FALSE;
        }   
        sort($a);
        sort($b);
        return ($strict && $a === $b) || $a == $b;
    }
       

    public function VerwijderbyOrderProductVariant($id_orderproductvariant) {
        $sql = 'Delete from ' . $this->_name . ' where ID_OrderProductVariant = ' . (int)$id_orderproductvariant;
        $query = $this->db->query($sql);

    }

    /**
     * Insert
     * @return int last insert ID
     */
    public function insert($data,$autoCompleteFields = false) {
    	if ($autoCompleteFields){
            $currentTime = date("Y-m-d H:i:s");
            $data['ID_CreationDealerGebruiker'] = (int)$this->dealerGebruikerId ? (int)$this->dealerGebruikerId : 2;
            $data['CreationDate'] = $currentTime;
    	}
        return parent::insert($data);
    }

    
    public function updateById(array $data,$id,$primaryKey = '')
    {    	
       $primaryKey = !empty($primaryKey) ? $primaryKey : $this->_id;
       if (empty($id)){
       		return FALSE;
       }
       return parent::update($data,$primaryKey . ' = ' . (int)$id); 	
    }
    
    
    /**
     * Update
     * 
     * @param array  $data: fields to update
     * @param mixed int/string $where : 
     * @return int numbers of rows updated
     */
    public function update($data, $where) {
    	if (preg_match('/^([0-9])+$/', $where)) {
    		return $this->updateById($data,(int)$where);
    	}
     
        return parent::update($data, $where);
    }
    
    public function deleteByStatus($id,$primaryKey = '') {
    	return $this->softDeleteById($id,$primaryKey);
    }
    
    
    /*
     * Check if number is decimal
     */
	public function isDecimal($v) {
    	return (floor($v) != $v);//if the number is not a whole number then its a decimal.
	}
	
	
	/**
	 * Convert date of format dd/mm/yyyy to format yyyy-mm-dd
	 */	
	public function convertDateToMysqlDate($convertDate){
		$date1 = explode('/',$convertDate);

		if ( (!is_array($date1)) || (count($date1)!=3) || (!checkdate($date1[1],$date1[0],$date1[2])) ){
			return FALSE;
		}
		#CHECK OK => convert to mysql-date
		return ($date1[2].'-'.$date1[1].'-'.$date1[0]);#YYYY-MM-DD
	
	} #[end function]

    public function getTranslationGroupId() {
        return $this->translationGroupId;
    }

    public function getDB() {
        return $this->getAdapter();
    }

}
