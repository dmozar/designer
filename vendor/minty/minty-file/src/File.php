<?php namespace Minty\File;

use Minty\File\Directory;
use Exception;

class File {
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @return \Minty\File\File
     */
    public static function get(){
        
        return new File;
        
    }
    
    
    /**
     * 
     * @param type $file
     * @return type
     */
    public function filepath($file){
        
        $e = explode('/', $file);
        
        array_pop($e);
        
        return  implode('/',$e);
    }
    
    
    
    
    public function append( $string, $file ){
        
        $path = $this->filepath($file);
        
        if( !is_dir( $path )) 
            Directory::get()->make( $path );

        if (file_exists( $file )) 
            $fh = fopen($file , 'a');    
        else 
            $fh = fopen($file, 'w');
        
        if(! @fwrite($fh, $string."\n"))
                throw new Exception('Cannot write file ' . $file);
        
        fclose($fh);
        
        return true;
    }
    
    
    
    public function write( $string, $file ){
        
        $path = $this->filepath($file);
        
        if( !is_dir( $path )) 
            Directory::get()->make( $path );

        
        $fh = fopen($file, 'w');
        
        if(! @fwrite($fh, $string."\n"))
                throw new Exception('Cannot write file ' . $file);
        
        fclose($fh);
        
        return true;
    }
    
    
}