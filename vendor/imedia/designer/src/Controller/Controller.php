<?php namespace Imedia\Designer\Controller;

use Imedia\Designer\Module;
use Minty\View\ViewModel;
use Minty\MVC;
use Minty\Router;
use Minty\MySql\ORM\Query;
use Minty\MySql\ORM\QueryBuilder;
use Minty\Session\SessionManager;
use Minty\Output\OutputManager;
use Minty\Doctrine\DoctrineManager;

class Controller extends Module {
    
    
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
        
        if(! MVC::getUserData() ){
            
            SessionManager::get()->Store('redirect', Router::FromRoute('Imedia\Designer', 'index'));
            
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
    
    /**
     * 
     * @return type
     */
    public function history(){
        
        if(! MVC::getUserData() ){
            
            SessionManager::get()->Store('redirect', Router::FromRoute('Imedia\Designer', 'history'));
            
            redirect( Router::FromRoute('Imedia\User', 'login') );
        }
        
        $ViewModel = $this->getServiceLocator()->get('HistoryDesignHelper');
        
        return $ViewModel;
        
    }
    
    /**
     * 
     * @return type
     */
    public function remove(){
        
        $Service = $this->getServiceLocator()->get('DesignerService');
        
        $options = [
            'view' => 'remove_design_item',
            'item' => $Service->getDesign()
        ];
        
        
        $RouteService   = $this->getServiceLocator()->Route;
        $Route          = $RouteService->Route();
        $params         = $Route->params_from_route();
        
        if( $options['item'] )
        if($params['confirmed'] == true){
            $em = $Service->getEntityManager();
            $em->remove($options['item']);
            $em->flush();
            
            DoctrineManager::RemoveCache('designer_single');
            DoctrineManager::RemoveCache('designer_list');
            
            redirect(Router::FromRoute('Imedia\Designer', 'history'));
        }
        
        if( ! $options['item'] ) redirect(Router::FromRoute('Imedia\Designer', 'history'));
        
        $ViewModel = $this->getServiceLocator()->get('ViewHelper', $options);
        
        return $ViewModel;
    }
    
}