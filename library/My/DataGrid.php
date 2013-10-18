<?php
class My_DataGrid {
	
	protected $grid;
	
	public function __construct($id = '') {
		//die('dfsdfs');
        //$view = new Zend_View();
        //$view->setEncoding('UTF-8');
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/datagrid.ini', APPLICATION_ENV);
        $grid = Bvb_Grid::factory('Table', $config, $id);
        $grid->setEscapeOutput(false);
        //$grid->setTemplate($template)
        //$grid->setExport(array('csv','excel','pdf'));
        $grid->setExport(array());
        $grid->setCharEncoding('utf8');
        $grid->setRecordsPerPage(30);    

        $grid->setUseKeyEventsOnFilters(true); //filters are applied when user changes filter content (onchange event)

        //$grid->setView($view);
        #$grid->saveParamsInSession(true);
        #$grid->setCache(array('enable' => array('form'=>false,'db'=>false), 'instance' => Zend_Registry::get('cache'), 'tag' => 'grid'));
        $this->grid = $grid;
        //return $grid;	
	}
	
	public function getGrid(){
		return $this->grid;
	}

}
