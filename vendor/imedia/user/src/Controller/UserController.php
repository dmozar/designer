<?php namespace Imedia\User\Controller;

use Imedia\User\Module;
use Minty\View\ViewModel;

use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;

use Minty\Session\SessionManager;
use Minty\Router;

class UserController extends Module {
    
    
    
    public function __construct() {
        parent::__construct();
        
        $this->register(__FILE__);
        
    }
    
    
    
    public function login(){
        
        $ServiceLocator = $this->getServiceLocator();
        
        $options = $this->getModuleConfigs();
        
        $ViewModel =  $ServiceLocator->get('LoginHelper', $options);
        
        return $ViewModel;
        
    }
    
    
    
    public function logout(){
        
        global $session_key;
        
        if( ! $session_key )
            $session_key == 'ci_credential';
        
        SessionManager::get()->delete( $session_key );
        
        redirect(site_url());
        
    }
    
    
    
    public function registration(){
        
        $ServiceLocator = $this->getServiceLocator();
        
        $options = $this->getModuleConfigs();
        
        $ViewModel =  $ServiceLocator->get('RegistrationHelper', $options);
        
        return $ViewModel;
        
    }
    
    
}