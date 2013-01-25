<?php
class User_Video {

    function __construct() {
        $this->Template["index"] = "myvideo.php";
        $this->Title=_("My Video");
        
    }
    
    function index(){
        $video = new Video();
        $this->JS .= Helper::jsScript("swfobject.js");
        $this->JS .= Helper::jsScript("jquery.strobemediaplayback.js");        
        $this->JS .= Helper::jsScript("myvideo.js");  
        
        
        if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id']))
        {
            $this->error="Please Login";
            
        }
        
        $this->inProgress = $video->get_list(array("isConverted"=>"0", "user_id" => $_SESSION['user_id']));
                
        $page = (int)(isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
        
        $video->getPages(false, false, $_SESSION['user_id']);
        $allPagesFromCurrentUser = $video->allVideos;
        $showPerSite = 5;
        
        $this->Seiten=(int)ceil($allPagesFromCurrentUser/$showPerSite);
        
        $this->page = $page;
        
        $start_pointer=(int)($page*$showPerSite)-$showPerSite;
        
        $this->obj = $video->getPages($start_pointer, $showPerSite, $_SESSION['user_id']);
                
        
        
    }
}
?>