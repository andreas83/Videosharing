<?php

class Video_View
{
    function __construct()
    {

        $this->Template["index"] = "index.php";
        $this->Template["thubnail"] = false;

    }


    function index()
    {
  
        
//        $this->JS .= Helper::jsScript("swfobject.js");
//        $this->JS .= Helper::jsScript("jquery.strobemediaplayback.js");        
//        $this->JS .= Helper::jsScript("myvideo.js");  
//        
//        $this->JS .= Helper::jsScript("jquery-ui.js");        
//        $this->JS .= Helper::jsScript("jquery.acornmediaplayer.js");        
//        $this->JS .= Helper::jsScript("html5player.js");  
//        $this->JS .= Helper::cssScript("acornmediaplayer.base.css"); 
//        $this->JS .= Helper::cssScript("themes/darkglass/acorn.darkglass.css");
        
        $this->showRegister=false;
        
        $video = new Video($_GET['id']);
        if($video->visibility_setting==3 && $video->user_id != $_SESSION['user_id'])
        {
            
            $this->error = _("This video is only available for specific user");
        }
        if($video->visibility_setting==2 && empty($_SESSION['user_id']))
        {
            $this->showRegister = true;
            $this->error = _("This video is only available for registered Users");
        }        
        $this->filename=$video->filename;
        $this->user_id=$video->user_id;
        $this->id=$video->id;
        $this->title = $video->title;
        $this->Title = $video->title;
        $this->desc = $video->descripton;
        $this->thumb = $video->thumb;

    }
    function thumbnail()
    {
	$video = new Video($_GET['id']);
	$img= Config::get("basedir")."/public/video/".$video->user_id."/".$video->id."/thumb".$video->thumb.".png";

	$image = new Image($img);


	if(isset($_GET['width']) && is_numeric($_GET['width']) && isset($_GET['height']) && is_numeric($_GET['height']))
	{
        	$image->resize($_GET['width'], $_GET['height'], "crop");
	}
	$offset = 300 * 60 * 60;
	$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
	header ($expire);
	$image->display();
	die();
    }
}
?>
