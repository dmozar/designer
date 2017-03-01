<?php

return $config = [
    
    /**
     * CACHE
     * -------------------------------------------------
     */
    'cache' => [
        'config'    => 'app.config.php',
        'service'   => 'service.config.php',
        'route'     => 'route.config.php',
        'map'       => 'map.config.php',
        'view'      => 'view.config.php',
        'ajax'      => 'ajax.config.php'
    ],
    
    
    /**
     * LANGS
     * ------------------------------------------------
     */
    'langs' => [
        'en' => [
            'title' => 'English',
            'icon'  => '/assets/img/layout/lang/en.png',
            'slug'  => 'en',
            'translate' => __DIR__ . '/../langs/en.php'
        ],
        'sr' => [
            'title' => 'Srpski',
            'icon'  => '/assets/img/layout/lang/sr.png',
            'slug'  => '',
            'translate' => __DIR__ . '/../langs/sr.php'
        ],
        'default_language' => [
            'title' => 'Srpski',
            'icon'  => '/assets/img/layout/lang/sr.png',
            'slug'  => '',
            'translate' => __DIR__ . '/../langs/sr.php'
        ]
    ],
    
    
    /**
     * DEBUG
     * ----------------------------------------------
     */
    'debug' => [
        'profiler' => false
    ],
    
    
    /**
     * MODULES
     * ----------------------------------------------
     */
    'modules' => [
        'Imedia\Designer'           => PATH_ROOT . 'vendor/imedia/designer/src/',
        'Imedia\User'               => PATH_ROOT . 'vendor/imedia/user/src/',
        'Imedia\Proxy'              => PATH_ROOT . 'vendor/imedia/proxy/src/',
        'Imedia\Home'               => PATH_ROOT . 'vendor/imedia/home/src/',
    ],
    
    
    
    /**
     * SERVICES
     * ----------------------------------------------
     */
    'services' => [
        'RouteService'              => 'Minty\Route\RouteService',
        'RouteFactory'              => 'Minty\Route\RouteFactory',
        'BackgroundService'         => 'Application\Service\BackgroundService',
    ],
    
    
    
    /**
     * LAYOUT
     * ----------------------------------------------
     */
    'layout' =>[
        'default'                               => 'Amazon',
        //'Imedia\RealEstate\Home\Controller+'    => 'RealEstateHome',
    ],
    
    
    'sessKey' => 'ci_credential',
    
    
    /**
     * Assets
     * ----------------------------------------------
     */
    'assets' => [
        'cache'             => PATH_PUBLIC . 'cache/assets/',
        'css_collection'    => 'css/style.css',
        'js_collection'     => 'js/script.js',
        'css_name'          => 'style.css',
        'js_name'           => 'script.js',
        'css' => [
            'bootstrap.css'           => __DIR__ . '/../public/css/bootstrap.css',
            'bootstrapslider.css'     => __DIR__ . '/../public/css/bootstrap-slider.min.css',
            'fa.css'                  => __DIR__ . '/../public/css/font-awesome.min.css',
            'amazon.css'              => __DIR__ . '/../public/css/amazon.css',
            'ui.css'                  => __DIR__ . '/../public/css/jquery-ui.min.css',
        ],
        'js' => [
            'jquery.js'               => __DIR__ . '/../public/js/jquery-3.1.1.min.js',
            'migrate.js'              => __DIR__ . '/../public/js/jquery-migrate-3.0.0.min.js',
            'rangy-core.js'           => __DIR__ . '/../public/js/rangy/rangy-core.js',
            'rangy-classapplier.js'   => __DIR__ . '/../public/js/rangy/rangy-classapplier.js',
            'rangy-highlighter.js'    => __DIR__ . '/../public/js/rangy/rangy-highlighter.js',
            'rangy-textrange.js'      => __DIR__ . '/../public/js/rangy/rangy-textrange.js',
            
            'ajaxform.js'             => __DIR__ . '/../public/js/jquery.form.js',
            'bootstrap.js'            => __DIR__ . '/../public/js/bootstrap.js',  
            'bootstrap.slider.js'     => __DIR__ . '/../public/js/bootstrap-slider.min.js',
            'amazon.js'               => __DIR__ . '/../public/js/amazon.js', 
        ],
    ],
    
    /**
     * DATABASE
     * --------------------------------------------
     */
    'database' => [
        'host'      => 'localhost',
        'db'        => 'shop',
        'user'      => 'root',
        'pass'      => '',
        'charset'   => 'utf8',
        'prefix'    => '',
        'proxy_path'=> PATH_ROOT . 'data/proxies/',
        'proxies' => [

        ]
    ],
    
    
    /**
     * Redis
     * ------------------------------------------
     */
    'redis' => [
        'host' => '127.0.0.1',
        'port' => '6379'
    ],
    
    
    /**
     * AJAX CALL
     * -------------------------------------------
     */
    'ajaxcall' => [
        'Filter.Cities'         => 'FilterService::Cities',
        'Filter.Locations'      => 'FilterService::Locations',
        'Filter.Types'          => 'FilterService::Types',
        'Filter.PublishTypes'   => 'FilterService::PublishTypes',
        'Filter.Categories'     => 'FilterService::Categories',
    ],
    
    'views' => [
        'navigation' => __DIR__ . '/../view/partial/navigation.phtml'
     ],
    'view_helpers' => [
        'NavigationHelper' => 'Application\View\NavigationHelper'
    ]
];
