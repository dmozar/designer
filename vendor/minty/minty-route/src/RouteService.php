<?php namespace Minty\Route;

use Exception;
use Minty\Service\ServiceInterface;

class RouteService implements ServiceInterface {
    
    
    /**
     *
     * @var type 
     */
    private $options = [];
    
    
    
    
    /**
     *
     * @var type 
     */
    private $Route;
    
    
    
    
    /**
     *
     * @var type 
     */
    private $request;
    
    
    
    /**
     *
     * @var type 
     */
    private $ServiceLocator;
   


    /**
     *
     * @var type 
     */
    private $Http;



    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getHttp() {
        return $this->Http;
    }

    
    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) {
        $this->Http = $Http;
    }
    
    
    
    /**
     * 
     * @param type $request_name
     * @param type $options
     * @return type
     * @throws Exception
     */
    public function __invoke($request_name, $options = []) {
        
        if( !method_exists($this, $request_name))
            throw new Exception('Route service don`t have requested name ' . $request_name);
        
        $this->options = $options;
        
        return $this->{$request_name}();
    }
    
    
    
    
    /**
     * 
     * @param type $options
     * @return \Minty\Route\RouteService
     */
    public function create($options = array()) {
        
        return $this;
    }
    
    
    
    
    
    /**
     * 
     * @return \Minty\Route\RouteService
     */
    private function Routing(){
        
        $this->Route = new Route();
        
        $this->Route->setRoutesMap( $this->options['routes'] );
        
        $this->request = $this->Route->__initialize();
        
        return $this;
        
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    public function get(){
        return $this->Route;
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    public function Route(){
        
        $this->options = null;
        
        return $this->Route;
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    public function request(){
        return $this->request;
    }

    
    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) {
        $this->ServiceLocator = $ServiceLocator;
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    public function getServiceLocator() {
        return $this->ServiceLocator;
    }

}
