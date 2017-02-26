<?php namespace Administrator\Login\Service;

use Minty\Service\ServiceInterface;
use Minty\Validation;
use Minty\Session\SessionManager;
use Imedia\User\Entity\User;
use \Minty\Type\NullType;

class LoginService implements ServiceInterface {
    
    
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
    private $module_configs;

    /**
     *
     * @var type 
     */
    private $message;

    /**
     *
     * @var type 
     */
    private $is_submit;
    
    /**
     *
     * @var type 
     */
    private $status = false;
    
    
    /**
     * 
     * @param type $options
     * @return \Imedia\RealEstate\Home\Service\ControllerService
     */
    public function create($options = array()) {
        
        $this->is_submit = $options['submit'];
        
        $RouteService   = $this->ServiceLocator->Route;
        $Route          = $RouteService->Route();

        $this->setModuleConfigs( $options['configs'] );

        return $this;
    }

    
    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator) { $this->ServiceLocator = $ServiceLocator; }

    /**
     * 
     * @return type
     */
    public function getServiceLocator() { return $this->ServiceLocator; }

    /**
     * 
     * @param type $configs
     */
    public function setModuleConfigs( $configs ){ $this->module_configs = $configs; }

    /**
     * 
     * @return type
     */
    public function getModuleConfigs(){ return $this->module_configs; }

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
    
    /**
     * 
     * @return type
     */
    public function getMessage(){ return $this->message; }
    
    /**
     * 
     * @return type
     */
    public function getStatus(){ return $this->status; }
    
    

}