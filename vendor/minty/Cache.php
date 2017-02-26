<?php namespace Minty;

use Exception;
use Minty\File\File;

class Cache {
    
    
    
    /**
     *
     * @var type 
     */
    private $configs = [];
    
    
    /**
     *
     * @var type 
     */
    private $modules = [];
    
    
    /**
     *
     * @var type 
     */
    public static $cached_services = [];
    
    
    
    /**
     *
     * @var type 
     */
    public static $cached_routes = [];
    
    
    
    /**
     *
     * @var type 
     */
    public static $cached_app = [];
    
    
    
    /**
     *
     * @var type 
     */
    public static $cached_ajax = [];
    
    
    
    /**
     *
     * @var type 
     */
    public static $cached_views = [];
    
    
    
    /**
     *
     * @var type 
     */
    public static $ajaxcalls = [];
    
    
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @return type
     */
    private function get_cache_path(){
        
        return PATH_ROOT . 'data/cache/';
        
    }
    
    
    /**
     * 
     * @return type
     */
    private function get_date_now(){
        $date = new \DateTime('now');
        return $date->format('d.m.Y H:i:s');
    }
    
    
    private $tmp_appconf = [];
    
    
    /**
     * 
     * @throws Exception
     */
    public function cache_config(){

        $application_config = PATH_APPLICATION . 'config/application.config.php';
        
        if( !file_exists( $application_config ))
            throw new Exception('Application does not have a configuration file. Please create application.config.php file in your Application/config directory!');
        
        
        $config = $this->tmp_appconf = include $application_config;
        
        
        if(array_key_exists('cache', $config))
            \Minty\Application::$cachePath = $config['cache'];
        
        
        if(array_key_exists('modules', $config))
                $this->modules = $config['modules'];
        
        $config_path = $this->get_cache_path() . $config['cache']['config'];
        
        if(file_exists($config_path) && ENV == 'production') return include_once $config_path;
        
        $this->write_cache($config, $config['cache']['config']);
        
        $this->cache_map($config);
        
        $this->cache_views( $config );
        
        $this->cache_ajaxcalls( $config );
        
        return $this->configs = $this->cache_module_config($config_path,$config['cache']['config']);
    }
    
    
    
    /**
     * 
     * @param type $config
     */
    private function cache_views( $config ){
        
        $array = [];
        
        
        if(array_key_exists('views', $this->tmp_appconf)){
            $array['Application'] = [
                'views' => @$this->tmp_appconf['views'],
                'helpers' => @$this->tmp_appconf['view_helpers']
            ];
        }
        
        
        foreach ($config['modules'] as $key => $module){
            
            $conf = include( $module . '../config/module.config.php');
            
            if(array_key_exists('views', $conf))
            $array[$key] = [
                'views' => @$conf['views'],
                'helpers' => @$conf['view_helpers']
            ];
        }
        
        $this->write_cache($array, $config['cache']['view']);
    }
    
    
    
    /**
     * 
     * @param type $config
     */
    private function cache_ajaxcalls( $config ){
        
        $array = [];
        
        if(array_key_exists('ajaxcall', $this->tmp_appconf)){
            $array['Application'] = @$this->tmp_appconf['ajaxcall'];
        }
        
        
        foreach ($config['modules'] as $key => $module){
            
            $conf = include( $module . '../config/module.config.php');
            
            if(array_key_exists('ajaxcall', $conf))
            $array[$key] = @$conf['ajaxcall'];
        }
        
        $this->write_cache($array, $config['cache']['ajax']);
    }
    
    
    
    /**
     * 
     * @param type $config_path
     */
    private function cache_module_config( $config_path, $p ){
        
        $conf = include($config_path);
        
        if(array_key_exists('modules', $conf)){
            foreach ($conf['modules'] as $key => $path){
                
                if(!array_key_exists('modules', $conf))
                        $conf['modules'] = [];
                
                if(is_array($path))
                    print_r($path);
                
                $conf['modules'][$key] = include($path.'../config/module.config.php');
            }
            
            $this->write_cache($conf, $p);
        }
        
        $this->cache_services( $conf );
        $this->cache_route( $conf );
        
        return $conf;
    }
    
    
    
    /**
     * 
     * @param type $array
     * @throws Exception
     */
    private function cache_services( $array ){
        
         if( ! array_key_exists('services', $array))
            throw new Exception('Service list not found in cached config list');
         
         $list = $array['services'];
         
         if(array_key_exists('modules', $array)){
            foreach ($array['modules'] as $nmsp => $data){
                if(array_key_exists('services', $data)){
                    $list = array_merge( $list, $data['services'] );
                }
                if(array_key_exists('repository', $data)){
                    $list = array_merge( $list, $data['repository'] );
                }
            }
        }
        
        
        
        
        $this->write_cache($list, $array['cache']['service']);
    }
    
    
    /**
     * 
     * @param type $array
     * @throws Exception
     */
    private function cache_route( $array ){
        
        if( ! array_key_exists('services', $array))
            throw new Exception('Service list not found in cached config list');
         
         $list = [];
         
         if(array_key_exists('modules', $array))
            foreach ($array['modules'] as $nmsp => $data)
                if(array_key_exists('routes', $data))
                    $list = array_merge( $list, [$nmsp => $data['routes']] );

                
        $nlist = [];
        
        foreach ($list as $namespaces => $data)
            foreach ($data as $name => $value){
                if(array_key_exists('priority', $value))
                        $nlist[$value['priority']] = [];
                
                $nlist[] = array($namespaces => $data);
            }
        
        $this->write_cache($nlist, $array['cache']['route']);
    }
    
    
    
    /**
     * 
     * @param type $array
     * @throws Exception
     */
    private function cache_map( $array ){
        
        if( ! array_key_exists('map', $array['cache']))
            throw new Exception('Service cache map not found in cached config list');
        
        $this->write_cache($this->modules, $array['cache']['map']);
        
    }
    
    
    
    /**
     * 
     * @param type $array
     * @param type $path
     */
    private function write_cache($array, $path){
        
        $message = '<?php ' . PHP_EOL;
        $message.= PHP_EOL;
        $message.= '// Auto generated by MInty - ' . $this->get_date_now() . PHP_EOL;
        $message.= PHP_EOL;
        $message.= 'return '. var_export($array, true);
        $message.= ';' . PHP_EOL;
            
        $config_path = $this->get_cache_path() . $path;
            
        File::get()->write(  $message, $config_path  );
    }
    
    
    
    /**
     * 
     * @param type $path
     * @return type
     */
    public function getMap( $path ){
        
        return include $this->get_cache_path() . $path; 
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function get_cached_service_configs(){
        
        
        if( ! self::$cached_services ) {
        
            $caches = \Minty\Application::$cachePath;
        
            $Cache = new Cache;
            
            self::$cached_services = include $Cache->get_cache_path() . $caches['service'];
        }

        return self::$cached_services; 
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function get_cached_route_configs(){
        
        
        if( ! self::$cached_routes) {
        
            $caches = \Minty\Application::$cachePath;
        
            $Cache = new Cache;
            
            self::$cached_routes = include $Cache->get_cache_path() . $caches['route'];
        }

        return self::$cached_routes; 
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function get_cached_application_configs( $name ){
        
        
        if( ! self::$cached_app) {
        
            $caches = \Minty\Application::$cachePath;
        
            $Cache = new Cache;
            
            self::$cached_app = include $Cache->get_cache_path() . $caches['config'];
        }

        return self::$cached_app[$name]; 
    }
    
    
    
    
    public function get_cached_ajax_configs( $name ){
        
        
        if( ! self::$cached_ajax) {
        
            $caches = \Minty\Application::$cachePath;
            $Cache = new Cache;
            self::$cached_ajax = include $Cache->get_cache_path() . $caches['ajax'];
        }
        
        if(array_key_exists($name, self::$cached_ajax))
            return self::$cached_ajax[$name]; 
        
        
        foreach (self::$cached_ajax as $module => $list){
            if(array_key_exists($name, $list)){
                self::$cached_ajax[$name] = $list[$name];
                return self::$cached_ajax[$name];
            } 
        }
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    public function get_cached_view_configs(){
        
        
        if( ! self::$cached_views) {
        
            $caches = \Minty\Application::$cachePath;
        
            $Cache = new Cache;
            
            self::$cached_views = include $Cache->get_cache_path() . $caches['view'];
        }

        return self::$cached_views; 
    }
    
}