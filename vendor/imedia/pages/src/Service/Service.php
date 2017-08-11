<?php namespace Imedia\Pages\Service;

use Minty\Service\ServiceInterface;
use Minty\Doctrine\DoctrineManager;
use Minty\Service\ServiceLocator;
use Exception;

class Service implements ServiceInterface {
    
    
    /**
     *
     * @var Minty\Service\ServiceLocator
     */
    public $ServiceLocator;
    
    /**
     *
     * @var type 
     */
    private $Http;
    
    /**
     * 
     * @param type $options
     * @return \Imedia\Shop\Service\Service
     */
    public function create($options = array()) {


        return $this;
    }

    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator( ServiceLocator $ServiceLocator) { $this->ServiceLocator = $ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getHttp() { return $this->Http; }

    /**
     * 
     * @param \Minty\Route\Http $Http
     */
    public function setHttp(\Minty\Route\Http $Http) { $this->Http = $Http; }
    
    

}