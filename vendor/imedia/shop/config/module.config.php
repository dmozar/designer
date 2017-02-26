<?php


return [
    
    'routes' => [
        
        'index' => [
            'route' => '/',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Imedia\Shop\Controller\Controller',
            'method' => 'index',
            'action' => 'http'
        ]
        
    ],
    
    
    'services' => [
        'HomeService' => 'Imedia\Shop\Service\Service',
    ],
    
    
    'views' => [
        'index'      => __DIR__ . '/../view/master/index.phtml',
        'editor'     => __DIR__ . '/../view/partial/editor.phtml',
    ],
    
    
    'view_helpers' => [
        'HomeHelper' => 'Imedia\Shop\View\Helper'
    ],
    
    
    'assets' => [
        'css' => [
            'home.css'      => __DIR__ . '/../public/css/home.css'
        ],
        'js' => [

        ],
    ]
    
];