<?php

class Video_View {

    function __construct() {

        $this->Template["index"] = "index.php";
        $this->Template["thumbnail"] = false;
    }

    function index() {


        $this->showRegister = false;

        $video = new Video($_GET['id']);
        if ($video->visibility_setting == 3 && $video->user_id != $_SESSION['user_id']) {
            $this->error = _("This video is only available for specific user");
        }
        if ($video->visibility_setting == 2 && empty($_SESSION['user_id'])) {
            $this->showRegister = true;
            $this->error = _("This video is only available for registered Users");
        }
        $this->filename = $video->filename;
        $this->user_id = $video->user_id;
        $this->id = $video->id;
        $this->title = $video->title;
        $this->Title = $video->title;
        $this->desc = $video->descripton;
        $this->thumb = $video->thumb;
    }

    function thumbnail() {
        $video = new Video($_GET['id']);
        $img = Config::get("basedir") . "/public/video/" . $video->user_id . "/" . $video->id . "/thumb" . $video->thumb . ".png";

        $thumb = str_replace(".png", "-" . $_GET['width'] . "x" . $_GET['height'] . ".png", $img);
        header("Pragma: public");

        //get the last-modified-date of this very file
        $lastModified = filemtime($img);
        //get a unique hash of this file (etag)
        $etagFile = md5_file($img);
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? $_SERVER['HTTP_IF_MODIFIED_SINCE'] : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);
        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etagFile");
        header('Cache-Control: public');
        /* 	if (@strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])==$lastModified || $etagHeader == $etagFile)
          {
          header("HTTP/1.1 304 Not Modified");
          exit;
          }
         */

        if (file_exists($thumb)) {
            header("Content-Type: image/png");
            echo file_get_contents($thumb);
            die();
        }

        $image = new Image($img);

        if (isset($_GET['width']) && is_numeric($_GET['width']) && isset($_GET['height']) && is_numeric($_GET['height'])) {
            $image->resize($_GET['width'], $_GET['height'], "crop");
            $img = str_replace(".png", "-" . $_GET['width'] . "x" . $_GET['height'] . ".png", $img);
            $parts = pathinfo($img);
            $image->setPathToTempFiles("/tmp");
            $image->save($parts['filename'], $parts['dirname'], $parts['extension']);
        }

        $image->display();
        die();
    }

}

?>
