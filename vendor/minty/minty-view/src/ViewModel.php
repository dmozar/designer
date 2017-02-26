<?php namespace Minty\View;

use Minty\Cache;
use Exception;

class ViewModel  {
    
    
    /**
     *
     * @var type 
     */
    private $key;

    /**
     *
     * @var type 
     */
    private $html;

    /**
     *
     * @var type 
     */
    private $views = [];

    /**
     *
     * @var type 
     */
    private $options = [];

    /**
     *
     * @var type 
     */
    private $errors = [
        'template_path' => 'Template path %s is not a valid path!',
        'view_not_found'=> 'View %s is not defined',
        'template_name' => 'Template %s is not defined!'
    ];

    /**
     *
     * @var type 
     */
    private $template = null;

    /**
     * 
     * @param type $options
     * @param type $views
     */
    public function __construct($options = [], $views = []) {
        $this->views = $views;
        $this->options = $options;
    }
    
    /**
     * 
     * @param type $options
     * @throws Exception
     */
    public function setOptions( $options ){
        foreach ($options as $key => $value){
            if(isset($this->{$key}))
                throw new Exception ('Option key ('.$key.') is already used or is reserved in ViewModel!');
            
            $this->{$key} = $value;
        }
    }

    /**
     * 
     * @return type
     */
    public function key(){ return $this->key; }

    /**
     * 
     * @return type
     */
    public function html(){ return $this->html; }

    /**
     * 
     * @param type $name
     * @throws Exception
     */
    public function setTemplate( $name ){
        if(! file_exists( $name ))
            throw new Exception(sprintf($this->errors['template_path'], $name));
        
        $this->template = $name;
    }

    /**
     * 
     * @param type $name
     * @return \Minty\View\ViewModel
     * @throws Exception
     */
    public function get( $name ){
        
        if(!array_key_exists($name, $this->views))
                throw new Exception(sprintf($this->errors['view_not_found'], $name));
        
        $this->template = $this->views[$name];
        
        $this->generate_html( $name );
        
        return $this;
    }

    /**
     * 
     * @param type $name
     * @throws Exception
     */
    private function generate_html( $name ){
        
        if( ! $this->template )
            throw new Exception(sprintf($this->errors['template_name'], $name));
        
        foreach ($this->options as $key => $value)
            $this->{$key} = $value;
        
        ob_start(); include $this->template; $this->html = ob_get_clean();
    }

    /**
     * 
     * @param type $name
     * @param type $data
     * @return type
     * @throws Exception
     */
    public function partial( $name, $data, $options = [] ){
       
        if( ! count($data) ) return null;
        
        if(!array_key_exists($name, $this->views)){
            
            $Cache = new Cache;
            $views = $Cache->get_cached_view_configs();
            
            foreach ($views as $module => $config){
                if(isset($config['views']))
                    $this->views = array_merge ($config['views'], $this->views);
            }
        }

        if(!array_key_exists($name, $this->views))
                throw new Exception(sprintf($this->errors['view_not_found'], $name));
        
        $path = $this->views[$name];
        
        if($options){
            foreach ($options as $key => $value)
                $this->{$key} = $value;
        }
        
        foreach ( $data as $key => $value){
            $this->item = $value;
            
            ob_start(); include $path; $html = ob_get_clean();
            
            echo $html;
        }
    }

    /**
     * 
     * @param type $name
     * @param type $data
     * @param type $return
     * @return type
     * @throws Exception
     */
    public function view($name, $data=[], $return = false){
        
        if(!array_key_exists($name, $this->views)){
            
            $Cache = new Cache;
            $views = $Cache->get_cached_view_configs();
            
            foreach ($views as $module => $config){
                if(isset($config['views']))
                    $this->views = array_merge ($config['views'], $this->views);
            }
        }

        if(!array_key_exists($name, $this->views))
                throw new Exception(sprintf($this->errors['view_not_found'], $name));
        
        $path = $this->views[$name];
        
        if($data)
            if(is_array($data))
                foreach ( $data as $key => $value)
                    $this->{$key} = $value;

        ob_start(); include $path; $html = ob_get_clean();
        
        if( $return ) return $html;
        
        echo $html;
    }
    
}
