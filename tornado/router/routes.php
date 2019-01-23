<?php
// EJEMPLO
//    "/path"=>[
//      'path'=>'path',
//      'get'=>array('Controller','method'),
//      'post'=>array('Controller','method'),
//    ]  
global $ROUTES;
$ROUTES=[
    "/home"=>[
        'path'=>'/',
        'allow'=>true,
        'get'=>array('Plain','home')
    ]  
];
foreach ($ROUTES as $key => $route) {
    $router->addRoute($route);
}