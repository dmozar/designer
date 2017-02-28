<?php namespace Application;

use Minty\MVC;

class Module extends MVC {
    
    
    
    
    
    public function __construct() {
        
        \Minty\Event\EventManager::get()->CreateEventListener('Application\Services\BackgroundService::Set', 'Background');
    }
    
    
    
}
