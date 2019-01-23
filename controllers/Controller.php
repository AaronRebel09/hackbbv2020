<?php
namespace Tornado;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Controlador base para controladores
 *
 * @author geoskull
 */
class Controller {
    public $spot;
    public $session_handle;
    public $session;
    public $entity;
    public $mail;
    public $bread;
    /**
     * Constructor de la clase
     * @global type $session_handle
     * @global type $spot
     * @global type $mail
     */
    public function __construct() {
        //se obtienen las sesiones
        global $session_handle;
        $this->session_handle=$session_handle;
        $this->session= $this->session_handle->getSegment("Tornado\Controllers");
        //se obtiene el modelo
        $this->entity = class_exists("\\Entity\\" . get_class($this)) ? ("\\Entity\\" . get_class($this)) : "";
        //se obtiene instancia de spot
        global $spot;
        $this->spot = $spot;
        $this->mapper = ($this->entity != "") ? $this->spot->mapper($this->entity) : NULL;
        //se crea loger
        $this->log = new Logger('tornado');
        $this->log->pushHandler(new StreamHandler(__DIR__ . '/logs/tornado.log', Logger::WARNING));
        //se obtiene datos de correo 
        global $mail;
        $this->mail = $mail;
    }
    /**
     * Hace el render, de la plantilla mustache
     * @param type $res
     * @param type $data
     * @return type
     */
    public function renderView($res, $data =[]) {
        $session=$this->session_handle->getSegment('Tornado\Session');
        $data= array_merge(["user"=>$session->get("user")],$data);
        $alert= $this->session->getFlash("alert");
        if($alert){
            $data=array_merge(["alert"=>$alert],$data);
        }
        return $res->m->render($data);
    }
    /**
     * Mejora la visualizacion del print_r
     * @param type $mixed
     */
    public static function pr($mixed) {
        echo '<pre>';
        print_r($mixed);
    }
    /**
     * Funcion que sirve para enviar correos
     * @param type $res
     * @param type $data
     * @param type $template
     */
    public function mailer($res,$data,$template) {
        $this->mail->AddAddress($data->data["emailto"]);
        $this->mail->Subject = $data->data["subject"];
        $mail = $res->mustache->loadTemplate($template);
        $this->mail->Body = $mail->render($data);
        if (!$this->mail->send()) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        }
    }
}
/**
 * Carga dinamica de todos los controladores
 */
foreach (scandir(__DIR__) as $class) {
    $buffer = explode(".", $class);
    if (end($buffer) == "php") {
        require_once( __DIR__ . '/' . $class );
    }
}