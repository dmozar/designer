<?php namespace Administrator\Home\Service;

use Minty\Service\ServiceInterface;

class HomeService implements ServiceInterface {
    
    
    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;
    
    
    /**
     *
     * @var int
     */
    private $offset = 0;
    
    
    /**
     *
     * @var int 
     */
    private $limit = 25;
    
    
    
    private $Http;
    
    
    
    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {
        
        $RouteService   = $this->ServiceLocator->Route;
        $Route          = $RouteService->Route();
        //$http         = $Route->Http();
        
        $params         = $Route->params_from_route();
        
        if(isset($params['page']))
            $this->offset = ($params['page'] * $this->limit) - $this->limit;
        
        
        return $this;
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
    
    
    
    
    
    /**
     * 
     * @return Minty\Mysql\ORM\Paginator
     */
    public function getAds(){
        
        $Repository = $this->ServiceLocator->get('AdRepository');
        
        return $Repository->getAds($this->offset, $this->limit);
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

}