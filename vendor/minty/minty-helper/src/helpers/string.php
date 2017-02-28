<?php


if(!function_exists('replace_between')){
    
    function replace_between($start, $end, $new, $source, $clean_tags = false) {
        $str = preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$new.'$3', $source);
        
        if($clean_tags){
            $str = str_replace($start, '', $str);
            $str = str_replace($end, '', $str);
        }
        
        return $str;
    }
    
}