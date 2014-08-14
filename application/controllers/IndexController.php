<?php

class IndexController extends My_Controller_Action
{

    public function indexAction()
    {
    	 $this->_helper->redirector('login', 'teamuser'); //,$this->getRequest()->getModuleName());
    }
    
    public function homeAction() 
    {
        date_default_timezone_set('UTC');
        $date = date('Ymd');
        $where = 'Date > ' . $date;
        $championshipdayModel = new Application_Model_Championshipday();
        $registrationModel = new Application_Model_Registration();        
        $upcomingChampionshipdays = $championshipdayModel->getAll($where);
        foreach($upcomingChampionshipdays as $k => $upcomingChampionshipday) {
            $fields = array(
                'ID_ChampionshipDay' => $upcomingChampionshipday['ID'],
                'ID_Team' => $this->authUser['ID_Team'],
            );
            $registration = $registrationModel->getOneByFields($fields);
            if($registration) {
                $upcomingChampionshipdays[$k]['Registration']=true;
            } else {
                $upcomingChampionshipdays[$k]['Registration']=false;              
            }
        }
        $this->view->upcomingChampionshipdays = $upcomingChampionshipdays;

        $where = 'Date <= ' . $date;
        $this->view->pastChampionshipdays = $championshipdayModel->getAll($where);
    }    
}

