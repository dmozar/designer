<?php namespace Imedia\Shop\Service;

use Minty\Service\ServiceInterface;
use Minty\Doctrine\DoctrineManager;
use Minty\Service\ServiceLocator;
use Doctrine\ORM\EntityManager;

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
     * @var type 
     */
    private $EntityManager;

    /**
     * 
     * @param type $options
     * @return \Imedia\Shop\Service\Service
     */
    public function create($options = array()) {
        
        $this->setEntityManager( DoctrineManager::get()->EntityManager() );

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
    public function getEntityManager(){ return $this->EntityManager;  }
    
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager( EntityManager $em ){ $this->EntityManager = $em; }

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