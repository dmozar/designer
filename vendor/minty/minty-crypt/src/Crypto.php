<?php namespace Minty\Crypt;


class Crypto {
    
    
    
    
    public function __construct() {
        
    }
    
    
    
    /**
     * 
     * @return \Minty\Crypt\Crypto
     */
    public static function get(){
        return new Crypto();
    }
    
    
    
    
    /**
     * 
     * @param type $pure_string
     * @return type
     */
    public function encrypt($pure_string){
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, APP_SECRET_KEY, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        
        return bin2hex(base64_encode($encrypted_string));
    }
    
    
    
    
    /**
     * 
     * @param type $encrypted_string
     * @return type
     */
    public function decrypt($encrypted_string){
        
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        
        $decrypted_string = @mcrypt_decrypt(MCRYPT_BLOWFISH, APP_SECRET_KEY, base64_decode(pack("H*" , $encrypted_string)), MCRYPT_MODE_ECB, $iv);
        
        return ($decrypted_string);
    }
    
}
