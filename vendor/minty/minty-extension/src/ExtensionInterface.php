<?php namespace Minty\Extension;


 interface ExtensionInterface {
     
    public static function get();
    
    public function initialize();
    
    public function setModuleConfigs( array $config );
     
}