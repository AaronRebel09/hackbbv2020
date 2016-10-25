<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tornado;

/**
 * Description of SessionLogin
 *
 * @author geoskull
 */
class SessionLogin extends \Zaphpa\BaseMiddleware{
    private $urlPublicas=['','/','/login','/forgot'];
    private $urlAdmin=[];
    /**
     * Metodo de preroute, se ejecuta en cada peticion antes de render
     * @return boolean
     */
    public function preroute(&$req, &$res) {
        //path success
        $redirect_after_login="/admin";
        //cargar paths
        $this->cargarPaths();
        //mandamos a llamar metodo login
        //$this->procesoLogin($redirect_after_login, $req, $res);
        return true;
    }
    public function prerender(&$buffer) {
    }
    /**
     * Metodo de autenticacion y control de acceso
     * Verifica si para cada ruta se necesita loguear, verifica si esta logueado
     * o si no
     * @param type $path
     * @param type $req
     * @param type $res
     */
    private function procesoLogin($path,$req,$res){
        global $spot;//se obtiene spot
        $usuarioMapper=$spot->mapper("Entity\Usuarios");//se obtiene dao de tabla
        global $session_handle;//se obtiene handeler de sesiones
        $session=$session_handle->getSegment("Tornado\Session");//se obtiene la sesion
        //si la uri esta en las publicas lo dejamos de otra manera vemos sesion y login
        if(!in_array(self::$context[pattern], $this->urlPublicas)){
            //si el usuario no esta logueado lo mandamos a loguear
            if(!$session->get("user",false)){
                header("Location: http://".$_SERVER["SERVER_NAME"].$path."/login?redirect=".$path.self::$context["request_uri"]);
                die();
            }
            else{
                //se obtiene la sesion
                $req->user=$session->get("user");
                //se obtiene el id del rol
                $rol=$session->get("user")["rol"];
                //se obtiene el status del usuario
                $status=$session->get("user")["status"];
                if(status){
                    if($rol==1&&in_array(self::$context["pattern"], $this->urlAdmin)){
                        $session->set("user", $req->user);
                    }
                    else{
                        switch ($rol){
                            case 1:
                                $aux="/admin";
                            break;
                        }
                        $session->set("user",$req->user);
                        header('Location:http://'.$_SERVER["SERVER_NAME"].$aux);
                    }
                }
                else{
                    $session_handle->destroy();
                    header("Location:/");
                }
            }
        }
        //si el pattern es login
        if(self::$context["request_uri"]=="/login"){
            if ($session->get("user", false)) {
                $rol=$session->get("user")["rol_id"];
                $aux="";
                switch ($rol){
                    case 1:
                        $aux="/admin";
                    break;
                }
                header('Location:'.$aux);
            }
            if(isset($req->data["user"],$req->data["pass"])){
                $username=$req->data["user"];
                $password=$req->data["pass"];
                $user=$usuarioMapper->where(["usuario"=>$username])->first();
                if($user){
                    if($user->password=md5($password)&&$user->status===true){
                        $user=$user->toArray();
                        $session->set("user",$user);
                        if(isset($req->data["redirect"])){
                            header("Location: http://" . $_SERVER["SERVER_NAME"] . $req->data["redirect"]);
                        }
                        else{
                            $aux="";
                            switch ($user["rol_id"]){
                                case 1:
                                    $aux="/admin";
                                break;
                            }
                            header("Location:".$aux);
                        }
                    }else{
                        $session_controller = $session_handle->getSegment('Tornado\Controllers');
                        $session_controller->setFlash("alert", ["message" => "El password y el usuario no coinciden!", "status" => "Error:", "class" => "alert-danger"]);
                    }
                }else{
                    $session_controller = $session_handle->getSegment('Tornado\Controllers');
                        $session_controller->setFlash("alert", ["message" => "El password y el usuario no coinciden!", "status" => "Error:", "class" => "alert-danger"]);
                }
            }
        }
        //si es logout
        if (self::$context["request_uri"] == '/logout') {
            $session_handle->destroy();
            header('Location:/');
            exit;
        }
    }
    /**
     * Metodo cargar Paths
     * Se obtiene el patron de contexto en las rutas globales y añade la ruta a su correspondiente atributo de esta clase dependiendo de sus propiedades
     * allow = indica si se tiene acceso sin estar logueado
     * rol= admin acceso solo administrador, agente acceso solo agente, cliente acceso solo cliente, se requiere estar logueado
     * @global type $ROUTES
     */
    private function cargarPaths(){
        global $ROUTES;
        foreach ($ROUTES as $key => $route){
            $route["allow"]=isset($route["allow"])?$route["allow"]:false;
            if($route["allow"]==true){
                array_push($this->urlPublicas, self::$context["pattern"]);
            }
            $route["rol"]=isset($route["rol"])?$route["rol"]:"";
            switch ($route["rol"]){
                case 'admin':
                    array_push($this->urlAdmin, self::$context["pattern"]);
                break;
            }
        }
    }
}
