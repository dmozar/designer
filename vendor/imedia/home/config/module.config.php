<?php


return [
    
    'routes' => [
        
        'index' => [
            'route' => '/',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Imedia\Home\Controller\Controller',
            'method' => 'index',
            'action' => 'http'
        ]
        
    ],
    
    
    'services' => [
        'HomeService' => 'Imedia\Home\Service\Service',
    ],
    
    
    'views' => [
        'index'      => __DIR__ . '/../view/master/index.phtml',
        'editor'     => __DIR__ . '/../view/partial/editor.phtml',
    ],
    
    
    'view_helpers' => [
        'HomeHelper' => 'Imedia\Home\View\Helper'
    ],
    
    
    'assets' => [
        'css' => [
            
        ],
        'js' => [

        ],
    ]
    
];