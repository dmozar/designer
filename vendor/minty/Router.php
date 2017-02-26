<?php namespace Minty;

use Minty\Cache;

class Router {
    
    
    /**
     *
     * @var type 
     */
    private static $routeList = [];
    
    
    /**
     * 
     * @global type $translate
     * @param type $module
     * @param type $key
     * @param type $params
     * @return type
     */
    public static function FromRoute( $module, $key, $params = [] ){
        
        $hash = md5($module . $key . json_encode($params));
        
        if(isset(self::$routeList[$hash]))
            return self::$routeList[$hash];
        
        global $server;

        $Cache = new Cache;
        
        $routes = $Cache->get_cached_route_configs();
        
        global $translate;
        
        foreach ($routes as $index => $mod){
            
            if($mod){
                
                $k = key($mod);
                
                if($k == $module){
                    
                    if(isset($mod[$module][$key])){
                        
                        $route_uri = $mod[$module][$key]['route'];
                        
                        
                        
                        preg_match_all('/{(.*?)}/', $route_uri, $t);
        
                        foreach ($t as $i => $v){
                            if($v)
                                foreach ($v as $g => $m){
                                    if(array_key_exists($m, $translate))
                                        $route_uri = str_replace ($m, $translate[$m], $route_uri);
                                }
                        }

                        $route_uri = str_replace ('{', '', $route_uri);
                        $route_uri = str_replace ('}', '', $route_uri);

                        $prepare_str = replace_between("(", ")", "%s", $route_uri, true);
        
                        foreach ($params as $alias => & $p) $p = Slugify($p);

                        self::$routeList[$hash] = rtrim($server->url,'/').'/' . ltrim(vsprintf( $prepare_str , $params ),'/');
                        
                        return self::$routeList[$hash];
                    }
                }
            }
        } 
    }
    
    
    
    /**
     * 
     * @global type $server
     * @global \Minty\type $translate
     * @param type $module
     * @param type $key
     * @param type $key2
     * @param type $params
     * @return type
     */
    public static function FromChilde( $module, $key, $key2,  $params = [], $params2 = [] ){
        
        $hash = md5($module . $key . $key2.  json_encode($params) . json_encode($params2));
        
        if(isset(self::$routeList[$hash]))
            return self::$routeList[$hash];
        
        global $server;

        $Cache = new Cache;
        
        $routes = $Cache->get_cached_route_configs();
        
        global $translate;
        
        foreach ($routes as $index => $mod){
            
            if($mod){

                $k = key($mod);
                
                if($k == $module){
                    
                    if(isset($mod[$module][$key])){
                        
                        if(isset($mod[$module][$key]['children'])){
                        
                            if(isset($mod[$module][$key]['children'][$key2])){
                                
                                $route_uri = $mod[$module][$key]['children'][$key2]['route'];
                                
                                

                                preg_match_all('/{(.*?)}/', $route_uri, $t);

                                foreach ($t as $i => $v){
                                    if($v)
                                        foreach ($v as $g => $m){
                                            if(array_key_exists($m, $translate))
                                                $route_uri = str_replace ($m, $translate[$m], $route_uri);
                                        }
                                }

                                $route_uri = str_replace ('{', '', $route_uri);
                                $route_uri = str_replace ('}', '', $route_uri);
                                
                                

                                $prepare_str = replace_between("(", ")", "%s", $route_uri, true);

                                foreach ($params2 as $alias => & $p) $p = Slugify($p);
                                
                                

                                self::$routeList[$hash] = rtrim(self::FromRoute($module, $key, $params),'/') .'/'. ltrim(vsprintf( $prepare_str , $params2 ),'/');

                                return self::$routeList[$hash];
                            }
                        }
                    }
                }
            }
        } 
    }
    
    
}