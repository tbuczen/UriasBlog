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
        $normalizedRouteArray = $this->normalizeRoute($route);
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
        $page = $page ?? 0;
        $posts = $this->getArticles($page);
        $max = $this->db->count("post");
        $this->vc->assign('posts',$posts);
        $this->vc->assign('paginationMaxPage',$max);
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

    private function getArticles($page)
    {
        $formattedPosts = [];
        $limit = $page*POST_PER_PAGE . "," . POST_PER_PAGE;
        $posts = $this->db->fetch("post",[],"*",$limit);

        foreach ($posts as $key => $post){
            $formattedPosts[$key] = $post;
            $thumbnail = $post["thumbnail"];
            if(empty($thumbnail)){
                //get first media associated with this post
                $t = $this->db->fetch("media",["post_id" => $post["id"]],"*",1);
                $thumbnail = $t["filename"] . "." . $t["extension"];
            }
            $date = DateTime::createFromFormat('Y-m-d H:i:s', $post["date"]);
            $dateFolder = $date->format('Y-m-d');
            $imgPath = "uploads" .DS. $dateFolder .DS. $this->urlize($post["title"],"_") .DS. $thumbnail;
            $formattedPosts[$key]["thumbnail"] = $imgPath;
            $formattedPosts[$key]["date"] = $dateFolder;
        }

        return $formattedPosts;
    }
}