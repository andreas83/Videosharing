<?php
class Video_Manager
{
    function __construct()
    {
       $this->Template["index"] = "index.php";
       $this->Template["Upload"] = "upload.php";
    }
    
    function index()
    {
        $this->var = "Here iam";
    }
    
    /**
     * This Controller is responsible for
     * the Upload Form handling 
     */
    function Upload()
    {
        
        $this->Title = "Upload";
        //we need some more upload stuff
        $this->JS = Helper::jsScript("jquery.uploadify.min");
        $this->JS.= Helper::jsScript("upload.js");
        $this->CSS= Helper::cssScript("uploadify.css");
        
        
        if( isset($_FILES) && isset($_POST) && !empty($_FILES))
        {
            //we need a better mime type checks i guess
            
            //$allowedFileExtension = array("avi", "mov", "mpeg", "flv");
            
            
            $tmpVideoName = md5(microtime());
            move_uploaded_file($_FILES['file_upload']['tmp_name'], Config::get('basedir')."/public/upload/".$tmpVideoName);
            $ffmpeg = new FFmpeg(Config::get('basedir')."/public/upload/".$tmpVideoName);
            $ffmpeg->getFileInformation();
            $ffmpeg->createThumbnail();
            //$ffmpeg->convertVideo("mp4");
            $ffmpeg->convertVideo("webm");
            
            $this->thumb=Config::get('address')."/public/upload/".$tmpVideoName;
        }
        
    }
}
?>