<?php

/**
 * Class AdminController Manages admin pages & user logic
 */
class AdminController extends BaseController
{

    /**
     * @return bool
     */
    private function isLoggedIn(){
        return (isset($_SESSION["user"]))? true:false;
    }

    public function createUser($user,$password,$nickname,$email=""){
        $options = ['cost' => 12];
        $passwordHashed = password_hash("$password", PASSWORD_BCRYPT, $options);
        $data = array("username" => $user, "password" => $passwordHashed, "nickname" => $nickname, "email" => $email);
        $this->db->insert("user",$data);
    }

    /**
     * @return array
     */
    private function login(){
        $error = null;
        $success = false;
        if(isset($_POST["username"]) && isset($_POST["password"])){
            $user = $this->db->findUser(array("username" => $_POST["username"], "password" => $_POST["password"] ));
            if($user){
                $_SESSION["user"] = array("username" => $user["username"], "nickname" => $user["nickname"], "email" => $user["email"]);
                $success = true;
            }else{
                $error = "Given credentials are invalid";
            }
        }else{
            $error = "Both fields required.";
        }
        return array("success" => $success, "error" => $error );
    }

    private function logout(){
        unset($_SESSION["user"]);
    }

    public function newPostAction(){
        $size = ini_get('post_max_size');
        $sizeBytes = $this->return_bytes($size);
        if(isset($_POST["submit"])){
            var_dump($_POST);
            if(isset($_FILES["images"])) {
                $sentSize = array_sum($_FILES["images"]["size"]);
                if($sentSize <= $sizeBytes){
                    var_dump($_FILES);
                }else{
                    //error - too big data
                    $this->vc->assign('error',$size);
                }
            }
        }

        $this->vc->assign('maxSize',$size);
        $this->vc->assign('maxSizeBytes',$sizeBytes);
        $this->vc->assign('maxCount',ini_get('max_file_uploads'));
        $this->vc->renderAll("newPost","admin");
    }

    public function adminAction(){
        if(!$this->isLoggedIn()){
            $this->loginAction();
            return false;
        }
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

        $this->vc->assign('posts',$posts);
        $this->vc->renderAll("posts","admin");
    }

    public function userAction(){

    }

    public function loginAction(){
        if($this->isLoggedIn()){
            $this->redirect("admin");
        }
        if(isset($_POST["login"])){
            $loginData = $this->login();
            if($loginData["success"]){
                $this->redirect("admin");
            }else{
                $this->vc->assign('error',$loginData["error"]);
                $this->vc->renderOne("login","admin");
            }
        }else{
            $this->vc->renderOne("login","admin");
        }
    }

    public function logoutAction(){
        if($this->isLoggedIn()){
            $this->logout();
            $this->redirect("login");
        }
    }

    protected function return_bytes($val) {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

}