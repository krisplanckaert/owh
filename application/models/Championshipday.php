<?php
class Application_Model_Championshipday extends My_Model
{
    protected $_name = 'championshipdays'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $enableDataGrid = TRUE;      
    protected $onlyActiveRows = TRUE;
    
    public function save($data,$id = NULL) {
        $isUpdate = FALSE;
       
        $dbFields = array(
            'ID_Championship' => $data['ID_Championship'],
            'Description' => $data['Description'],
            'Date' => $data['Date'],
        );

        if (!empty($id)) {
            $isUpdate = TRUE;
            $this->updateById($dbFields,$id);
        } else {
            $id = $this->insert($dbFields);
        }  
        return $id;
    }
    
    public function buildDataGrid() {
     	//1. build source
        $select = $this->db
                        ->select()
                        ->from(array('t' => $this->getTableName()))
                        ->joinleft(array('c' => 'championships'), 'c.ID=t.ID_Championship', array("Championship" => "c.Description"))                                
                        ->where('t.ID_Status=1')
        ;

        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('championshipday');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Championship', array('hidden' => true));        

        $this->dataGrid->updateColumn('Championship',array(
                            'title'		=> 'Chamionship',
                            'position'  => 10,
        ));    
        $this->dataGrid->updateColumn('Date',array(
                            'title'		=> 'Date',
                            'decorator' => '<a href="/championshipday/detail/id/{{ID}}">{{Date}}</a>',
                            'position'  => 20,
        ));    
        

        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    
    
    public function buildDataGrid2($data) {
     	//1. build source
        $select = $this->db
                        ->select()
                        ->from($this->getTableName())
                        ->where('ID_Status=1')
        ;

        if(isset($data['gtDate'])) {
            $select->where('Date > ' . $data['gtDate']);
        }
        if(isset($data['ltDate'])) {
            $select->where('Date <= ' . $data['ltDate']);
        }
        
        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('tournement');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        
        $this->dataGrid->updateColumn('Date',array(
                            'title'		=> '',
                            'decorator' => '<a href="/tournement/registration/id/{{ID}}">{{Date}}</a>',
                            'position'  => 10,
                            'order' => false,            
        ));    
        $this->dataGrid->updateColumn('Description',array(
                            'title'		=> '',
                            'position'  => 5,
                            'order' => false,            
        ));          
        
        $filters = new Bvb_Grid_Filters();
        $this->dataGrid->addFilters($filters);
        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }        
}