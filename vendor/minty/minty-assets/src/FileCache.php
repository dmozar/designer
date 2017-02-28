<?php namespace Minty\Assets;

use Minty\Assets\File;

class FileCache {
    
    
    
    /**
     *
     * @var type 
     */
    protected static $ctimeName = 'ctime';
    
    
    protected static $cfileName = 'filesystem';
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    
    /**
     * 
     * @param type $name
     * @return type
     */
    private function cachePath( $name = null ){
        return PATH_ROOT . 'data/cache/' . $name; 
    }
    
    
    
    
    
    /**
     * 
     * @return \Dady\AssetsFileCache
     */
    public static function get(){

        self::cache_time_dir();
        
        return new \Minty\Assets\FileCache;
    }

    
    
    
    
    /**
     * 
     */
    private static function cache_time_dir(){
        
        $Fc = new FileCache;
        
        if( ! is_dir( $Fc->cachePath() . self::$ctimeName )) {
            File::get()
                    ->MakeDir( self::$ctimeName, $Fc->cachePath() );
        }
    }
    
    
    
    /**
     * 
     * @param type $name
     * @param type $value
     * @param type $expiried
     */
    public function store( $name, $value, $expiried = 0 ){
        
        if( ! is_dir( $this->cachePath() . self::$cfileName )) 
            File::get()->MakeDir( self::$cfileName, $this->cachePath()  );
        
        $path = $this->cachePath() . self::$cfileName . '/' . $name . '.c';
        
        if (false === @file_put_contents($path, $value)) {
            throw new \RuntimeException('Unable to write file '.$path);
        }
        
        $path = $this->cachePath() . self::$ctimeName . '/' . $name . '.c';
        
        if (false === @file_put_contents($path, time() + ($expiried ? $expiried : 360000))) {
            throw new \RuntimeException('Unable to write ctime '.$path);
        }
        
    }
    
    
    
    public function fetch($name){
        
        $filePath = $this->cachePath(self::$cfileName) . '/' . $name . '.c';
        
        $timePath = $this->cachePath(self::$ctimeName) . '/' . $name . '.c';
        
        if(!file_exists( $filePath )) return false;
        
        $time = time();
        
        $cachedTime = file_get_contents($timePath);
        
        if($cachedTime < $time ){
            
            @unlink($filePath);
            @unlink($timePath);
            
            return false;
            
        }
        
        return file_get_contents( $filePath );
        
        
    }
    
    
}