<?php namespace Minty\Session;

use Minty\Crypt\Crypto;

class SessionManager {
    
    
    /**
     *
     * @var type 
     */
    var $timeout;
    
    /**
     *
     * @var type 
     */
    var $sess_unit = 60;
    
    
    /**
     *
     * @var type 
     */
    var $session_time;
    
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @return \Minty\Session\SessionManager
     */
    public static function get(){
        return new SessionManager;
    }
    
    
    
    /**
     * 
     * @param type $name
     * @param type $data
     * @param type $lifetime
     */
    public function Store( $name, $data, $lifetime = 0 ){

        $this->set_data($name, $data, $lifetime);
    }
    
    
    /**
     * 
     * @param type $name
     */
    public function Read( $name ){
        return $this->get_data( $name );
    }
    
    
    
    /**
     * 
     * @param type $name
     * @param type $data
     * @param type $expiried
     */
    private function set_data($name, $data, $expiried = null){
        
        $Crypto = Crypto::get();
        
        if($expiried > 0){
            $this->session_time = $expiried * $this->sess_unit;
        } else {
            $this->session_time = 3600000 * $this->sess_unit;
        }
        
        if( ! is_array($data) || !is_object($data))
            $data = ['raw'=>$data];
        
        $serializedData = base64_encode(serialize($data));
        $cryptedData = $Crypto->encrypt( $serializedData );
        
        
        @setcookie($name, $cryptedData, time() + ($this->session_time), "/");
    }
    

    /**
     * 
     * @param type $name
     * @param type $unserialize
     * @return type
     */
    private function get_data( $name, $unserialize = true ){
        
        $Crypto = Crypto::get();

        $cryptedData = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
        
        if( ! $cryptedData ) return null;
        
       
        $decryptedData = $Crypto->decrypt( $cryptedData );
        
        $unserializedData = unserialize( base64_decode($decryptedData) );
        
        if(is_array($unserializedData))
            if(array_key_exists('raw', $unserializedData))
                    return $unserializedData['raw'];
        
        return $unserializedData;
    }
    

    /**
     * 
     * @param type $name
     */
    public function delete($name){
        
        if(isset( $_COOKIE[$name])){
            unset( $_COOKIE[$name] );
            @setcookie($name, null, -1, '/');
        }
    }

    /**
     * 
     */
    public function delete_all(){
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }
    
    
}
