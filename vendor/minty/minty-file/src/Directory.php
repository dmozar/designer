<?php namespace Minty\File;

use Exception;

class Directory {
    
    public function __construct() {
        
    }
    
    public static function get(){
        
        return new Directory;
        
    }
    
    
    public function make($pathname, $permision = 0775, $reqursive = true){
        
        if( !@mkdir($pathname, $permision, $reqursive))
                throw new Exception ('Directory classs cannot create directory ' . $pathname);
        
    }
    
}