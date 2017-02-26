<?php

/**
 * 
 */
if(!function_exists('user_ip')){
    function user_ip(){
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}

/**
 * 
 */
if(! function_exists('referer_url')){
    function referer_url(){
        return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : null;
    }
}

/**
 * 
 */
if(!function_exists('Slugify')){
    function Slugify($text , $array = array()) { 
        
        $string = strtolower( $text );
        
        $table = array(
            '(TM)' => '', 'Č'  => 'c', 'Ć' => 'c',  'Ž' => 'z', 'Đ' => 'dj', 'Š' => 's', 'č' => 'c',
            'ć'    => 'c', 'ž' => 'z', 'đ' => 'dj', 'š' => 's', 'À' => 'A',  'Á' =>'A',  'Â' => 'A', 
            'Ã'    => 'A', 'Ä' =>'A',  'Å' => 'A',  'Æ' => 'A', 'Ç' => 'C',  'È' =>'E',  'É' => 'E',
            'Ê'    => 'E', 'Ë' => 'E', 'Ì' => 'I',  'Í' => 'I', 'Î' => 'I',  'Ï' => 'I', 'Ñ' => 'N', 
            'Ò'    => 'O', 'Ó' => 'O', 'Ô' => 'O',  'Õ' => 'O', 'Ö' => 'O',  'Ø' => 'O', 'Ù' => 'U', 
            'Ú'    => 'U', 'Û' => 'U', 'Ü' => 'U',  'Ý' => 'Y', 'Þ' => 'B',  'ß' => 'Ss','à' => 'a', 
            'á'    => 'a', 'â' => 'a', 'ã' => 'a',  'ä' => 'a', 'å' => 'a',  'æ' => 'a', 'ç' => 'c', 
            'è'    => 'e', 'é' => 'e', 'ê' => 'e',  'ë' => 'e', 'ì' => 'i',  'í' => 'i', 'î' => 'i', 
            'ï'    => 'i', 'ð' => 'o', 'ñ' => 'n',  'ò' => 'o', 'ó' => 'o',  'ô' => 'o', 'õ' => 'o', 
            'ö'    => 'o', 'ø' => 'o', 'ù' => 'u',  'ú' => 'u', 'û' => 'u',  'ý' => 'y', 'ý' => 'y', 
            'þ'    => 'b', 'ÿ' => 'y', 'Ŕ' => 'R',  'ŕ' => 'r', '?' => '',   '"' => '',  '\''=> '',
            '['    => '',  ']' => '',  '{' => '',   '}' => '',  '=' => '',   '+' => '',  '*' => '',
            '!'    => '',  '~' => '',  '@' => '',   '$' => '',  '%' => '',   '^' => '',  '&' => '',
            '('    => '',  ')' => '',  ',' => '',   '>' => '',  '<' => '',   ':' => '',  '-' => '_',
            ' '    => '_','amp;'=>'','quot;'=>'','8220;'=> '',   '“'=>'',    '®' => '™', 'Ü' => 'u',
            '%2B'  => '+', '®' => '',  '™' => ''
        );
        
        if(count($array)){
            foreach( $array as $k => $r)
                $string = str_ireplace($k, $r, $string);
        }

        $string = strtr($string, $table);
        $string = preg_replace("/(\_)\_+/", "_", $string);
        $string = iconv('utf-8', 'us-ascii//TRANSLIT', $string);

        return  $string;
    }
}

/**
 * 
 */
if(!function_exists('site_url')){
    
    function site_url( $path = ""){
        
        global $server;
        
        return rtrim($server->url,'/').'/'.($path ? ltrim($path,'/'):'');
        
    }
    
}

/**
 * 
 */
if(!function_exists('assets_url')){
    
    function assets_url( $path = ""){
        
        global $server;
        
        return rtrim($server->assets_url,'/').'/'.($path ? ltrim($path,'/'):'');
        
    }
    
}

/**
 * 
 */
if( !function_exists('redirect')){
    function redirect( $url ){
        header('Location:'.$url);
        exit(0);
    }
}