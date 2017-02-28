<?php namespace Minty\MySql\ORM;

abstract class RepositoryManager {
    
    
    /**
     *
     * @var type 
     */
    public $ServiceLocator;
    
    
    /**
     *
     * @var type 
     */
    public $options;
    
    
    
    private $Http;
    

    
    /**
     * 
     * @param type $options
     */
    public function create($options = array()) { 
        
        $this->options = $options;
        
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
