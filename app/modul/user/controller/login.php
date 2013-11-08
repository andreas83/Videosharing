<?php
class User_Login {

    function __construct() {
        $this->Template["index"] = "index.php";
        $this->Template["register"] = "index.php";
        $this->Title="Authenticate";
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

                $this->login_error=_("Sorry, please check your Credentials");
            }
         
         }
    }
    
      function register() {
        
        if(isset($_POST)  && !empty($_POST)){
            $this->error=false;
            if($_POST['password1'] != $_POST['password2'] || strlen($_POST['password1'] )<=1)
            {
                $this->error_type["pass"]=_("Password did not match");
                $this->error=true;
            }
            if(strlen($_POST['user'] )<=3)
            {
                $this->error_type["user"]=_("Username must be more than 2 character");
                $this->error=true;                
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ 
                $this->error_type["mail"] =_("eMail is not Valid");
                $this->error=true;
            }
            
            if($this->error!=true){
                $user = new User();
                $return = $user->get_list(array("username" => $_POST['user']));
                if(count($return)>0)
                {
                    $this->error_type["username"] =_("Username allready exist");
                    $this->error=true;    
                }
            }
            
            if ($this->error!=true)
            {
                $user = new User();
                $user->username = $_POST['user'];
                $user->password = md5($_POST['password1']);
                $user->email = $_POST['email'];
                $user->save();
                $_SESSION['user']  = $user->username;
                $_SESSION['user_id']= $user->id;
                header("Location: ".Config::get('address')."/user/video");
                     
                
            }
        }
    }
    
}

?>
