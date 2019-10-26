<?php
namespace Tornado;

/**
 * Esta clase hace funcionar a mustache
 * @author geoskull
 */
class Mustache extends \Zaphpa\BaseMiddleware {
    /**
     * Metodo que se ejecuta antes de que se haga el ruteo
     * @param type $req
     * @param type $res
     */
    public function preroute(&$req, &$res) {
        $mustache=new \Mustache_Engine(array(
            'template_class_prefix' => '__MyTemplates_',
            //'cache' => __TOR__.'/tmp/cache/mustache',
            'loader' => new \Mustache_Loader_FilesystemLoader(__TOR__.'/views/'),
            'partials_loader' => new \Mustache_Loader_FilesystemLoader(__TOR__.'/views/partials'),
            'strict_callables'=>true,
            'charset'=>'UTF-8',
            'pragmas'=>[\Mustache_Engine::PRAGMA_FILTERS, \Mustache_Engine::PRAGMA_BLOCKS]
        ));
        $mustache->addHelper('date', [
            'format'=> function ($value){return strtolower((string)date("d - F - Y",$value));},
            'myformato'=> function ($value){return strtolower((string)date("d - F - Y, H:i:s",$value));},
            'fecha_hora'=> function ($value){return strtolower((string)date("d - F - Y, g:i a",$value));},
        ]);    
        $res->mustache=$mustache;
        if(is_file(__TOR__."/views/". implode("/", self::$context["callback"]).".mustache")){
            $res->m=$res->mustache->loadTemplate(implode("/", self::$context["callback"]));
        }
    }
    /**
     * Metodo que se ejecuta antes de que se haga el render
     * @param type $buffer
     */
    public function prerender(&$buffer) {
    }
}
