<?php
class Application_Model_Team extends My_Model
{
    protected $_name = 'teams'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $enableDataGrid = TRUE;      
    protected $onlyActiveRows = TRUE;
    
    public function save($data,$id = NULL) {
        $isUpdate = FALSE;
       
        $dbFields = array(
            'Description' => $data['Description'],
        );

        if (!empty($id)) {
            $isUpdate = TRUE;
            $this->updateById($dbFields,$id);
        } else {
            $id = $this->insert($dbFields);
        }  
    }
    
    public function buildDataGrid() {
     	//1. build source
        $select = $this->db
                        ->select()
                        ->from($this->getTableName())
                        ->where('ID_Status=1')
        ;

        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('team');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        
        $this->dataGrid->updateColumn('Description',array(
                            'title'		=> 'Description',
                            'decorator' => '<a href="/team/detail/id/{{ID}}">{{Description}}</a>',
                            'position'  => 10,
        ));    
        

        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    
}