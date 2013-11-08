<?php
class Video_Manager
{
    function __construct()
    {

        $this->Template["index"] = "index.php";
        $this->Template["upload"] = "upload.php";
        $this->Template["save"] = "upload.php";
        $this->Template["edit"] = "upload.php";
        $this->Template["update"] = "upload.php";
        $this->Template['uploadFile'] = "json.php";
        $this->Template['editFile'] = "upload.php";
        
        
        $this->showUpload=true;
    }


    function index()
    {

        $this->Title = "Overview";
        $this->sub_headline = "Overview";
    }


    /**
     * This Controller is responsible for
     * the Upload Form handling
     */
    function upload()
    {

        $this->Title = "Upload";

         $this->JS .= Helper::jsScript("jquery.ui.widget.js");
         $this->JS .= Helper::jsScript("jquery.iframe-transport.js");
         $this->JS .= Helper::jsScript("jquery.fileupload.js");
         $this->JS .= Helper::jsScript("upload.js");


        
    }
    
    
    function uploadFile()
    {
        if ( isset($_FILES) && isset($_POST) && !empty($_FILES) )
        {

            // we need a better mime type checks i guess
            
            // $allowedFileExtension = array("avi", "mov", "mpeg", "flv");
           
            $tmpVideoName = md5(microtime());
            
            move_uploaded_file($_FILES['file_upload']['tmp_name'], Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            
            $ffmpeg = new FFmpeg(Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg->getFileInformation();
            $ffmpeg->createThumbnail();

            
            $this->json = array("tmpFile"=>$tmpVideoName);


        }
    }
    
    function editFile()
    {
        $this->JS .= Helper::jsScript("upload.js");
        $this->Title = "Edit Video";
        
        
        $tmpVideoName=$_GET['video_id'];
        
        $this->showUpload=false;
        $this->editMode=false;        
        
        $this->thumbnails = array(Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs1/00000001.png", 
                                  Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs2/00000001.png",
                                  Config::get('address') . "/public/upload/" . $tmpVideoName. "_thumbs3/00000001.png");
        $this->filename = $tmpVideoName;
        $this->showAlert = true;
        $this->success=_("Please enter the Title and Description of your Video");
        $video = new Video();
        $this->video=$video;
           
        
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
                $video->thumb = (isset($_POST['thumb']) ? $_POST['thumb'] : "1");
                $video->visibility_setting= $_POST['visibility'];
                $video->save();
                header("Location: " .Config::get('address')."/user/video");
                
            }
        }
    }
    
    
    function edit(){
        $this->JS .= Helper::jsScript("upload.js");
        $this->Title = "Edit Video";
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
                    $video->thumb = (isset($_POST['thumb']) ? $_POST['thumb'] : "1");
                    $video->save();
                    header("Location: " .Config::get('address')."/user/video");
                }
                
            }
        }
        var_dump($_POST);
    }
    
}
?>
