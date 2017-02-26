<?php namespace Minty\Output;


class OutputManager {
    
    
    /**
     *
     * @var type 
     */
    private static $self;
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function get(){
        
        if(self::$self) return self::$self;
        
        self::$self = new OutputManager; 
        
        return self::$self;
        
    } 
    
    
    /**
     * 
     * @param array $array
     */
    public function json( array $array = [] ){
        
        $this->setHeader();
        
        $json = $this->arrayToJson( $array );
        
        $this->outputJson( $json );
    }
        
    
    
    /**
     * 
     */
    public function setHeader(){
        header('Content-type:application/json;charset=utf-8');
    }
    
    
    
    /**
     * 
     * @param type $array
     */
    public function arrayToJson( $array ){
        
        $json = json_encode($array);

        return $json;
    }
    
    
    
    
    
    /**
     * 
     * @param type $json
     */
    private function outputJson( $json ){
        
        echo $json; exit(0);
        
    }
    
    
    
    /**
     * 
     * @param \Minty\View\ViewModel $ViewModel
     */
    public function view( \Minty\View\ViewModel $ViewModel ){
        
        echo $ViewModel->html(); exit(0);
        
    }
    
}
