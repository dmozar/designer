<?php namespace Imedia\Designer;

use Application\Module as ApplicationModule;

class Module extends ApplicationModule {
    
    public function __construct() {
        parent::__construct();
        
    }
    
    
    public function getModuleConfigs(){
        
        return include __DIR__ . '/../config/module.config.php';
        
    }
    
}