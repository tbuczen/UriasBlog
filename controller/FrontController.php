<?php

class FrontController extends BaseController
{

    /**
     * @var RoutingController
     */
    private $routing;
    /**
     * @var SessionController
     */
    private $sc;

    public function __construct(){
        parent::__construct();
        $this->routing = new RoutingController();
        $this->sc = new SessionController();
        $this->vc->assign('layout',$this->sc->getLayout());
    }

    /**
     * Parse given url ad match it with routing
     */
    function handleRequest(){
        $route = ltrim($_SERVER["REQUEST_URI"],"/");
        $normalizedRouteArray = explode("/",$this->normalizeRoute($route));
        $data = $this->routing->routeToPage($normalizedRouteArray);
        $this->vc->assign("requestData",$data);
        $this->callActionOrRenderView($data);
    }

//    PAGES
    /**
     * @param null $params - @see RoutingController [page]
     */
    public function mainAction($params = null){
        extract($params);
        //check search
        if(isset($page) && $page !== null){
            //load pagination
        }else{
            $posts = array(
                array("title" => "Article1",
                    "tags" => "#nature,#alberta,#rockymountains,#wildlife,#rawsonLake",
                    "date" => "2016-08-20",
                    "img_main" => "img1.jpg",
                    "img_gallery" => array(),
                    "description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate. Voluptatum ducimus voluptates voluptas?"
                ),
                array("title" => "Takie tam",
                    "tags" => "#nature,#alberta,#rockymountains,#wildlife,#rawsonLake",
                    "date" => "2016-08-22",
                    "img_main" => "img1.jpg",
                    "img_gallery" => array(),
                    "description" => "Morbi nibh. Maecenas tortor quis suscipit mauris. Pellentesque habitant morbi tristique interdum. Donec pulvinar interdum, lacus. Vestibulum massa vel nulla. Phasellus adipiscing. Nunc vehicula. Nunc elementum. Morbi accumsan at, suscipit wisi. "
                ),
                array("title" => "Test",
                    "tags" => "#nature,#alberta,#rockymountains,#wildlife,#rawsonLake",
                    "date" => "2016-08-22",
                    "img_main" => "img1.jpg",
                    "img_gallery" => array(),
                    "description" => "Praesent scelerisque condimentum ante eget nulla. Phasellus ac ipsum. Fusce ullamcorper varius risus vehicula convallis tellus. Vestibulum consectetuer sagittis luctus mauris sit amet, consectetuer vulputate tempor interdum dui nulla, vitae est."
                )
            );
        }
        $this->vc->assign('posts',$posts);
        $this->vc->assign('title',"Main");
        $this->vc->renderAll("main");
    }

//    PAGES
    /**
     * @param $params @see RoutingController [date,title]
     */
    public function postDetailsAction($params){
        $this->vc->assign('title',"Details");
        extract($params);
        $post = array(
            "id" => 1232,
            "title" => "Article1",
            "date" => "2016-08-20",
            "img_main" => "img1.jpg",
            "img_gallery" => array(),
            "tags" => "#nature,#alberta,#rockymountains,#wildlife,#rawsonLake",
            "description" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eligendi non quis exercitationem culpa nesciunt nihil aut nostrum explicabo reprehenderit optio amet ab temporibus asperiores quasi cupiditate. Voluptatum ducimus voluptates voluptas?"
        );
        $this->vc->assign('post',$post);
        $this->vc->renderAll("post");
    }

    public function tagsAction(){
        $this->vc->renderAll("tags");
    }

    public function error404Action($params = null){
        $this->vc->renderAll("404");
    }

//    ACTIONS
    public function changeViewAction(){
        if($this->sc->getLayout() == "list"){
            $this->sc->setLayout("grid");
        }else{
            $this->sc->setLayout("list");
        }
        $this->routing->redirect();
    }
}