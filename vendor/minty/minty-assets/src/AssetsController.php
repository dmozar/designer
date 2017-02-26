<?php namespace Minty\Assets;

use Minty\Extension\ExtensionInterface;
use Minty\Assets\FileCache;
use Assetic\AssetManager;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Filter\CssMinFilter;
use Assetic\Filter\JSMinFilter;
use Assetic\AssetWriter;
use Assetic\Asset\AssetCache;

class AssetsController implements ExtensionInterface {
    
    /**
     *
     * @var type 
     */
    private static $assetManager;
    
    
    /**
     *
     * @var type 
     */
    private static $configs = [];
    
    
    /**
     *
     * @var type 
     */
    private static $style_version = 1;
    
    
    /**
     *
     * @var type 
     */
    private static $js_version = 1;
    
    
    /**
     *
     * @var type 
     */
    private static $route;
    
    
    
    /**
     *
     * @var type 
     */
    private $style, $js;
    
    
    private static $cdn_css = [], $cdn_js = [];
    
    
    
    /**
     * 
     */
    public function __construct( ) {
        
    }
    
    
    /**
     * 
     * @param type $url
     */
    public static function ADD_CDN_JS( $url ){
        self::$cdn_js[] = $url;
    }
    
    
    /**
     * 
     * @param type $url
     */
    public static function ADD_CDN_CSS( $url ){
        self::$cdn_css[] = $url;
    }
    
    
    
    /**
     * 
     * @param type $route
     */
    public function setRoute( $route ){
        self::$route = $route;
    }
    
    
    /**
     * 
     * @return type
     */
    public static function get( ){
        
        return self::$assetManager ?: new AssetsController( );
        
    }
    
    
    /**
     * 
     * @param type $assets
     */
    private function merge_assets($assets){
        
        
        
        /**
         * 
         */
        if( !array_key_exists('css', self::$configs))
                self::$configs['css'] = [];
        
        /**
         * 
         */
        if( !array_key_exists('js', self::$configs))
                self::$configs['js'] = [];
        
        /**
         * 
         */
        if(array_key_exists('css', $assets)){
            foreach ($assets['css'] as $key => $value){
                self::$configs['css'][$key] = $value;
            }
        }
        
        /**
         * 
         */
        if(array_key_exists('js', $assets)){
            foreach ($assets['js'] as $key => $value){
                self::$configs['js'][$key] = $value;
            }
        }
        
        /**
         * 
         */
        if(array_key_exists('cache', $assets)){
            self::$configs['cache'] = $assets['cache'];
        }

        /**
         * 
         */
        if(array_key_exists('css_collection', $assets)){
            self::$configs['css_collection'] = $assets['css_collection'];
        }
        
        /**
         * 
         */
        if(array_key_exists('js_collection', $assets)){
            self::$configs['js_collection'] = $assets['js_collection'];
        }
        
        
        /**
         * 
         */
        if(array_key_exists('css_name', $assets)){
            self::$configs['css_name'] = $assets['css_name'];
        }

        /**
         * 
         */
        if(array_key_exists('js_name', $assets)){
            self::$configs['js_name'] = $assets['js_name'];
        }
        
        if($this->removeList){
            $this->remove( $this->removeList , self::$configs );
            array_filter(self::$configs, function($value) { return $value !== ''; });
        }
        
    }
    
    
    private function remove( $removeList, & $configs){
        
        foreach ($configs as $key => & $values){
            foreach ($removeList as $i => $r){
                if(is_array($values)){
                    if(array_key_exists($r, $values)){ 
                        unset($values[$r]);
                    }
                }
            }
        }
    }
    
    
    private $removeList = null;
    
    
    /**
     * 
     * @param array $config
     * @return \Dady\Assets\AssetsController
     */
    public function setModuleConfigs(array $config) {
        
        if(array_key_exists('assets', $config)){
            $this->merge_assets ($config['assets']);
        
            if(array_key_exists('remove', $config['assets'])){

                $this->removeList = $config['assets']['remove'];
                $this->remove( $this->removeList , self::$configs );
                array_filter(self::$configs, function($value) { return $value !== ''; });
            }
        }
        
        
        if(array_key_exists('master', $config)){
            $master = include $config['master'];
            if(array_key_exists('assets', $master))
                $this->merge_assets($master['assets']);
        }
        
        unset($config);
        
        return $this;
    }
    
    
    
    /**
     * 
     */
    public function initialize(){
        
        require 'CssMin.php';
        require 'jsmin.php';
        
        $Cache = new \Minty\Cache;
        
        $aPc = $Cache->get_cached_application_configs('assets');
        
        if($aPc)
            $this->merge_assets( $aPc );
        
        
    }
    
    
    
    public function create(){
        
        if($this->removeList){
            $this->remove( $this->removeList , self::$configs );
            array_filter(self::$configs, function($value) { return $value !== ''; });
        }
        
        
        $e = explode('.', self::$configs['css_name']);
        $this->style = reset($e);
        
        $e = explode('.', self::$configs['js_name']);
        $this->js = reset($e);
        
        $this->createCss();
        $this->createJs();
    }
    
    
    
    /**
     * 
     * @return type
     */
    private function createCss(){
        
        $collection = array();
        
        foreach ( self::$configs['css'] as $key => $css){
            $collection[] = new FileAsset($css );
        }
        
        $styles = new AssetCollection($collection, array(
            new CssMinFilter(),
        ));
        
        
        $styles->setTargetPath( self::$configs['css_name'] );
        
        $am = new AssetManager();
        $am->set($this->style, $styles);
        
        $lastmodifiled = self::$style_version = $styles->getLastModified();
        if( $lastmodifiled == FileCache::get()->fetch($this->style)) return;
        
        FileCache::get()->store($this->style, $lastmodifiled);
        
        $writer = new AssetWriter(PATH_PUBLIC.'/assets/css');
        $writer->writeManagerAssets($am);
        
        
        $this->copy_resources();
    }
    
    
    
    
    /**
     * 
     * @return type
     */
    private function createJs(){
        
        $collection = array();
        
        foreach ( self::$configs['js'] as $key => $css){
            $collection[] = new FileAsset($css );
        }
        
        $scripts = new AssetCollection($collection, array(
            new JSMinFilter(),
        ));
        
        $scripts->setTargetPath( self::$configs['js_name'] );
        
        $am = new AssetManager();
        $am->set($this->js, $scripts);
        
        $lastmodifiled = self::$js_version = $scripts->getLastModified();
        if( $lastmodifiled == FileCache::get()->fetch($this->js)) return;
        
        FileCache::get()->store($this->js, $lastmodifiled);
        
        $writer = new AssetWriter(PATH_PUBLIC.'/assets/js');
        $writer->writeManagerAssets($am);
    }
    
    
    
    /**
     * 
     */
    private function copy_resources(){
        
        $jsonconfig = json_decode(file_get_contents( PATH_ROOT . 'composer.json' ));
        
        $autoload = ($jsonconfig->autoload->{'psr-4'});
        
        foreach ($autoload as $key => $path){
            
            $src = PATH_ROOT . $path . '../public/img';
            $dst = PATH_PUBLIC . 'assets/img';
            
            File::get()->recurse_copy($src, $dst);
        }
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function CSS(){
        return '<link rel="stylesheet" href="'.( self::$route->Route()->Http()->getAssetsUrl() .('/assets/css/'.self::$configs['css_name'].'?v='.self::$style_version)).'">' . PHP_EOL;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function JS(){
        return '<script src="'.( self::$route->Route()->Http()->getAssetsUrl() .('/assets/js/'.self::$configs['js_name'].'?v='.self::$js_version)).'"></script>' . PHP_EOL;
    }
    
    
    /**
     * 
     * @return type
     */
    public static function CDN_CSS(){
        foreach (self::$cdn_css as $css)
        echo '<link rel="stylesheet" href="'.($css).'">' . PHP_EOL;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public static function CDN_JS(){
        foreach (self::$cdn_js as $js)
        echo '<script src="'.($js).'"></script>' . PHP_EOL;
    }
    
}
