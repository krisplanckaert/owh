<?php
error_reporting(E_ALL);
ini_set("display_errors",1);

if($_SERVER["SERVER_NAME"] == 'localhost' || $_SERVER["SERVER_NAME"] == 'winsol-dev')
{ //local
	define("APPLICATION_ENV",'development'); //
	define("PROJECT_PATH",'D:/AppServ/www/klanten/winsol/vhosts/winsol-dev/');
	define("SERVER_INCLUDE_PATH",'D:/AppServ/www/combell/includes/');
}else
{ //online 
	define("APPLICATION_ENV",'production');
	define("PROJECT_PATH",'/var/www/vhosts/winsol-dev.be/');
	define("SERVER_INCLUDE_PATH",'/var/www/includes/');
}

// --

defined('APPLICATION_PATH')
    || define(
    'APPLICATION_PATH', PROJECT_PATH . 'application/');
/*
defined('APPLICATION_PATH')
    || define(
    'APPLICATION_PATH', realpath(dirname(__FILE__)).'/../application/');
    */
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
    
    define('ZF_LIB_PATH', SERVER_INCLUDE_PATH . 'zf/library/');
    define('ZF_EXTRA_LIB_PATH', SERVER_INCLUDE_PATH . 'zf/extras/library/'); //zendX
    define('ZF_APP_LIB_PATH', PROJECT_PATH . 'library/');

set_include_path(implode(PATH_SEPARATOR, array(
    ZF_LIB_PATH,
    ZF_EXTRA_LIB_PATH,
    ZF_APP_LIB_PATH,
    get_include_path()
)));    

try
{ 
	//autoload
		require_once 'Zend/Loader/Autoloader.php'; 
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->setDefaultAutoloader(create_function('$class',
	 	"include str_replace('_', '/', \$class) . '.php';"
	 	));
	 //application settings		
		$application = new Zend_Application(
					APPLICATION_ENV,
					APPLICATION_PATH.'configs/application.ini'
					);
		$application->bootstrap()
					->run();
} catch (Exception $exception) {
    echo '<html><body><center>'
       . 'An exception occured while bootstrapping the application.';
    if (defined('APPLICATION_ENV')
        && APPLICATION_ENV != 'production'
    ) {
        echo '<br /><br />' . $exception->getMessage() . '<br />'
           . '<div align="left">Stack Trace:' 
           . '<pre>' . $exception->getTraceAsString() . '</pre></div>';
    }
    echo '</center></body></html>';
    exit(1);
}