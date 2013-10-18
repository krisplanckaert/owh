<?php
class Application_Model_Teamuser extends My_Model
{
    protected $_name = 'teamusers'; //table name
    protected $_id   = 'ID'; //primary key

    protected $enableDataGrid = TRUE;    
   
    public function getOne($id = NULL) {
        $id = !$id ? (int)$this->authUserId : $id;
    	$row = parent::getOne($id);
    	if (!empty($row)){
            $row['ID_Permissions'] = array();
            $row['ID_Permissions'][] = $row['ID_Permission'];
        }  
        return $row;
    }
    
    public function save($data,$id = NULL) {
        $isUpdate = FALSE;
//Zend_Debug::dump($data);exit;         
        $dbFields = array(
            'ID_Team' => $data['ID_Team'],
            'ID_Status' => $data['ID_Status'],
            'Username'  => $data['Username'],
        );
        if (!empty($data['Password']) && $data['Password']== $data['repeatPassword']){
            $dbFields['Password'] = md5($data['Password']);
        }
        if (!empty($data['ID_Permission'])){
            $dbFields['ID_Permission'] = $data['ID_Permission'];
        }
        
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
                                ->from(array('tu' =>$this->getTableName()))
                                ->joinleft(array('t' => 'teams'), 't.ID=tu.ID_Team', array("Team" => "t.Description"))
                                ->order('Username')
        ;
        if (!$this->getAcl()->userIsAllowed(My_Roles::ADMIN)){
            $select->where('ID_Team='.$this->authUser['ID_Team']);
        }
        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('teamuser');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Team', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Permission', array('hidden' => true));       
        $this->dataGrid->updateColumn('Password', array('hidden' => true));
        
        $this->dataGrid->updateColumn('Username',array(
                            'title'		=> 'E-mail',
                            'decorator' => '<a href="/teamuser/detail/id/{{ID}}">{{Username}}</a>',
                            'position'  => 10,
        ));        
        
        $this->dataGrid->updateColumn('ID_Status', array(
                                                'title' => 'Status',
                                                'position'  => 30,            
                                                'style' => 'text-align:center;',
                                                'helper'    => array(
                                                                    'name'=> 'StatusHelper',
                                                                    'params'=>array(
                                                                            'getImageLock',array('{{ID_Status}}'))
                                                            )
                                            )
        );

        $this->dataGrid->updateColumn('Team',array(
                            'title'		=> 'Team',
                            'position'  => 20,
        ));         
        $statusModel = new Application_Model_Status();
        $statusList  = $statusModel->buildSelect(); //array('1' => 'Actief','2' => 'Inactief');
        $filters = new Bvb_Grid_Filters();
        $filters->addFilter('ID_Status',array(
                        'values' => $statusList));
        $filters->addFilter('Username');
        $this->dataGrid->addFilters($filters);
        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    
}