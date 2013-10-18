<?php
class Application_Model_Game extends My_Model
{
    protected $_name = 'games'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $enableDataGrid = TRUE;      
    protected $onlyActiveRows = TRUE;
    
    public function save($data,$id = NULL) {
        $isUpdate = FALSE;
       
        $dbFields = array(
            'ID_Tournement' => $data['ID_Tournement'],
            'ID_Field' => $data['ID_Field'],
            'ID_TeamWhite' => $data['ID_TeamWhite'],
            'ID_TeamBlack' => $data['ID_TeamBlack'],
            'ID_PlayerReferee1' => $data['ID_PlayerReferee1'] ? $data['ID_PlayerReferee1'] : NULL,
            'ID_PlayerReferee2' => $data['ID_PlayerReferee2'] ? $data['ID_PlayerReferee1'] : NULL,
            'ID_PlayerRefereeHead' => $data['ID_PlayerRefereeHead'] ? $data['ID_PlayerRefereeHead'] : NULL,
            'ID_TeamReferee1' => $data['ID_TeamReferee1'],
            'ID_TeamReferee2' => $data['ID_TeamReferee2'],
            'ID_TeamRefereeHead' => $data['ID_TeamRefereeHead'],
            'ScoreWhite' => $data['ScoreWhite']=='' ? NULL : $data['ScoreWhite'],
            'ScoreBlack' => $data['ScoreBlack']=='' ? NULL : $data['ScoreBlack'],
            'Time' => $data['Time'],
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
                        ->from(array('g' => $this->getTableName()))
                        ->joinleft(array('tw' => 'teams'), 'tw.ID=g.ID_TeamWhite', array("TeamWhite" => "tw.Description"))
                        ->joinleft(array('tb' => 'teams'), 'tb.ID=g.ID_TeamBlack', array("TeamBlack" => "tb.Description"))
                        ->joinleft(array('pr1' => 'players'), 'pr1.ID=g.ID_PlayerReferee1', array("Referee1" => "pr1.Name"))
                        ->joinleft(array('pr2' => 'players'), 'pr2.ID=g.ID_PlayerReferee2', array("Referee2" => "pr2.Name"))
                        ->joinleft(array('prh' => 'players'), 'prh.ID=g.ID_PlayerRefereeHead', array("RefereeHead" => "prh.Name"))
                        ->joinleft(array('tr1' => 'teams'), 'tr1.ID=g.ID_TeamReferee1', array("TeamReferee1" => "tr1.Description"))
                        ->joinleft(array('tr2' => 'teams'), 'tr2.ID=g.ID_TeamReferee2', array("TeamReferee2" => "tr2.Description"))
                        ->joinleft(array('trh' => 'teams'), 'trh.ID=g.ID_TeamRefereeHead', array("TeamRefereeHead" => "trh.Description"))
                        ->joinleft(array('f' => 'fields'), 'f.ID=g.ID_Field', array("Field" => "f.Description"))
                        ->where('g.ID_Status=1')
                        ->order(array('Field','Time'))
        ;

        $source = new Bvb_Grid_Source_Zend_Select($select);
        $this->dataGrid->setGridId('games');
        $this->dataGrid->setSource($source);
    	//2. specify columns
        $this->dataGrid->updateColumn('ID', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Status', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Tournement', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_TeamWhite', array('hidden' => true));        
        $this->dataGrid->updateColumn('ID_TeamBlack', array('hidden' => true)); 
        $this->dataGrid->updateColumn('ID_PlayerReferee1', array('hidden' => true));        
        $this->dataGrid->updateColumn('ID_PlayerReferee2', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_PlayerRefereeHead', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_Field', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_TeamReferee1', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_TeamReferee2', array('hidden' => true));
        $this->dataGrid->updateColumn('ID_TeamRefereeHead', array('hidden' => true));

        $this->dataGrid->updateColumn('Field',array(
                            'title'		=> 'Field',
                            'position'  => 10,
        ));    
        $this->dataGrid->updateColumn('Time',array(
                            'title'		=> 'Time',
                            'decorator' => '<a href="/tournement/detail/id/{{ID_Tournement}}/tab/game/page/detail/childId/{{ID}}">{{Time}}</a>',
                            'position'  => 20,
        ));    
        $this->dataGrid->updateColumn('TeamWhite',array(
                            'title'		=> 'White',
                            'position'  => 30,
        ));    
        $this->dataGrid->updateColumn('ScoreWhite',array(
                            'title'		=> 'Score White',
                            'position'  => 40,
                            'helper'    => array(
                                        'name'=> 'ScoreHelper',
                                        'params'=>array(
                                                    'getScore',array('{{ID}}','{{ScoreWhite}}', 'White')
                                            ))               
        ));    
        $this->dataGrid->updateColumn('ScoreBlack',array(
                            'title'		=> 'Score Black',
                            'position'  => 50,
                            'helper'    => array(
                                        'name'=> 'ScoreHelper',
                                        'params'=>array(
                                                    'getScore',array('{{ID}}','{{ScoreBlack}}','Black')
                                            ))                 
        ));    
        $this->dataGrid->updateColumn('TeamBlack',array(
                            'title'		=> 'Black',
                            'position'  => 60,
        ));  
        $this->dataGrid->updateColumn('TeamReferee1',array(
                            'title'		=> 'Team Ref 1',
                            'position'  => 70,
        ));           
        //Referee 1
        $this->dataGrid->updateColumn('Referee1', array(
                'position'  => 75,
                'helper'    => array(
                            'name'=> 'PlayerHelper',
                            'params'=>array(
				    	'getSelectPlayer',array('{{ID}}','{{ID_PlayerReferee1}}','{{ID_TeamReferee1}}', 'Referee1')
				))        		        
        ));        
        $this->dataGrid->updateColumn('TeamReferee2',array(
                            'title'		=> 'Team Ref 2',
                            'position'  => 80,
        ));  
        //Referee 2
        $this->dataGrid->updateColumn('Referee2', array(
                'position'  => 85,
                'helper'    => array(
                            'name'=> 'PlayerHelper',
                            'params'=>array(
				    	'getSelectPlayer',array('{{ID}}','{{ID_PlayerReferee2}}','{{ID_TeamReferee2}}', 'Referee2')
				))        		        
        ));    
        $this->dataGrid->updateColumn('TeamRefereeHead',array(
                            'title'		=> 'Team Ref Head',
                            'position'  => 90,
        ));  
        //Referee Head
        $this->dataGrid->updateColumn('RefereeHead', array(
                'position'  => 95,
                'helper'    => array(
                            'name'=> 'PlayerHelper',
                            'params'=>array(
				    	'getSelectPlayer',array('{{ID}}','{{ID_PlayerRefereeHead}}','{{ID_TeamRefereeHead}}', 'RefereeHead')
				))        		        
        ));            

        //3. build form	
        //4. deploy 	
        return $this->dataGrid->deploy();
    }    

    public function saveGame($referee1, $referee2, $refereeHead, $scoreBlack, $scoreWhite){
    	if (empty($referee1)){
            return FALSE;
    	}
    	
    	$totalUpdated = 0;
    	foreach($referee1 as $k=>$v){
            $value = $v == 0 || $v == '' ? null : (int)$v;
            $dbFields = array(
                'ID_PlayerReferee1' => $value,
            );
            $totalAffected = $this->updateById($dbFields,(int)$k);
            $totalUpdated += (int)$totalAffected;
    	}
    	foreach($referee2 as $k=>$v){
            $value = $v == 0 || $v == '' ? null : (int)$v;
            $dbFields = array(
                'ID_PlayerReferee2' => $value,
            );
            $totalAffected = $this->updateById($dbFields,(int)$k);
            $totalUpdated += (int)$totalAffected;
    	}
    	foreach($refereeHead as $k=>$v){
            $value = $v == 0 || $v == '' ? null : (int)$v;
            $dbFields = array(
                'ID_PlayerRefereeHead' => $value,
            );
            $totalAffected = $this->updateById($dbFields,(int)$k);
            $totalUpdated += (int)$totalAffected;
    	}     
   	foreach($scoreWhite as $k=>$v){
            $value = $v == 0 || $v == '' ? null : (int)$v;
            $dbFields = array(
                'ScoreWhite' => $value,
            );
            $totalAffected = $this->updateById($dbFields,(int)$k);
            $totalUpdated += (int)$totalAffected;
    	}   
    	foreach($scoreBlack as $k=>$v){
            $value = $v == 0 || $v == '' ? null : (int)$v;
            $dbFields = array(
                'ScoreBlack' => $value,
            );
            $totalAffected = $this->updateById($dbFields,(int)$k);
            $totalUpdated += (int)$totalAffected;
    	}           
    	return $totalUpdated;
    }        
}