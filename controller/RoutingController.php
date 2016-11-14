<?php

class RoutingController extends BaseController
{
    /**
     * It acts like routing in sf instead of Calling Bundle:Controller:action we get our function by returning array
     * @param $routeArr
     * @return array (pageAction -> function name; controller -> controller name; params )
     */
    public function routeToPage($routeArr)
    {
        if($routeArr[0] == "action"){
            array_shift($routeArr);
            return $this->takeToAction($routeArr);
        }

        $controller = "front";
        $params = [];
        switch (reset($routeArr)) {
            case null: case "" :
                $action = "main";
                break;
            case "admin" :
                $controller = "admin";
                $action = "admin";
                break;
            case "login" :
                $controller = "admin";
                $action = "login";
                break;
            case "logout" :
                $controller = "admin";
                $action = "logout";
                break;
            case "tags" :
                $action = "tags";
                break;
            case "about" :
                $action = "about";
                break;
            case "contact" :
                $action = "contact";
                break;
            case "legal_disclaimer" :
                $action = "legalDisclaimer";
                break;
            case "p" : case "page" :
                if(count($routeArr) == 2 && is_numeric($routeArr[1])){
                    $action = "main";
                    $params["page"] = $routeArr[1];
                }else{
                    $action = "error404";
                }
                break;
            case "info" : case "post" :
                if(count($routeArr) == 3 && self::validateDate($routeArr[1])){
                    $action = "postDetails";
                    $params["date"] = $routeArr[1];
                    $params["title"] = $routeArr[2];
                }else{
                    $action = "error404";
                }
                break;
            default :
                $action = "error404";
        }

        if(is_numeric(reset($routeArr))){
            $action = "main"; // + page
            $params["page"] = reset($routeArr);
        }

        return array("controller" => $controller ,"action" => $action , "params" => $params);
    }

    /**
     * @param $actionArr array
     * @return array (controller, action, params)
     */
    public function takeToAction($actionArr)
    {
        $controller = "front";
        $params = [];
        var_dump($actionArr);
        switch (reset($actionArr)) {
            case "changeView" :
                $action = "changeView";
                break;
            case "logout" :
                $controller = "admin";
                $action = "logout";
                break;
            default:
                $action = "error404";
        }
        return array("controller" => $controller ,"action" => $action , "params" => $params);
    }

    public function redirect($path = "")
    {
        header("Location: /$path");
    }

}