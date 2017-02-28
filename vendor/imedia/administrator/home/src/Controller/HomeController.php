<?php namespace Administrator\Home\Controller;

use 
Administrator\Home\Module,
Minty\View\ViewModel,
Minty\MySql\ORM\Query,
Minty\MySql\ORM\QueryBuilder,
Minty\Event\EventManager;



class HomeController extends Module {

    
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function index(){
        
        EventManager::SEO('title','Dashboard Home');
        
        $ServiceLocator = $this->getServiceLocator();
        
        $ViewModel =  $ServiceLocator->get('HomeHelper');
        
        return $ViewModel;
    }
    
}