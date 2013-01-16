<?php
class Video_Manager
{
    function __construct()
    {

        $this->Template["index"] = "index.php";
        $this->Template["upload"] = "upload.php";
    }


    function index()
    {

        $this->title = "Overview";
        $this->sub_headline = "Overview";
    }


    /**
     * This Controller is responsible for
     * the Upload Form handling
     */
    function upload()
    {

        $this->Title = "Upload";
//        uploadify just works with old jquery versions...
//        $this->JS = Helper::jsScript("jquery.uploadify.min");
//        $this->JS .= Helper::jsScript("upload.js");
//        $this->CSS = Helper::cssScript("uploadify.css");
        
        $this->JS .= Helper::jsScript("flowplayer.min.js");
        $this->JS .= Helper::jsScript("video.js");
        $this->CSS = Helper::cssScript("minimalist.css");
        
        
        if ( isset($_FILES) && isset($_POST) && !empty($_FILES) )
        {
            // we need a better mime type checks i guess
            
            // $allowedFileExtension = array("avi", "mov", "mpeg", "flv");
            
            $tmpVideoName = md5(microtime());
            move_uploaded_file($_FILES['file_upload']['tmp_name'], Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg = new FFmpeg(Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg->getFileInformation();
            $ffmpeg->createThumbnail();
            //$ffmpeg->convertVideo("webm");
            //$ffmpeg->convertVideo("mp4");
            $ffmpeg->convertVideo("mp4");
            $this->thumb = Config::get('address') . "/public/upload/" . $tmpVideoName;
        }
    }
}
?>