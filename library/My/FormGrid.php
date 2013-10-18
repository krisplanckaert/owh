<?php
class My_FormGrid extends Bvb_Grid_Form
{
  
     /**
     * Constructor
     *
     * @param string $formClass
     * @param mixed $options
     */
    public function __construct($formClass = 'Zend_Form',$options = NULL)
    {
    	
        parent::__construct($formClass,$options);
        $this->setBulkAdd(1) //Replace param with the number of forms you want to display
				->setBulkDelete(true)
	//			->setBulkEdit(true) //when bulk edit is true, column edit isn't displayed
	//			->setAdd(true)
	//->setEdit(true)
				->setDelete(true)
	//			->setAddButton(true)
		//		->setCsv(false)
	//			->setSaveAndAddButton(true);
				->setDeleteConfirmationPage(true)			
			;
    }
}
