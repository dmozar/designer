<?php


return [
    
    'routes' => [
        'proxy' => [
            'route' => '/proxy',
            'constraints' => [
                
            ],
            'priority' => 1,
            'controller' => 'Imedia\Proxy\Controller\ProxyController',
            'method' => 'index',
            'action' => 'ajax'
        ]
    ],
    'services' => [
        'ProxyFactory' => 'Imedia\Proxy\Service\ProxyFactory',
        'ProxyService' => 'Imedia\Proxy\Service\ProxyService'

    ],
    
    'repository' => [
        
    ],
    
    'views' => [
        
    ],
    'view_helpers' => [
        
    ],
    
    /**
     * Assets
     */
    'assets' => [
       
    ]
    
];