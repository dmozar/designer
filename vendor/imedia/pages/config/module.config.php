<?php


return [
    
    'routes' => [
        
        'tutorial' => [
            'route' => '/{:tutorial}',
            'constraints' => [
                'view' => 'tutorial'
            ],
            'priority' => 1,
            'controller' => 'Imedia\Pages\Controller\Controller',
            'method' => 'index',
            'action' => 'http'
        ]
        
    ],
    
    
    'services' => [
        'PagesService' => 'Imedia\Pages\Service\Service',
    ],
    
    
    'views' => [
        'errorpage'  => __DIR__ . '/../view/master/error.phtml',
        'tutorial'   => __DIR__ . '/../view/master/tutorial.phtml',
    ],
    
    
    'view_helpers' => [
        'PagesHelper' => 'Imedia\Pages\View\Helper'
    ],
    
    
    'assets' => [
        'css' => [
            
        ],
        'js' => [

        ],
    ]
    
];