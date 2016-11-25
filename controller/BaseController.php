<?php

/**
 * Class BaseController - contains base functions of route handle and base for page controllers
 */
class BaseController
{
    /**
     * @var ViewController
     */
    protected $vc;

    /**
     * @var Db
     */
    protected $db;

    public function __construct()
    {
        $this->db = new Db();
        $this->vc = new ViewController();
        $this->vc->assign('title',SITE_NAME);
    }

    /**
     * @param $date
     * @return bool
     */
    static function validateDate($date){
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    /**
     * @param $route
     * @return string
     */
    function normalizeRoute($route){
        if(!isset($route) || $route ==null){$route = "/";}
        return (strcmp(substr($route,-1),"/") == 0)? rtrim($route,"/"):$route;
    }

    /**
     * Check if we can call action specified in routing - if not - try to render view name based on action name
     * If it fails too - show 404
     * @param $data
     * @throws Exception
     */
    public function callActionOrRenderView($data){
        //Get controller from data and create its instance
        $controllerName = ucfirst($data["controller"]) . "Controller";
        $controller = new $controllerName;
        //Get function parameters
        $params = $data["params"];
        //get action (page render method) from routing
        $actionName = $data["action"]. "Action";

        //try to run Action
        if(is_callable(array($controller, $actionName))){
            $controller->$actionName($params);
        }else{
            //try to render view named after action
            try{
                $this->vc->renderAll($data["action"]);
            }catch(\Exception $e){
                //no action nor view
                $this->vc->renderAll("404");
                throw new Exception("No action nor view found for : $controller::$actionName.");
            }
        }
    }

    public function redirect($path){
        header('Location: /'.$path);
    }

    /**
     * @param $data
     */
    public function dump(...$data){
        echo "<pre>";
        foreach ($data as $d ){
            var_dump($d);
        }
        echo "</pre>";
    }
}