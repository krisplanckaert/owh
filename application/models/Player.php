<?php
class Application_Model_Player extends My_Model
{
    protected $_name = 'players'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $enableDataGrid = TRUE;      
    protected $onlyActiveRows = TRUE;
    
    public function save($data,$id = NULL) {
        $isUpdate = FALSE;
       
        $dbFields = array(
            'Name' => $data['Name'],
            'Email' => $data['Email'],
            'Player' => $data['Player'],
            'Referee' => $data['Referee'],
            'Coach' => $data['Coach'],
            'Captain' => $data['Captain'],
        );
        
        if(isset($data['ID_Team'])) {
            $dbFields['ID_Team'] = $data['ID_Team'];
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
                        ->from(array('p' => $this->getTableName()))
                        ->joinleft(array('t' => 'teams'), 't.ID=p.ID_Team', array("Team" => "t.Description"))
                        ->where('p.ID_Status=1')
        ;
        if (!$this->getAcl()->userIsAllowed(My_Roles::ADMIN)){
            $select->where('ID_Team='.$this->authUser['ID_Team']);
        }
        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('player');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Team', array('hidden' => true));
        
        $this->dataGrid->updateColumn('Name',array(
                            'title'		=> 'Name',
                            'decorator' => '<a href="/player/detail/id/{{ID}}">{{Name}}</a>',
                            'position'  => 10,
        ));   
        $this->dataGrid->updateColumn('Email',array(
                            'title'		=> 'Email',
                            'position'  => 15,
        ));                
        $this->dataGrid->updateColumn('Team',array(
                            'title'		=> 'Team',
                            'position'  => 20,
        ));          
        $this->dataGrid->updateColumn('Player',array(
                            'title'		=> 'Player',
                            'position'  => 30,
        ));
        $this->dataGrid->updateColumn('Referee',array(
                            'title'		=> 'Referee',
                            'position'  => 40,
        ));
        $this->dataGrid->updateColumn('Coach',array(
                            'title'		=> 'Coach',
                            'position'  => 50,
        ));
        $this->dataGrid->updateColumn('Captain',array(
                            'title'		=> 'Captain',
                            'position'  => 60,
        ));        
        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    
}