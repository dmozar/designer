<?php


/**
 * 
 */
if( !function_exists('language')){
    
    function language($name){
        
        global $translate;
        
        if( ! is_array($translate))
            return 'no translate file loaded!';
        
        if(!array_key_exists($name, $translate))
            return $name;
        
        return $translate[$name];
    }
    
}


