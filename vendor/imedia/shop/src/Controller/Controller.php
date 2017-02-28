<?php namespace Imedia\Shop\Controller;

use Imedia\Shop\Module;
use Minty\View\ViewModel;

use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;


class Controller extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
        
    }
    
    
    public function index(){
        
        $ServiceLocator = $this->getServiceLocator();
        
        $ViewModel =  $ServiceLocator->get('HomeHelper');
        
        return $ViewModel;
        
    }
    
}