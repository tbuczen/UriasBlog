<?php

/**
 * Created by PhpStorm.
 * User: urias
 * Date: 24.08.16
 * Time: 16:07
 */
class SessionController
{

    public function getLayout(){
        if(!isset($_SESSION["layout"])){
            $_SESSION["layout"] = "list";
        }
        return $_SESSION["layout"];
    }

    public function setLayout($string)
    {
        $_SESSION["layout"] = $string;
    }
}