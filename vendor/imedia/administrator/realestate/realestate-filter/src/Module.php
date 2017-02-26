<?php namespace Administrator\RealEstate\Filter;

use Application\Module as ApplicationModule;
use Minty\MVC;
use Minty\Router;
use Minty\Session\SessionManager;
use Minty\Helper\HelperManager;

class Module extends ApplicationModule {
    
    public function __construct() {
        parent::__construct();
        
    }
    
    
    public function getModuleConfigs(){
        
        return include __DIR__ . '/../config/module.config.php';
        
    }
    
    
    public function __initialize(){
        
        global $e;
        
        if( ! MVC::isLoged())
            header('Location:' . Router::FromRoute('Administrator\Login', 'login') );
        
        HelperManager::get()->load('table'); 
        
    }
    
}