<?php namespace Minty\MySql\ORM;


class Entity {
    
    
    
    public function IsNotEmpty( $value ){
        
        if(is_object( $value ) ){
            
            if($value instanceof Entity){
                
                $primaryKey = \Minty\MySql\ORM\Annotation::get()->getPrimaryKey($value);
                
                return $value->{$primaryKey} ? true : false;
                
            } else {
                
                
                
                if( $value instanceof \Minty\Type\Arrays ) return false;
                if( $value instanceof \Minty\Type\Boolean ) return false;
                if( $value instanceof \Minty\Type\Date ) return false;
                if( $value instanceof \Minty\Type\DateTime ) return false;
                if( $value instanceof \Minty\Type\Day ) return false;
                if( $value instanceof \Minty\Type\Decimal ) return false;
                if( $value instanceof \Minty\Type\Email ) return false;
                if( $value instanceof \Minty\Type\Email ) return false;
                if( $value instanceof \Minty\Type\Integer ) return false;
                if( $value instanceof \Minty\Type\Month ) return false;
                if( $value instanceof \Minty\Type\Name ) return false;
                if( $value instanceof \Minty\Type\NullType ) return false;
                if( $value instanceof \Minty\Type\Phone ) return false;
                if( $value instanceof \Minty\Type\PrimaryKey ) return false;
                if( $value instanceof \Minty\Type\StdClass ) return false;
                if( $value instanceof \Minty\Type\String ) return false;
                if( $value instanceof \Minty\Type\Time ) return false;
                if( $value instanceof \Minty\Type\Year ) return false;
                
                return true;
                
            }
            
        } else {
            
            return $value ? true : false;
            
        }
        
    }
    
    
    public function IsEmpty( $value ){
        
        return $this->IsNotEmpty($value) ? false : true;
        
    }


    private function recursive( $name ){
      
        $e = explode('->', $name);
        
        $v = ':::';
        
        foreach ($e as $i => $p){
            if( $v === ':::'){
                $v = $this->$p;
            } else {
                $v = $v->$p;
                if( ! $v ) return null;
            }
        }
        return $v;
    }
    
    
    public function __get($name){
        
        if(strpos($name, '->') !== false){
            return $this->recursive($name);
        }
        
        if(isset($this->{$name}))
            return $this->{$name};
        return null;
    }
    
    
    public function __set($name, $value) {
        $this->{$name} = $value;
    }
    
}