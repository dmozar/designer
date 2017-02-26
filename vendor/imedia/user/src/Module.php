<?php namespace Imedia\User;

use Application\Module as ApplicationModule;
use Minty\MVC;
use Minty\Router;
use Minty\Session\SessionManager;

class Module extends ApplicationModule {
    
    public function __construct() {
        parent::__construct();
        
    }
    
    
    public function getModuleConfigs(){
        
        return include __DIR__ . '/../config/module.config.php';
        
    }
    
    
    public function __initialize(){
        
        
        if( MVC::isLoged() )
            header('Location:' . Router::FromRoute('Imedia\RealEstate\Home', 'home') );
        
    }
    
    
}