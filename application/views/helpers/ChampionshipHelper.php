<?php

/**
 * Chzmpionship helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Zend_View_Helper_ChampionshipHelper extends Zend_View_Helper_Abstract {

    public $view;

    /**
     * setView
     * @see Zend_View_Helper_Abstract::setView()
     *
     * If a helper class has a setView() method, it will be called when the helper class
     * is first instantiated, and passed to the current view object.
     */
    public function setView(Zend_View_Interface $view){
            $this->view = $view;
    }

    /**
    * Workaround to call multiple methods
    * @param string $method
    * @param array $args
    */
    public function ChampionshipHelper($method,$args=NULL)
    {
        $thisClass    = get_class();
        $classMethods = get_class_methods($thisClass);
        // case the method exists into this class  //
        if(in_array($method,$classMethods)) {
            $arrCaller = array($thisClass,$method);
            return call_user_func_array($arrCaller, $args );
        }
        //method not found in this class
     	throw new Exception("Method ".$method." doesn't exist in class " .$thisClass."." ); 	
    }
	
    public function viewInfo($data){
        if (empty($data)){
            return NULL;
        }
        $this->view->setEscape('htmlentities'); ?>

        <div class="viewInfo">
            <div class="title" >
		<strong><?php echo $this->view->translate($data['Description'],1);?></strong>
            </div>

            <table class="tbl_viewInfo">
                <tbody>
                    <tr>
                    </tr>
                </tbody>
            </table>
	</div><br clear="all" />
<?php }

}
