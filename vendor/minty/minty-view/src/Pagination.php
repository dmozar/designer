<?php namespace Minty\View;

class Pagination {
    
    var $total, $limit, $offset, $current, $start, $end, $pages;
    
    var $display = 7;
    
    public function __construct() {
        
    }
    
    
    public static function get(){
        return new Pagination;
    }
    
    public function create( & $options ){
        
        $this->total    = $options['total'];
        $this->limit    = $options['limit'];
        $this->current  = $options['page'];
        
        $this->generate( $options );
    }
    
    private function generate( & $options ){

        $this->calculate_first();
        $this->fix_pages();
        
        $options['pages'] = [];
        
        for($i = $this->start; $i < $this->end + 1; $i++){
            $options['pages'][$i] = $i;
        }
    }
    
    private function calculate_first(){
        
        $this->pages    = round(ceil($this->total / $this->limit));
        $this->start    = $this->pages - 3;
        $this->end      = $this->pages + 3;
    }
    
    private function fix_pages(){
        
        if($this->start < 1) $this->start = 1;
        if( ($this->end * $this->limit) > $this->total ) $this->end = ceil( $this->total/$this->limit );
        
    }
    
}