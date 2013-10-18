<?php

/**
 * Product helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_PlayerHelper extends Zend_View_Helper_Abstract {

    public $view;

    /**
     * setView
     * @see Zend_View_Helper_Abstract::setView()
     *
     * If a helper class has a setView() method, it will be called when the helper class
     * is first instantiated, and passed to the current view object.
     */
    public function setView(Zend_View_Interface $view) {
        $this->view = $view;
    }

    /**
     * Workaround to call multiple methods
     * @param string $method
     * @param array $args
     */
    public function PlayerHelper($method, $args=NULL) {
        $thisClass = get_class();
        $classMethods = get_class_methods($thisClass);
        // case the method exists into this class  //
        if (in_array($method, $classMethods)) {
            $arrCaller = array($thisClass, $method);
            return call_user_func_array($arrCaller, $args);
        }
        //method not found in this class
        throw new Exception("Method " . $method . " doesn't exist in class " . $thisClass . ".");
    }


    public function getSelectPlayer($gameId,$playerId,$teamId, $field) {
        $playerModel = new Application_Model_Player();
        $options = array(
            'where' => 'ID_Team = ' . $teamId,
            'value' => 'Name',
            'order' => 'Name',
        );        
        $playerList = $playerModel->buildSelect($options);        
        $html = '<select name="'.$field.'['.(int)$gameId.']" style="font-size:80%;">';
        foreach($playerList as $k=> $v){
                $sel = $k == $playerId ? ' selected="selected"' : '';
                $html .= '<option value="' . (int)$k . '"' . $sel . '>'.$this->view->escape($v) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

}

