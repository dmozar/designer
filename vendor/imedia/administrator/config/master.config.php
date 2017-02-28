<?php

return [
    
    'sessKey' => 'adm_credential',
    
    /**
     * Assets
     */
    'assets' => [
        
        'css_collection'    => 'css/admin.css',
        'css_name'          => 'admin_style.css',
        'js_name'           => 'admin_script.js',
        'js_collection'     => 'js/admin.js',
        

        
        'css' => [
            'font-awesome-4.7.0'=> PATH_APPLICATION . 'public/css/font-awesome.min.css',
            'switch.css'        => PATH_APPLICATION . 'public/css/minty.switch.css',
            'administrator.css' => PATH_APPLICATION . 'public/css/administrator.css',
            'jscrollpane.css'   => PATH_APPLICATION . 'public/css/jquery.jscrollpane.css'
        ],
        'js' => [
            
            'mousewheel.js'     => PATH_APPLICATION . 'public/js/jquery.mousewheel.min.js',
            'mwheelIntent.js'   => PATH_APPLICATION . 'public/js/mwheelIntent.js',
            'jscrollpane.js'    => PATH_APPLICATION . 'public/js/jquery.jscrollpane.min.js',
            'minty.table.js'    => PATH_APPLICATION . 'public/js/minty.table.js',
            'switch.js'         => PATH_APPLICATION . 'public/js/minty.switch.js',
            'administrator.js'  => PATH_APPLICATION . 'public/js/administrator.js'
        ],
    ]
    
    
];