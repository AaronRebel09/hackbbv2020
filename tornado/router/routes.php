<?php
// EJEMPLO
//    "/path"=>[
//      'path'=>'path',
//      'get'=>array('Controller','method'),
//      'post'=>array('Controller','method'),
//    ]  
global $ROUTES;
$ROUTES=[
    "/api/getMaxDataByDay"=>[
        'path'=>'/api/getMaxDataByDay',
        'allow'=>true,
        'get'=>array('Api','getMaxDataByDay')
    ],
    "/api/getWorstComments"=>[
        'path'=>'/api/getWorstComments',
        'allow'=>true,
        'get'=>array('Api','getWorstComments')
    ],
    "/api/getBestComments"=>[
        'path'=>'/api/getBestComments',
        'allow'=>true,
        'get'=>array('Api','getBestComments')
    ],
    "/csv"=>[
        'path'=>'/csv',
        'allow'=>true,
        'get'=>array('Plain','home')
    ],  
    "/"=>[
        'path'=>'/',
        'allow'=>true,
        'get'=>array('Dashboard','home')
    ],
];
foreach ($ROUTES as $key => $route) {
    $router->addRoute($route);
}