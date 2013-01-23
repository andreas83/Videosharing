<?php

class User_Register {

    function __construct() {
        $this->Template["index"] = "index.php";
   
        $this->Title = _("Register");
    }

    function index() {
        
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
                $this->success = _("Please check your mail for activation link");
                     
                
            }
        }
    }
    
}


?>
