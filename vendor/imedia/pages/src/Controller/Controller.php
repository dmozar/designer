<?php namespace Imedia\Pages\Controller;

use Imedia\Pages\Module;
use Minty\View\ViewModel;
use Minty\MVC;
use Minty\Router;
use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;
use Minty\Session\SessionManager;


class Controller extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        $this->register(__FILE__);
        
    }
    
    
    public function index(){
        $ServiceLocator = $this->getServiceLocator();
        
        $ViewModel =  $ServiceLocator->get('PagesHelper');
        
        return $ViewModel;
        
    }
    
}