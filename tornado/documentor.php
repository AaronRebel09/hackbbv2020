<?php

namespace Tornado;

/**
 * Please note that ZaphpaAutoDocumentation is instantiated twice
 * once as a BaseMiddleware, another time: as callback
 */
class AutoDocumentator extends \Zaphpa\Middleware\AutoDocumentator {
 private $path = '/docs';
 private static $details = false;

function __construct($path = '/docs', $details = false) {
    $this->path = $path;

    // Don't overwrite a true value if we've got one
    self::$details = (self::$details || $details);
  }

 public function preprocess(&$router) {
    $router->addRoute(array(
      'path' => $this->path,
      'get'  => array('\Tornado\AutoDocumentator', 'generateDocs'),
    ));
  }
  /**
   * Esta documentacion
   */
  public function generateDocs($req, $res) {
    $res->setFormat('html');

    $res->add("<h1 class='text-centered'>LUNA - API Documentation</h1>");

    $style = '
    <link rel="stylesheet" type="text/css" href="/assets/css/kube.min.css">
    <style>
    body{
        padding:20px;
        text-align:center;
    }
    h1{
        color:#222;
    }
    h2 a, h2 a:hover{
        color:#6F9CC8;
        display:block;
        text-decoration:none;
        border-bottom:1px solid #6F9CC8;
        padding:10px 5px;
    }

    ul{
        width:80%;
        margin:auto;
        text-align:left
    }
    li{
        margin-bottom:10px;
        list-style:none;

    }
    b.tag{
        color:#BD54C8;
    }
    
    pre{
        background-color:#f4f4f4;
        color:#222;
        line-height:10px;
        padding:0px 20px;
        font-size:10px;
    }
    i{
        font-size:15px;
        color:#999;
    }
    p{
        background-color:#fafafa;
        border-left:10px solid #6F9CC8;
        padding:10px;
    }
    </style>
    ';

    $res->add($style);

    if (!self::$details) {
      $pattern = "<li>
          <h2>%i</h2>
          <p class='small'>%d</p>
         </li>";
    } else {
      $pattern = "<li>
      <h2><a href='%I'>%i</a></h2>
      <p class='small'>%d</p>
      <i> <b>File:</b> %f, <b>Class:</b> %c, <b>Method:</b> %m</i>
      </li>";
    }

    $res->add("<ul class='docs'>\n");

    $sorted_routes = self::$routes;
    ksort($sorted_routes);

    foreach ($sorted_routes as $method => $mroutes) {
      ksort($mroutes);
      foreach ($mroutes as $id => $route) {
        if(class_exists( $route['callback'][0] ) ){

          $reflector = new \ReflectionClass($route['callback'][0]);
          $classFilename = $route['file'];
          if (empty($classFilename)) {
            $classFilename = basename($reflector->getFileName());
          }

          $callbackMethod = $reflector->getMethod($route['callback'][1]);
          $methodComments = trim(substr($callbackMethod->getDocComment(), 3, -2));

          // remove the first *
          $methodComments = preg_replace('/\*/', '', $methodComments, 1);

          $methodComments = preg_replace('/(@code+)/', '<pre> ', $methodComments);
          $methodComments = preg_replace('/(@endcode+)/', '</pre> ', $methodComments);
          $methodComments = preg_replace('/(@\w+)/', '<b class="tag">$1</b> ', $methodComments);

          
          

          // replace all the other *'s with line breaks
          $methodComments = preg_replace('/\*/', '<br />', $methodComments);

          $data = array(
            '%i' => strtoupper($method) . ' ' . $id,
            '%I' => $id,
            '%f' => "<a href='subl://open/?url=file://{$reflector->getFileName()}'>".$classFilename."</a>",
            '%d' => $methodComments,
            '%c' => $route['callback'][0],
            '%m' => $route['callback'][1]
          );

          if (strpos($methodComments, '@hidden') === false) {
            $res->add( strtr($pattern, $data) );
          }
          
        }
      }
    }

    $res->add("</ul>");
    $res->send(200);
  }
}
