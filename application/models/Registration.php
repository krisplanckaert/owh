<?php
class Application_Model_Registration extends My_Model
{
    protected $_name = 'registrations'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $enableDataGrid = TRUE;      
    protected $onlyActiveRows = TRUE;
    
    public function getOverviewByTournement($tournementId) {
        $registrationplayerModel = new Application_Model_Registrationplayer();
        $playerModel = new Application_Model_Player();
        $teamModel = new Application_Model_Team();        
        $where = 'ID_Tournement='.$tournementId;
        $registrations = $this->getAll($where);
        $return = $registrations;
        foreach($registrations as $k => $v) {
            $team = $teamModel->getOne($v['ID_Team']);
            $return[$k]['Team'] = $team['Description'];
            $where = 'ID_Registration='.$v['ID'];
            $rp = $registrationplayerModel->getAll($where);
            $return[$k]['Players'] = array();
            foreach($rp as $k2 => $v2) {
                $player = $playerModel->getOne($v2['ID_Player']);
                $return[$k]['Players'][$k2] = $player['Name'];
            }
        }
        return $return;
    }
    
    public function getOne($id) {
        $row = parent::getOne($id);
        if($row) {
            $row['ID_Player'] = array();            
            $registrationplayerModel = new Application_Model_Registrationplayer();
            $where = 'ID_Registration = ' . $row['ID'];
            $registrationPlayers = $registrationplayerModel->getAll($where);
            foreach($registrationPlayers as $rp) {
                $row['ID_Player'][] = $rp['ID_Player'];
            }
        }
        return $row;
    }
    
    public function save($data,$id = NULL) {
        $registrationplayerModel = new Application_Model_Registrationplayer();        
        $isUpdate = FALSE;
       
        if(!$id) {
            $fields = array(
                'ID_Tournement' => $data['ID_Tournement'],
                'ID_Team' => $data['ID_Team'],
            );
            $registration = $this->getOneByFields($fields, 'AND', false);
            if($registration) {
                $isUpdate = TRUE;
                $dbFields = array(
                    'ID_Status' => 1,
                );
                $id = $registration['ID'];
                $this->updateById($dbFields, $id);                
            }
        }
        $dbFields = array(
            'ID_Tournement' => $data['ID_Tournement'],
            'ID_Team' => $data['ID_Team'],
        );

        if (!empty($id)) {
            $isUpdate = TRUE;
            $this->updateById($dbFields,$id);
        } else {
            $id = $this->insert($dbFields);
        }  
        $registrationPlayers = isset($data['ID_Player']) ? $data['ID_Player'] : null;
        $totalPlayers = $registrationplayerModel->saveRegistrationPlayer($id, $registrationPlayers);    
        
        return $id;
    }
    
    public function buildDataGrid() {
     	//1. build source
        $select = $this->db
                        ->select()
                        ->from(array('r' => $this->getTableName()))
                        ->joinleft(array('t' => 'teams'), 't.ID=r.ID_Team', array("Team" => "t.Description"))
                        ->where('r.ID_Status=1')
        ;

        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('team');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Team', array('hidden' => true));      
        $this->dataGrid->updateColumn('ID_Tournement', array('hidden' => true));         
        
        $this->dataGrid->updateColumn('Team',array(
                            'title'		=> 'Team',
                            'decorator' => '<a href="/tournement/detail/id/{{ID_Tournement}}/tab/registration/page/detail/childId/{{ID}}">{{Team}}</a>',            
                            'position'  => 20,
        ));  
 
        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    
}