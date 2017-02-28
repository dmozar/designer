<?php namespace Minty\View;

use Exception;

abstract class AbstractView {
     
    /**
     *
     * @var type 
     */
    private $ServiceLocator;
     
     
    /**
     *
     * @var type 
     */
    private $views;

    
    /**
     *
     * @var type 
     */
    private $Http;
     
    
    
    /**
     * 
     * @param type $options
     * @return \Minty\View\ViewModel
     */
    public function getView($options = []){

       $view = new \Minty\View\ViewModel( $options, $this->views );

       return $view;
    }


    /**
     * 
     * @param \Minty\Service\ServiceLocator $ServiceLocator
     */
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator){
        $this->ServiceLocator = $ServiceLocator;
    }


    /**
     * 
     * @return type
     * @throws Exception
     */
    public function getServiceLocator(){

        if( ! $this->ServiceLocator instanceof \Minty\Service\ServiceLocator )
            throw new Exception('Service Locator not found in helper view');

        return $this->ServiceLocator;
    }

    
    /**
     * 
     * @param array $views
     */
    public function setViews( array $views = []){
        $this->views = $views;
    }

    
    /**
     * 
     * @param \Minty\Route\Http $http
     */
    public function setHttp(\Minty\Route\Http $http ){
        $this->Http = $http;
    }

    
    /**
     * 
     * @return type
     */
    public function getHttp(){
        return $this->Http;
    }
 }