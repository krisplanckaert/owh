<?php
class My_DecoratorRegistry
{

	public static function getDisplayGroupDecoratorDefault()
	{
		return array(
				'FormElements',
			//	'ViewHelper',
			//	'Errors',
			//	'Description',			
				'Fieldset',
				array('HtmlTag',array('tag'=>'div','placement' => 'append','openOnly'=>true,'style'=>'float:left;width:90%;margin-top:15px;'
				))
				);	
		
		
	}
	
    /**
     * Custom group decorator for creating a large sized form
     */
    public static function getDisplayGroupDecoratorLarge()
    {
        return array(
            'FormElements',
            array('HtmlTag', array(
                                'tag' => 'div',
                                'class' => 'form large',
            					'placement' => 'append',
                            )
            ),
            'Fieldset'
        );
    }

    /**
     * Custom group decorator for creating a medium sized form
     */
    public static function getDisplayGroupDecoratorMedium()
    {
        return array(
            'FormElements',
            array('HtmlTag', array(
                                'tag' => 'dl',
                                'class' => 'form medium'
                            )
            ),
            'Fieldset'
        );
    }

    /**
     * Custom group decorator for creating a small sized form
     */
    public static function getDisplayGroupDecoratorSmall()
    {
        return array(
            'FormElements',
            array('HtmlTag', array(
                                'tag' => 'dl',
                                'class' => 'form small'
                            )
            ),
            'Fieldset'
        );
    }

    /**
     * Custom group decorator for buttons
     */
    public static function getButtonsDisplayGroupDecorator()
    {
        return array(
            'FormElements',
            array('HtmlTag', array(
                                'tag' => 'div',
                                'id' => 'buttons'
                            )
            )
        );
    }

    /**
     * Custom button decorator
     */
    public static function getButtonDecorator()
    {
        return array('ViewHelper');
    }
}
