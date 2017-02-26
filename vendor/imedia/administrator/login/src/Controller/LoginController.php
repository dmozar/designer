<?php namespace Administrator\Login\Controller;

use Administrator\Login\Module;
use Minty\View\ViewModel;

use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;


class LoginController extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
        
    }
    
    
    public function index(){
        
        $ServiceLocator = $this->getServiceLocator();
        
        $configs = $this->getModuleConfigs();
        
        $ViewModel =  $ServiceLocator->get('LoginHelper', $configs );
        
        return $ViewModel;
        
    }
    
}