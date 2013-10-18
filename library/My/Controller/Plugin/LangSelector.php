<?php
/**
 * 
 * Select language
 *
 */
class My_Controller_Plugin_LangSelector extends Zend_Controller_Plugin_Abstract
{   
	
    protected $auth;
    protected $languages = array('nl' => 1, 'fr' => 2);
    private $_excludeActions = array(
        'product'  => array('ajax-calculate-price'),
    );


    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
//      Determine which error controller to use for the different modules
//      $front = Zend_Controller_Front::getInstance();
//      if (!($front->getPlugin('Zend_Controller_Plugin_ErrorHandler')
//          instanceof Zend_Controller_Plugin_ErrorHandler)) {
//          return;
//      }

        if(!Zend_Auth::getInstance()->hasIdentity()) {
            return;
        }

         $this->auth = Zend_Auth::getInstance()->getIdentity();
        //Zend_Debug::dump($this->auth);exit;
        //Zend_Registry::set('selectedLang', $langValue);

        $lang = $request->getParam('lang');
        $langId_default = 1;
        $lang_default = 'nl';
        $locale_default = 'nl_BE';
        //$my_Translate = new Zend_Session_Namespace('my_Translate');
        $session = new Zend_Session_Namespace('translation');
        //$session->translate = ;
        if (!empty($lang)){
            $lang = strtolower($lang);

            if  (!array_key_exists($lang,$this->languages)){
                //$langId      = $langId_default;
                $langValue   = $lang_default;
                $localeValue = $locale_default;
            } else {
                $langValue = $lang;
                $localeValue = $lang.'_BE';
            }
        } else if (isset($session->translate) && !empty($session->translate)) {
            $langValue   = $session->translate['lang']; //Zend_Registry::get('selectedLang');
            $localeValue = $session->translate['locale']; //Zend_Registry::get('selectedLocale');
            //die('lang = ' . $langValue . ' | locale = ' . $localeValue); exit;
        } else {
            //retrieve lang selection from user
            //@todo, locale can also be retrieved from database, low low low priority
            $langValue   = $lang_default;
            $localeValue = $locale_default;
            if ($this->auth->ID_Taal==2){
                //French
                $localeValue = 'fr_BE';
                $langValue   = 'fr';
            }
        }
        $session->translate['langId'] = $this->languages[$langValue];
        $session->translate['lang']   = $langValue;
        $session->translate['locale'] = $localeValue;


        //echo 'lang = ' .$langValue . ' ==> ' .  var_dump(Zend_Registry::isRegistered('selectedLang'));
        //echo 'locale = ' .$localeValue . ' ==> ' . var_dump(Zend_Registry::isRegistered('selectedLocale'));
        //echo 'locVal = ' . $localeValue;
        //Zend_Registry::set('selectedLang', $langValue);
          
        $locale = new Zend_Locale($localeValue);
        Zend_Registry::set('Zend_Locale', $locale);



        //$language   = 'en'; // @todo: language is based on the user language (not yet implemented)
        //die(APPLICATION_PATH . 'configs/lang/' . $langValue . '.csv');
        //1. custom translations in csv
        $enablecache = false;
        if($enablecache) {
            $translator = new Zend_Translate(array(
                'adapter' => 'csv',
                'content' => APPLICATION_PATH . 'configs/lang/' . $langValue . '.csv',
                'locale' => $langValue,
                'cache' => Zend_Controller_Front::getInstance()->getParam('bootstrap')
                                                               ->getResource('cachemanager')
                                                               ->getCache('translate')
            ));
        } else {
            $translator = new Zend_Translate(
                'csv',
                APPLICATION_PATH . 'configs/lang/' . $langValue . '.csv',
                $langValue
            );
        }
        $controllerName = $request->getControllerName();
        $actionName     = $request->getActionName();
        if (array_key_exists($controllerName,$this->_excludeActions)
            && in_array($actionName,$this->_excludeActions[$controllerName])) {
            $translator_db = null;
        } else {
            //2. custom translation in database
            $translator_db = new Zend_Translate(
                'array',
                APPLICATION_PATH . 'configs/lang/' . $langValue . '.php',
                $langValue
            );
        }

        //3. zend framework translations
        $translator_zf = new Zend_Translate(
            'array',
            PROJECT_LIB_PATH . 'resources/languages',
            $langValue,
            array('scan' => Zend_Translate::LOCALE_DIRECTORY)
        );

        //options
        // Create a log instance and log all untranslated strings

        $writer = new Zend_Log_Writer_Stream(APPLICATION_LOG_PATH.'untranslated.log');
        $log    = new Zend_Log($writer);
        $translatorOptions = array(
            'log'             => $log,
            'logUntranslated' => true
        );
        if (APPLICATION_ENV=='production'){
                $translatorOptions['disableNotices'] = TRUE;
        }
        $translator->setOptions($translatorOptions);

        $translator->setOptions(array('logUntranslated' => false));

        //add translations
        $translator->addTranslation(array('content' => $translator_zf));
        unset($translator_zf); //var is not used anymore => free memory
        if($translator_db) {
            $translator->addTranslation(array('content' => $translator_db));
            unset($translator_db); //var is not used anymore => free memory
        }
  	//Zend_Validate_Abstract::setDefaultTranslator($translator);
        //Zend_Form::setDefaultTranslator($translator);
        // Save it for later

        Zend_Registry::set('Zend_Translate', $translator);


    }
    
}
