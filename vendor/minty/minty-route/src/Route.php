<?php namespace Minty\Route;

use Minty\Route\Http;

class Route {
    
    
    /**
     *
     * @var type 
     */
    private $map = [];
    
    
    /**
     *
     * @var type 
     */
    private $Http;
    
    
    
    /**
     *
     * @var type 
     */
    private $params = [];
    
    
    
    public function __construct() {
        
        $this->Http = new Http; 
        $this->Http->__initialize();
        
        $lang = $this->Http->getLang();
        
        if(isset($lang['translate'])){
            if(file_exists($lang['translate'])){
                
                global $translate;
                
                $translate = include $lang['translate'];
                
            } else {
                throw new \Exception('Translate file ' . $lang['translate'] . ' does not exists');
            }
        } else {
            throw new \Exception('Application config does not have translate configuration');
        }
        
    }
    
    
    /**
     * 
     * @param type $map
     */
    public function setRoutesMap( $map ){
        $this->map = $map;
    }
    
    
    
    /**
     * 
     * @return type
     */
    public function Http(){
        return $this->Http;
    }
    
    
    
    public function __initialize(){
        
        return $this->__resolve();
        
    }
    
    
    
    /**
     * 
     * @return boolean
     */
    public function __resolve(){
        foreach ($this->map as $priority => $routes){
            foreach ($routes as $moduleNamespace => $routeListArray){
                foreach ($routeListArray as $key => $data){
                    if( $request = $this->__match_route($data) ){
                        return $request;
                    }
                }
            }
        }
        
        return false;
    }
    
    
    
    public function params_from_route(){
        return $this->params;
    }
    
    
    
    /**
     * 
     * @param type $data
     * @return boolean|\stdClass
     */
    private function __match_route( $data ){
        
        //print_r($data);
        
        if(array_key_exists('children', $data)){
            
            $children = []; 
            
            foreach ($data['children'] as $key => $d)
                $children[] = [$key => $d];
            
            foreach ($children as $priority => $child){
                
                $key = key($child);
                
                $route_uri = $data['route'] . $child[$key]['route'];
                
                if( $this->__matching( $route_uri, $child[$key]['constraints'] )){
                    
                    $c = $child[$key];
                    
                    $request = new \stdClass();
                    $request->route = $data['route'] . $c['route'];
                    $request->controller = $data['controller'];
                    $request->method = $data['method'];
                    $request->action = $c['action'];
                    
                    $options = [];
                    
                    if(isset($data['options']))
                        $options = array_merge ($options, $data['options']);
                    
                    if(isset($c['options']))
                        $options = array_merge ($options, $c['options']);
                    
                    $request->options = $options;
                    
                    $this->__clear();
                    
                    return $request;
                }
            }
        }
        
        
        
        if( $this->__matching( $data['route'], $data['constraints'] )){
                    
            $request = new \stdClass();
            $request->route = $data['route'];
            $request->controller = $data['controller'];
            $request->method = $data['method'];
            $request->action = $data['action'];
            $request->options = isset($data['options']) ? $data['options'] : [];
            
            $this->__clear();
            
            return $request;      
        }
        
        $this->__clear();
        
        return false;
    }
    
    
    
    /**
     * 
     * @param type $route_uri
     * @param type $constraints
     * @return boolean
     */
    private function __matching( $route_uri, $constraints ){
        
        global $translate;
        
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
        $route_uri = rtrim($route_uri,'/').'/';
        
        //echo $route_uri . ' == ' . $this->Http->getUri() . PHP_EOL;
        
        $patern = '~'.$route_uri.'~';
        
        preg_match($patern, $this->Http->getUri(), $match,  PREG_OFFSET_CAPTURE);
        
        $f = $this->Http->getUri();
        
        //echo $f . PHP_EOL;
        
        if(count($match))
        if($match[0][0] == $f) {

            $this->params = $constraints;
            foreach ($constraints as $alias => $value){
                if(isset($match[$alias])){
                    $this->params[$alias] = $match[$alias][0];
                }
            }
            
            return true;
        }
        
        return false;
    }
    
    
    private function __clear(){
        $this->map = null;
    }
    
}

