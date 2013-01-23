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
            $this->JS .= Helper::jsScript("swfobject.js");
            $this->JS .= Helper::jsScript("jquery.strobemediaplayback.js");
            $this->JS .= Helper::jsScript("video.js");            
            // we need a better mime type checks i guess
            
            // $allowedFileExtension = array("avi", "mov", "mpeg", "flv");
            
            $tmpVideoName = md5(microtime());
            move_uploaded_file($_FILES['file_upload']['tmp_name'], Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg = new FFmpeg(Config::get('basedir') . "/public/upload/" . $tmpVideoName);
            $ffmpeg->getFileInformation();
            $ffmpeg->createThumbnail();
            //$ffmpeg->convertVideo("webm"); 
            $this->showUpload=false;
            $this->pid = $ffmpeg->convertVideo("mp4");
            $this->thumb = Config::get('address') . "/public/upload/" . $tmpVideoName;
            $this->filename = $tmpVideoName;
        }
    }
    
    
 
    /**
     * @todo sanitize input
     */
    function checkStatus()
    {
       
        if(is_numeric($_GET['pid']) && FFmpeg::isProcessRunning($_GET['pid'])){
            $this->json=array("isConverted" => false);
        }else
        {
            FFmpeg::markFinished(Config::get('basedir') . "/public/upload/".$_GET['video']);
            $this->json=array("isConverted" => true);
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
                $video->enabled = 1;
                $video->visibility_setting= $_POST['visibility'];
                $video->save();
                
                if(!is_dir(Config::get('basedir') . "/public/video/".$_SESSION['user_id']))
                {
                    mkdir(Config::get('basedir') . "/public/video/".$_SESSION['user_id']);
                }
                if(!is_dir(Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id))
                {
                    mkdir(Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id);
                }
                rename(Config::get('basedir') . "/public/upload/".$_POST['filename'].".mp4", 
                        Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id."/".$video->id.".mp4");
                
                rename(Config::get('basedir') . "/public/upload/".$_POST['filename'], 
                        Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id."/".$video->id.".basefile");
                
                rename(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs1/00000001.png", 
                        Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id."/thumb1.png");
                rename(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs2/00000001.png", 
                        Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id."/thumb2.png");
                rename(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs3/00000001.png", 
                        Config::get('basedir') . "/public/video/".$_SESSION['user_id']."/".$video->id."/thumb3.png");
                
                unlink(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs1/00000001.png");
                unlink(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs2/00000001.png");
                unlink(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs3/00000001.png");
                rmdir(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs1");
                rmdir(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs2");
                rmdir(Config::get('basedir') . "/public/upload/".$_POST['filename']."_thumbs3");
                
                
            }
        }
    }
}
?>