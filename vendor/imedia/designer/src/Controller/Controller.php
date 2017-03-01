<?php namespace Imedia\Designer\Controller;

use Imedia\Designer\Module;
use Minty\View\ViewModel;
use Minty\MVC;
use Minty\Router;
use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;
use Minty\Session\SessionManager;
use Minty\Output\OutputManager;

class Controller extends Module {
    
    
    /**
     * 
     */
    public function __construct() {
        parent::__construct();
        SessionManager::get()->Store('redirect', Router::FromRoute('Imedia\Designer', 'index'));
        $this->register(__FILE__);
        
    }
    
    /**
     * 
     * @return type
     */
    public function index(){
        
        if(! MVC::getUserData() ){
            redirect( Router::FromRoute('Imedia\User', 'login') );
        }
        
        SessionManager::get()->delete('redirect');
        
        $ServiceLocator = $this->getServiceLocator();
        
        $ViewModel =  $ServiceLocator->get('DesignerHelper');
        
        return $ViewModel;
        
    }
    
    /**
     * 
     */
    public function save(){
        
        $output = [
            'status' => false,
            'title'  => 'Error',
            'message' => language('UncategorizedError')
        ];
        
        $Http = $this->getServiceLocator()->Route->Route()->Http();
        
        if( ! $json = $Http->getFromPost('json') ) OutputManager::get()->json( $output );
        
        $Service = $this->getServiceLocator()->get('DesignerService');
        
        OutputManager::get()->json( $Service->Save($output, $json, $Http->getFromPost('title'), $Http->getFromPost('id') ) );
        
    }
    
    /**
     * 
     */
    public function load(){
        
        $output = [
            'status' => false,
            'title'  => null,
            'json'   => [],
            'id'     => null,
            'message' => language('UncategorizedError')
        ];
        
        $Service = $this->getServiceLocator()->get('DesignerService');
        
        OutputManager::get()->json( $Service->Load( $output ) );
    }
    
}