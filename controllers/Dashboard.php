<?php

class Dashboard extends Tornado\Controller {

    public function home($req,$res)  {

    	echo $this->renderView($res);
    }

    public function comments($req,$res)  {

        echo $this->renderView($res);
    }
}