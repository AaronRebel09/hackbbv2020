<?php
namespace Tornado;
/**
 * Cross-origin resource sharing (CORS) is a mechanism that allows restricted 
 * resources (e.g. fonts) on a web page to be requested from another domain 
 * outside the domain from which the resource originated.
 * 
 * Uso:
 * $router->attach('\Tornado\Cors','*')->restrict('GET','/post/{id}');
 * 
 * Para mas informacion leer http://zaphpa.org/doc.html
 * @author geoskull
 */
class CORS extends \Zaphpa\BaseMiddleware{
    private $domain;//atributo de la clase
    /**
     * Constructor de la clase, inicializa el dominio
     * @param type $domain
     */
    public function __construct($domain='*') {
        $this->domain=$domain;
    }
    /**
     * Añade los distintos metodos aceptados y las distintas cabeceras
     * @param type $req
     * @param type $res
     */
    public function preroute(&$req, &$res) {
        $allowedMethods= self::$context['http_method'];//se obtienen los metodos        
        $res->addHeader("Access-Control-Allow-Origin",$this->domain);//se añade cabecera para dominio
        $headers=array(
            "Access-Control-Allow-Methods"=>$allowedMethods,
            "Access-Control-Allow-Headers"=>array(
                "origin","accept","content-type","authorization",
                "x-http-method-override","x-pingother","x-requested-with",
                "if-match","if-modified-since","if-none-match","if-unmodified-since"
            ),
            "Access-Control-Expose-Headers"=>array(
                "tag","link",
                "X-RateLimit-Limit","X-RateLimit-Remaining","X-RateLimit-Reset",
                "X-OAuth-Scopes","X-Accepted-OAuth-Scopes"
            )
        );
        /**
         * Añade dichas cabeceras a la respuesta
         */
        foreach ($headers as $key => $vals) {
            $res->addHeader($key,$vals);
        }
    }
}
