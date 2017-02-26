<?php


return [
    
    'routes' => [
        
        'home' => [
            'route' => '/backside',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Administrator\Home\Controller\HomeController',
            'method' => 'index',
            'action' => 'http',
            
        ]
        
    ],
    'services' => [
        'HomeService' => 'Administrator\Home\Service\HomeService',
        'GridService' => 'Administrator\Home\Service\GridService',
    ],
    
    'views' => [
        'home'     => __DIR__ . '/../view/master/index.phtml',
    ],
    'view_helpers' => [
        'HomeHelper' => 'Administrator\Home\View\HomeHelper',
        'GridHelper' => 'Administrator\Home\View\GridHelper'
    ],
    
    'master' => PATH_ROOT . 'vendor/imedia/administrator/config/master.config.php',
    
    /**
     * Assets
     */
    'assets' => [
        
        'remove' => [
            'realestate.css'
        ],
        
        'css' => [
            
        ],
        'js' => [

        ],
    ]
    
];