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
        $tournementModel = new Application_Model_Tournement();
        $registrationModel = new Application_Model_Registration();        
        $upcomingTournements = $tournementModel->getAll($where);
        foreach($upcomingTournements as $k => $upcomingTournement) {
            $fields = array(
                'ID_Tournement' => $upcomingTournement['ID'],
                'ID_Team' => $this->authUser['ID_Team'],
            );
            $registration = $registrationModel->getOneByFields($fields);
            if($registration) {
                $upcomingTournements[$k]['Registration']=true;
            } else {
                $upcomingTournements[$k]['Registration']=false;              
            }
        }
        $this->view->upcomingTournements = $upcomingTournements;

        $where = 'Date <= ' . $date;
        $this->view->pastTournements = $tournementModel->getAll($where);
    }    
}

