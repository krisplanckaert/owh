<?php
class RegistrationController extends My_Controller_Action 
{   

    public function saveAction() {
        $formData = $this->_getAllParams();
        if(isset($formData['btn_softDelete_x'])) {
           $this->model->softDeleteById($formData['childId']);
        } else {
            $registrationplayerModel = new Application_Model_Registrationplayer();
            if($formData['id']) {
                $registrationId = $formData['childId'];
            } else {
                $registrationModel = new Application_Model_Registration();   
                $data = array(
                    'ID_ChampionshipDay' => $formData['ID_ChampionshipDay'],
                    'ID_Team' => $this->authUser['ID_Team'],
                );
                $registrationId = $registrationModel->insert($data);
            }
            $registrationplayerModel->saveRegistrationPlayer($registrationId, $formData['ID_Player']);
        }
        $this->_helper->redirector('home', 'index');
      }
}