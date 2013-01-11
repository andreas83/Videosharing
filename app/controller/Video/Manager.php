<?php
class Video_Manager
{
    function __construct()
    {
       $this->Template["index"] = "index.php";
       
       $this->Template["muh"] = "index.php";
    }
    function index(){
        $this->var = "Here iam";
    }
    function muh(){
        echo "bÖSE";
        $this->var="gut";
    }
}
?>