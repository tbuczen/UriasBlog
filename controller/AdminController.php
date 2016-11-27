<?php

//TODO :: user passwd change
/**
 * Class AdminController Manages admin pages & user logic
 */
class AdminController extends BaseController
{


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
                $_SESSION["user"] = array("username" => $user["username"], "nickname" => $user["nickname"], "email" => $user["email"], "id" => $user["id"]);
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

    public function adminAction(){
        $this->checkPermission();
        $posts = $this->db->fetch("post");
        $this->vc->assign('posts',$posts);
        $this->vc->renderAll("posts","admin");
    }

    public function userListAction(){
        $this->checkPermission();
        $users = $this->db->fetch("user");
        $this->vc->assign('users',$users);
        $this->vc->renderAll("users","admin");
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
}