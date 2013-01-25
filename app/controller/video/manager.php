<?php
class Video_Manager
{
    function __construct()
    {

        $this->Template["index"] = "index.php";
        $this->Template["upload"] = "upload.php";
        $this->Template["checkStatus"] = "json.php";
        $this->Template["uploadProgress"] = "json.php";
        $this->Template["save"] = "upload.php";
        $this->Template["edit"] = "upload.php";
        $this->Template["update"] = "upload.php";
        $this->showUpload=true;
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

        
        
        
        
        if ( isset($_FILES) && isset($_POST) && !empty($_FILES) )
        {
            $this->JS .= Helper::jsScript("upload.js");

            // we need a better mime type checks i guess
            
            // $allowedFileExtension = array("avi", "mov", "mpeg", "flv");
           
            $tmpVideoName = md5(microtime());
            
            move_uploaded_file($_FILES['file_upload']['tmp_name'], Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            
            $ffmpeg = new FFmpeg(Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg->getFileInformation();
            $ffmpeg->createThumbnail();

            $this->showUpload=false;

            $this->thumbnails = array(Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs1/00000001.png", 
                                      Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs2/00000001.png",
                                      Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs3/00000001.png");
            $this->filename = $tmpVideoName;
            $this->showAlert = true;
            $this->success=_("Please enter the Title and Description of your Video");
        }
    }
    
    
 
 
    
    
    function delete(){
        $video=new Video($_GET['id']);
        $video->user_id = $_SESSION['user_id'];
        $video->del_save();
        
        header("Location: ".Config::get('address')."/user/video");
    }
    
    /**
     * 
     */
    function save()
    {
        
        if(isset($_POST)  && !empty($_POST)){
            $error=false;
            if(!$_POST['title'] || empty($_POST['title']))
            {
                $error=true;
            }
            if(!$_POST['description'] || empty($_POST['description']))
            {
                $error=true;
            }   
            if(!$_POST['filename'] || empty($_POST['filename']))
            {
                $error=true;
            }
            
            if($error==false)
            {
                
                $video = new Video();
                $video->title = $_POST['title'];
                $video->descripton = $_POST['description'];
                $video->user_id = $_SESSION['user_id'];
                $video->isConverted = 0;
                $video->filename =  $_POST['filename'] ;
                $video->thumb = $_POST['thumb'];
                $video->visibility_setting= $_POST['visibility'];
                $video->save();
                header("Location: " .Config::get('address')."/user/video");
                
            }
        }
    }
    
    
    function edit(){
        $this->JS .= Helper::jsScript("upload.js");
        
        $this->showUpload=false;
        $video = new Video($_GET['id']);
        $this->video=$video;
        $this->editMode=true;
        $this->thumbnails = array(Config::get('address') . "/public/video/" . $video->user_id. "/" . $video->id. "/thumb1.png", 
                                    Config::get('address') . "/public/video/" . $video->user_id. "/" . $video->id. "/thumb2.png",
                                    Config::get('address') . "/public/video/" . $video->user_id. "/" . $video->id. "/thumb3.png");
    }
    
    
    function update()
    {
        if($_POST['delete']=="true")
        {
            $video=new Video($_POST['id']);
            $video->user_id = $_SESSION['user_id'];
            
            $video->del_save();
            
        }
        
        if(isset($_POST)  && !empty($_POST)){
            $error=false;
            if(!$_POST['title'] || empty($_POST['title']))
            {
                $error=true;
            }
            if(!$_POST['description'] || empty($_POST['description']))
            {
                $error=true;
            }   
            
            
            if($error==false)
            {
                
                $video = new Video($_POST['id']);
                if($video->user_id == $_SESSION['user_id'])
                {
                    $video->title = $_POST['title'];
                    $video->descripton = $_POST['description'];
                    $video->visibility_setting= $_POST['visibility'];
                    $video->thumb = $_POST['thumb'];
                    $video->save();
                    header("Location: " .Config::get('address')."/user/video");
                }
                
            }
        }
        var_dump($_POST);
    }
    
}
?>