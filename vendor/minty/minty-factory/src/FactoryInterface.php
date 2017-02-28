<?php namespace Minty\Factory;

use Minty\Service\ServiceInterface;

interface FactoryInterface {
    
    
    
    public function create( $request, $options = [], ServiceInterface $service = null);
    
    
    
}