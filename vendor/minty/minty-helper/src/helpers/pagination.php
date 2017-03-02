<?php

if( !function_exists('pagination') ){
    function pagination($page, $offset, $limit, $total, $html = false, $url = null){
        
        $options = [
            'total'     => $total,
            'limit'     => $limit,
            'offset'    => $offset,
            'start'     => $offset,
            'page'      => $page,
            'current'   => $page,
            'url'       => $url
        ];
        
        \Minty\View\Pagination::get()->create( $options  );
        
        if( ! $html )
            return $options;
        
        ob_start();
        include 'pagination/view.phtml';
        $view = ob_get_clean();
        return $view;
    }
}

