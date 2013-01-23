<?php
class User_Logout {

    function __construct() {
        $this->Template["index"] = "index.php";
        
        
    }

    function index() {
        session_destroy();
        header("Location: ".Config::get('address'));
    }
    
}

?>
