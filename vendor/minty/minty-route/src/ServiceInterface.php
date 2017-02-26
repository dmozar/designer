<?php namespace Minty\Service;


interface ServiceInterface {
    
    
    public function create($options = []);
    
    public function setServiceLocator(\Minty\Service\ServiceLocator $ServiceLocator );
    
    public function getServiceLocator();
    
    public function setHttp( \Minty\Route\Http $Http );
    
    public function getHttp();
    
}