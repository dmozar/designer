<?php namespace Minty;

use Minty\Service\ServiceLocator;
use Minty\Route\RouteService;
use Minty\Autoload;
use Minty\Template;
use Minty\Session\SessionManager;

class Application extends ServiceLocator {
    
    use Template;
    
    
    /**
     *
     * @var type 
     */
    public $ServiceLocator;
    
    
    /**
     *
     * @var type 
     */
    private $autoloader;
    
    
    
    /**
     *
     * @var type 
     */
    public static $cachePath = [];
    
    
    
    /**
     *
     * @var type 
     */
    protected $configs = [];
    
    
    
    /**
     *
     * @var type 
     */
    protected $fetch_class;
    
    
    
    /**
     *
     * @var type 
     */
    protected $fetch_method;
    
    
    
    /**
     *
     * @var type 
     */
    protected $module_path;
    
    
    
    /**
     *
     * @var type 
     */
    public static $Profiler;
    
    
    /**
     * 
     */
    public function __construct() {
        
        parent::__construct();
        
        $this->Environment();
        
        $this->ServiceLocator =& $this;
    }
    
    
    
    /**
     * Environment
     */
    private function Environment(){
        
        if(ENV == 'development') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function  getServiceLocator(){
        return $this->ServiceLocator;
    }
    
    
    
    
    /**
     * Start
     */
    public static function Start(){
        
        \Minty\Helper\HelperManager::get()->load('url');
        \Minty\Helper\HelperManager::get()->load('string');
        \Minty\Helper\HelperManager::get()->load('pagination');
        
        $Application = new Application;
        
        $Application->CacheConfigs();
        
        $Application->autoloader = new Autoload( $Application->configs['cache']['map'] );
        
        $Cache = new Cache();
        
        $options = [
            'routes' => $Cache->get_cached_route_configs()
        ];
        
        $Application->Route = $Application->ServiceLocator->factory('RouteFactory', 'Routing', $options, new RouteService());
        
        if( ! $request = $Application->Route->request() )
        {
            //TODO Show 404
            die('Page not found');
        }
        
        
        $Application->initialize_controller( $request, $Application );
    }
    
    
    /**
     * 
     */
    protected function CacheConfigs(){
        
       
        $Cache = new Cache();
        $this->configs = $Cache->cache_config();
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getConfigs(){
        return $this->configs;
    }
    
    
    /**
     * 
     * @return type
     */
    public function fetch_class(){
        return $this->fetch_class;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function fetch_method(){
        return $this->fetch_method;
    }
    
    
    
    /**
     * 
     * @param type $request
     * @param type $Application
     */
    private function initialize_controller( $request, & $Application ){
        
        $is_ajax = false;
        
        if($request->action == 'ajax'){
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
                $is_ajax = true;
            } else {
                //TODO Show404
                die('Page not found');
            }
        }
        
        global $session_key;
        
        $sessKey = $this->configs['sessKey'];
        
        \Minty\Helper\HelperManager::get()->load('language');
        
        $Controller = new $request->controller;
        
        
        $moduleConfigs = $Controller->getModuleConfigs();
        
        if(isset($moduleConfigs['master'])){
            $masterConfigs = include ($moduleConfigs['master']);
            if(isset($masterConfigs['sessKey']))
                $sessKey = $masterConfigs['sessKey'];
        }
        
        if(isset($moduleConfigs['sessKey'])){
            $sessKey = $masterConfigs['sessKey'];
        }
        
        if($request->controller !== 'Imedia\User\Controller\UserController' && $request->method !== 'login' && $sessKey && $request->method !== 'logout'){
            $session_key = ($sessKey);
            SessionManager::get()->Store('appKey', $session_key);
        } 
        
        if($request->controller == 'Imedia\User\Controller\UserController' && ($request->method == 'login' || $request->method == 'logout')){
            $session_key = SessionManager::get()->Read('appKey');
        }
        
        $Controller->setRoute( $this->Route );
        $Controller->setOptions( $request->options );
        $Controller->setCache( new \Minty\Cache );
        $Controller->setServiceLocator( $this->ServiceLocator );
        $Controller->getUser();
        
        global $e;
        
        $e = new \stdClass();
        $this->module_path  = $Controller->getModulePath();
        $this->fetch_class  = $e->controller    = $request->controller;
        $this->fetch_method = $e->method        = $request->method;
        
        if( method_exists( $Controller, '__initialize'))
        $Controller->__initialize();
        
        \Minty\Event\EventManager::get()->register($request->controller, $Controller);
        
        //TODO validate request call method
        
        $method = $request->method;
        
        $view = $Controller->$method();

        $e->options = $request->options;
        $e->http    = $this->Route->Route()->Http();
        
        \Minty\Event\EventManager::get()->ListenerEvent( $e );
        
        $Cache = new Cache();
        
        if( $debug = $Cache->get_cached_application_configs('debug') ){
            if(isset($debug['profiler'])){
                if($debug['profiler'])
                    $this->Profiler( $is_ajax );
            }
        }
        
        
        $this->AssetManager( $Cache, $Controller, $this->Route );
        $this->TemplateLoader( $view, $Cache, $request );
        
        exit(0);
    }
    
    
    
    private function AssetManager( $Cache, $Controller, $route ){
        
        $AssetControler = \Minty\Assets\AssetsController::get( );
        $AssetControler->setRoute( $route );
        $AssetControler->initialize();
        $AssetControler->setModuleConfigs( $Controller->getModuleConfigs() );
        $AssetControler->create();
        
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function getModulePath(){
        
        return $this->module_path;
        
    }
    
    
    
    /**
     * 
     * @global type $start_memory
     */
    private function Profiler( $is_ajax ){
        
        
        global $start_memory;

        $size = memory_get_usage() - $start_memory;
        
        $queries = (\Minty\MySql\ORM\EventManager::get()->getEvents() );
        
        $sqlLogs = [];


        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        $size = $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .$filesizename[$i] : '0 Bytes';
        
        $string = '<div style="display:block;position: fixed; bottom:0px; left:0px; right:0px; background-color: #fff; z-index:999999; border-top:solid 1px #ccc; cursor:default;">';
        
            $string.= '<ul style="display:block;position:relative;margin:0;padding:0;">';
            
                // memo usage
                $string.= '<li style="display:inline-block;padding:0;margin:0; list-style:none;font-size:13px;">';
                    
                    $string.= '<span style="display:block;padding:10px 15px;">Mem. Usage:'.$size.'</span>';
                
                $string.= '</li>';
                
                // sql
                $string.= '<li style="display:inline-block;padding:0;margin:0; list-style:none;font-size:13px;">';
                    
                    $string.= '<span style="display:block;padding:10px 15px;">Queries:'.count($queries).'</span>';
     
                $string.= '</li>';
                
                // max sql
                $max = 0;
                $last = null;
                
                if(count($queries)){
                    foreach ($queries as $index => $q){
                        $t = $q['execution_time'];
                        if($max < $t){
                            $last = $q;
                            $max = $t;
                        }
                    }
                }
                
                if($last){
                    // sql
                    $string.= '<li style="display:inline-block;padding:0;margin:0; list-style:none;font-size:13px;">';

                        $string.= '<span style="display:block;padding:10px 15px;">Longest Query: ('.($last['key']).': '.( round($last['execution_time'],6)).')</span>';

                    $string.= '</li>';
                }
                
            
            $string.= '</ul>';
        
        $string.= '</div>';

        self::$Profiler =  $string;
        
        
        if(count( $queries )){
            
            $path = PATH_ROOT . 'data/logs/' . time() . '.log';
            
            $data = print_r($queries,true);
            
            file_put_contents(PATH_ROOT . 'data/logs/' . time() . '.log', $data);
            
        }
        
        
    }
    
    
}