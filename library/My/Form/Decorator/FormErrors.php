<?php

class My_Form_Decorator_FormErrors extends Zend_Form_Decorator_Abstract
{
    public function __construct($options = null)
    {
        parent::__construct($options);
    }

     /**
      * Renders the form errors
      *
      * @param string $content content of the form
      * @return string $content content of the form with the error messages
      */
    public function render($content)
    {
        $form = $this->getElement();
        if (!$form instanceof Zend_Form) {
            return $content;
        }

        $message = $this->getOption('message');
        if (empty($message)) {
            $message = '';
        }

        $view = $form->getView();
        if (null === $view) {
            return $content;
        }

        $errors  = $form->getMessages();
        if (empty($errors)) {
            return $content;
        }
                
        $markup = '<div class="formErrors">';


        if (!empty($message)) {
            $markup .= '<p>' . $view->translate($message) . ': </p>';
        }
        $markup .= '<ul>';

        foreach ($errors as $name =>$list) {
            $element = $form->$name;

            if ($element instanceof Zend_Form_Element) {
                $label = $element->getLabel();
                if (empty($label)) {
                    $label = $element->getName();
                }
                $label = trim($label);
                
                $errorMessage = '';
                foreach ($list as $key => $error) {
                    $errorMessage = $view->escape($error);
                    break; // just display the first error message of a form field
                }
                
                $markup .= '<li><span class="label">' . $view->escape($label) . ': </span>'
                 . $view->escape($errorMessage) . '</li>';
             //           . htmlentities($errorMessage) . '</li>';
            } else {
                if (is_string($list)) {
                    $markup .= '<li>' . $view->escape($list) . '</li>';
                }
            }
        }

        $markup .= '</ul></div>';

        
        switch ($this->getPlacement()) {
            case self::PREPEND:
                	$content = $markup . $this->getSeparator() . $content;
                	break;   
            case self::APPEND:
            	 default:
                	$content = $content . $this->getSeparator() . $markup;
                	break;                                             
        }
        return $content;
    }
}
