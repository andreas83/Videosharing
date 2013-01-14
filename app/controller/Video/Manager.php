<?php
class Video_Manager
{
    function __construct()
    {
       $this->Template["index"] = "index.php";
       $this->Template["Upload"] = "upload.php";
    }
    function index(){
        $this->var = "Here iam";
    }
    function Upload(){
        
        $this->Title = "Upload";
        //we need some more upload stuff
        $this->JS = Helper::jsScript("jquery.uploadify.min");
        $this->JS.= Helper::jsScript("upload.js");
        $this->CSS= Helper::cssScript("uploadify.css");
        
        
    }
}
?>