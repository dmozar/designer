<?php namespace Minty\Assets;

use Minty\Assets\Exception\MakeDirException;

class File {
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @return \Dady\Assets\File
     */
    public static function get(){
        return new \Minty\Assets\File;        
    }
    
    
    
    /**
     * 
     * @param type $path
     * @param type $place
     * @param type $permision
     * @throws MakeDirException
     */
    public function MakeDir( $path, $place = null, $permision = '0755' ){
        
        $place = $place ? $place : PATH_PUBLIC . 'assets/';
        
        if( ! @mkdir($place.$path, $permision, true) )
            throw new MakeDirException('Cannot create folder in ' . $place.$path);
        
    }
    
    
    
    /**
     * 
     * @param type $src
     * @param type $dst
     */
    public function recurse_copy($src,$dst) { 
        if(file_exists($src)){
            $dir = opendir($src); 
            @mkdir($dst); 
            while(false !== ( $file = readdir($dir)) ) { 
                if (( $file != '.' ) && ( $file != '..' )) { 
                    if ( is_dir($src . '/' . $file) ) { 
                        $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                    } 
                    else { 
                        copy($src . '/' . $file,$dst . '/' . $file); 
                    } 
                } 
            } 
            closedir($dir); 
        }
    } 
    
    
}
