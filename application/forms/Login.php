<?php

class Application_Form_Login extends My_Form
{

    public function init()
    {
        $this->addElement('text', 'Username', array(
            'label'      => 'Username',
            'required'   => true,
        	'errorMessages' => array('Username is verplicht'),
            'filters'    => array('StringTrim','StripTags','StringToLower'),
            'validators' => array(
                                array('NotEmpty',true),
                            )
        ));

        $this->addElement('text', 'Password', array(
            'label'      => 'Paswoord',
            'required'   => true,
            'errorMessages' => array('Paswoord is verplicht'),
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                                array('NotEmpty',true),
                                array('StringLength',true,array(6,20))
                            ) 
            ));  
    }


}

