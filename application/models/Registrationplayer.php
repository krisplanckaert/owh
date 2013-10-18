<?php
class Application_Model_Registrationplayer extends My_Model
{
    protected $_name = 'registrationplayers'; //table name
    protected $_id   = 'ID'; //primary key
    
    protected $onlyActiveRows = TRUE;

    public function saveRegistrationPlayer($registrationId,$players){
    	//1. update status
        $totalUpdate = parent::update(array('ID_Status' => 2), 'ID_Registration = '. (int)$registrationId);
        $totalInsert = 0;
        if (empty($players)){
                return $totalInsert;
        }
    	//2. we need to know if we need to update or insert	
        $options = array(
            'where'    => 'ID_Registration = ' . (int)$registrationId,
            'emptyRow' => false,
            'key'    => 'ID_Player',
            'value'    => 'ID',
        );
        $existingData = $this->buildSelect($options);
        foreach($players as $v){
            $data = array(
                    'ID_Registration' => (int)$registrationId,
                    'ID_Player'   => (int)$v,
            );
            if (array_key_exists((int)$v,$existingData)) {
                //update, selected row exists
                $data = array(
                        'ID_Status' => 1,
                );
                $totalUpdate = $this->updateById($data,(int)$existingData[(int)$v]);
                $totalInsert++;
            } else {
                //insert
                $id = $this->insert($data);
                if (!empty($id)){
                    $totalInsert++;
                }
            }
        } //end foreach
        return $totalInsert;
    }
    
}