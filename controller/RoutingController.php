<?php

class RoutingController extends BaseController
{
    /**
     * It acts like routing in sf instead of Calling Bundle:Controller:action we get our function by returning array
     * @param $route
     * @return array (pageAction -> function name; controller -> controller name; params )
     */
    public function routeToPage($route)
    {
        if($route != "") {
            if ($route[0] == "action") {
                array_shift($route);
                return $this->takeToAction($route);
            }
        }
        global $ROUTING;

        $path = null;
        $params = [];

        if(array_key_exists($route,$ROUTING))
            $path = $ROUTING[$route];

        $route = strtok($route,'?');

        $matchScore = [];
        if(empty($path)) {
            //match route
            $routeElements = explode("/", $route);
            foreach ($ROUTING as $possibleRoute => $action) {
                $possibleRouteElements = explode("/", $possibleRoute);
                if (count($routeElements) == count($possibleRouteElements)) {
                    foreach ($possibleRouteElements as $key => $possibleElem) {
                        //if not a parameter and route length matches
                        if(!empty($possibleElem)) {
                            if ($possibleElem[0] !== "@" && $possibleElem == $routeElements[$key]) {
                                if(!array_key_exists($possibleRoute,$matchScore))
                                    $matchScore[$possibleRoute] = 0;
                                $matchScore[$possibleRoute] += 1;
                            }else if($possibleElem[0] !== "@" && $possibleElem !== $routeElements[$key]){
                                unset($matchScore[$possibleRoute]); //if route doesn match - break iterating over that path and its elements
                                break 1;
                            }
                        }
                    }
                }
            }
            //extract params
            if(!empty($matchScore)) {
                $matched = array_search(max($matchScore), $matchScore);
                $matchedElements = explode("/", $matched);
                foreach ($matchedElements as $key => $elem) {
                    if (!empty($elem)) {
                        if ($elem[0] == "@") {
                            $paramName = substr($elem, 1);
                            $params[$paramName] = $routeElements[$key];
                        }
                    }
                }
                if ($matched) $path = $ROUTING[$matched];
            }
        }

        if(is_null($path)){
            $path = "Front:error404";
        }

        $components = explode(":",$path);
        $controller = $components[0];
        $action = $components[1];
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
//        var_dump($actionArr);
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