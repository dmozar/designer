<?php

if( ! function_exists('minty_table')){
    function minty_table( $url, $id="#table", $height = 300, $limit = 25 ){
        
        $html = '<div id="'. (str_replace('#','',$id)) .'" class="minty-table" data-id="'.$id.'" data-source="'.$url.'" data-limit="'.$limit.'" data-offset="0" style="max-height:'.$height.'px;" >';

        $html.= '</div>';
        
        return $html;
    }
}


if( !function_exists('minty_table_data')){
    function minty_table_data(Minty\MySql\ORM\Paginator $result, $cells = [], $key = null, $page = 1, $pagination = 1, $plus = []){
        
        $data           = [];
        $data['total']  = $result->total;
        
        $next = $result->offset + $result->limit;
        
        if($next > $data['total']) $next = $result->offset;
        
        $data['offset']     = $next;
        $data['items']      = [];
        $data['names']      = [];
        $data['key']        = $key;
        $data['pagination'] = $pagination;
        $data['page']       = $page;
        $data['limit']      = $result->limit;
        
        Minty\View\Pagination::get()->create( $data );
        
        if($data['total']){
            
            $iteration = 0;
            $id = null;
            
            foreach ($result->result as $entity){
                
                $item = []; $iteration++;
                
                if($key){
                    if(isset($entity->{$key}))
                    $id = $entity->{$key};
                }
                
                foreach ($cells as $tableName => $cellName){
                    
                    if($iteration == 1)
                        $data['names'][] = $tableName;
                    
                    $value = $entity->$cellName;
                    
                    if( !is_object($value)) {
                        
                        if(array_key_exists($tableName, $plus)){
                            if(isset($plus[$tableName][$entity->$cellName])){
                                if(is_array($plus[$tableName]))
                                    $item[] = str_replace('_key_',$id,$plus[$tableName][$entity->$cellName]);
                                else
                                    $item[] = str_replace('_key_',$id,$plus[$tableName]);
                            } else {
                                $item[] = $entity->$cellName;
                            }
                        } else {
                            $item[] = $entity->$cellName;
                        }
                    }
                }
                $data['items'][] = $item;
            }
        }
        
        return $data;
    }
}

