<?php namespace Minty;

use Exception;

trait Template {
    
    
    
    private function TemplateLoader(\Minty\View\ViewModel $ViewModel, \Minty\Cache $Cache, $request ){
        
        $layoutMap = $Cache->get_cached_application_configs('layout');
        
        $layout = $layoutMap['default'];
        
        foreach ($layoutMap as $key => $name){
            $k = str_replace('\\', '/', $key);
            $k = str_replace('+', '/(\w+)+', $k);
            $c = str_replace('\\', '/', $request->controller);
            $p = '~'.$k.'~';
            
            preg_match('~'.$k.'~', $c, $match);
            
            if(count($match)){
                if( strpos($c, $match[0]) === 0){
                    $layout = $name;
                    break;
                }
            }
        }
        
        $layoutPath = PATH_APPLICATION . 'view/' . $layout . '/master/layout.phtml';
        
        if(!file_exists($layoutPath))
            throw new Exception('Layout ( '.$layout.' ) is defined but there is no layout file!');
        
        
        ob_start();
        
        include_once $layoutPath;
        
        $html = ob_get_clean();
        
        echo $html;
        
    }
    
    
}