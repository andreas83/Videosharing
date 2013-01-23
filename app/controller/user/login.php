<?php
class User_Login {

    function __construct() {
        $this->Template["index"] = "index.php";
        
        
    }

    function index() {
         $user = new User;
         
         if(isset($_POST) && !empty($_POST))
         {
            if($user->check_login($_POST['user'], $_POST['password']))
            {
                $_SESSION['user']  = $user->username;
                $_SESSION['user_id']= $user->id;
                header("Location: ".Config::get('address')."/user/video");

            }else
            {

                $this->error=_("Sorry, please check your Credentials");
            }
         
         }
    }
    
}

?>
