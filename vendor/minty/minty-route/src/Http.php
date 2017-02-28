<?php namespace Minty\Route;

use Minty\Cache;


/**
 * 
 */
class Http {
    
    private 
            $server;
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     */
    public function __initialize(){
        
        $this->server = new \stdClass();
        
        $s = ($_SERVER);
        
        $this->prepare_server( $s );
        
        global $server;
        
        $server = $this->server;
    }
    
    
    
    /**
     * 
     * @param type $s
     */
    private function prepare_server( $s ){
        
        $this->server->lang = null;
        
        $this->protocol($s);
        
        $this->uri($s);
        
        $this->resolve_language();
        
        $this->url($s);
    }
    
    
    
    /**
     * 
     */
    private function protocol( $s ){
        $this->server->protocol = $s['SERVER_PORT'] == '80' 
                ? 'http' : ($S['REQUEST_SCHEME']?: $s['SERVER_PORT'] == '443' ? 'https' : 'http');
    }
    
    
    
    /**
     * 
     */
    private function uri( $s )
    {
        $this->server->uri = str_replace('index.php', '', $s['SCRIPT_NAME']) . $s['REQUEST_URI']; 
        
        $e = explode('?', $this->server->uri);
        
        if(count($e) > 1){
            $this->server->uri = rtrim(reset($e),'/');
            $this->server->get = str_replace('//','',end($e));
        } else {
            $this->server->uri = $this->server->uri;
            $this->server->get = '';
        }
        
        
        
        if($_GET)
            $this->server->query = $_GET;
        else 
            $this->server->query = null;
        
        if($_POST)
            $this->server->post = $_POST;
        else 
            $this->server->post = null;
        
        $this->server->uri = ltrim($this->server->uri,'/');
    }
    
    
    
    /**
     * 
     * @param type $name
     * @return boolean
     */
    public function getFromPost( $name ){
        
        if(isset($this->server->post))
            if(isset($this->server->post[$name]))
                return $this->server->post[$name];
        
            return false;
    }
    
    
    
    /**
     * 
     */
    private function url($s){
        
        $uri = $this->server->uri;
        
        
        $this->server->host 
                = $s['HTTP_HOST'];
        
        $this->server->uri = $uri;
        
        $this->server->url 
                = $this->server->protocol . '://' . $this->server->host;
        
        $a = explode('?', $this->server->url);
        
        
        if( count($a) > 1){
            $args = array_pop($a);
        } else {
            $args = [];
        }
        
        $this->server->args = $args;
        
        $this->server->url = reset($a) . ($this->server->lang['slug'] ? '/'.$this->server->lang['slug']:'') . '/';
        
        $this->server->assets_url = reset($a);

        $this->server->curent_url 
                = rtrim($this->server->url,'/') .'/'. ltrim($this->server->uri,'/');
    }
    
    
    
    
    /**
     * 
     */
    private function resolve_language(){
        
        $Cache = new Cache;
        
        $langs = $Cache->get_cached_application_configs('langs');
        
        $this->server->uri = '/'.rtrim(ltrim($this->server->uri,'/'),'/').'/';
        
        if($langs){
            
            $length  = 2;         
            $pattern = "~/(\w{1,2})/~";
            
            $match = [];

            preg_match($pattern, '/'. rtrim($this->server->uri,'/').'/', $match);
            
            if(count($match) == 2){
                
                $macth = $match[1];
                
                if (ctype_alpha($macth)) 
                    if(array_key_exists($macth, $langs)){
                        $this->server->uri = substr($this->server->uri ,strlen($macth.'/'));
                        $this->server->lang = $langs[$macth];
                    }
            }
            
            
            if( ! $this->server->lang)
                $this->server->lang = $langs['default_language'];
            
            $this->server->lang_code = $this->server->lang['slug'];
            
        }
    }
    

    /**
     * 
     * @return type
     */
    public function getLang(){
        return $this->server->lang;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getLangCode(){
        return $this->server->lang_code;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getUri(){
        return str_replace('//','/',$this->server->uri);
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function uri_array(){
        return explode('/', $this->server->uri);
    }
    
    
    /**
     * 
     * @return type
     */
    public function getRequest(){
        return $this->server->get;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getHost(){
        return $this->server->host;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getUrl(){
        return $this->server->url;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getAssetsUrl(){
        return $this->server->assets_url;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getCurrentUrl(){
        return $this->server->current_url;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getProtocol(){
        return $this->server->protocol;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getArgs(){
        return $this->server->args;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getQuery(){
        return $this->server->query;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getPost(){
        return $this->server->post;
    }
}

