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
    public function preroute(&$req, &$res) {
        global $ROUTES;
        $redirect_after_login="/dashboard";
        global $spot;
        global $session_handle;
        $session=$session_handle->getSegment("Tornado\Session");
        global $BASE;
        return true;
    }
    public function prerender(&$buffer) {
    }
}
