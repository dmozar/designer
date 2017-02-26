<?php namespace Imedia\Proxy\Controller;

use Imedia\Proxy\Module;
use Minty\View\ViewModel;

use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;
use Minty\Output\OutputManager;

class ProxyController extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
        
    }

    
    
    public function index(){
        
        $options = [];
        
        $response = $this->getServiceLocator()->factory('ProxyFactory', 'AjaxManager', $options);

        //print_r($response);
        
        OutputManager::get()->json( $response );
        
    }
    
}