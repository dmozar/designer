<?php namespace Administrator\Login;

use Application\Module as ApplicationModule;
use Minty\MVC;
use Minty\Router;
use Minty\Session\SessionManager;

class Module extends ApplicationModule {
    
    
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
        
    }
    
    
    /**
     * 
     * @return type
     */
    public function getModuleConfigs(){  return include __DIR__ . '/../config/module.config.php'; }
    
    
    /**
     * 
     * @global type $e
     */
    public function __initialize(){
        
        global $e;
        
        if(! MVC::isLoged() && $e->controller !== 'Administrator\Login\Controller\LoginController')
            header('Location:' . Router::FromRoute('Administrator\Login', 'login') );
        
        if( MVC::isLoged() )
            header('Location:' . Router::FromRoute('Administrator\Home', 'home') );
        
    }
    
}