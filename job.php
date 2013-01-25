<?php
include "init.php";


$pids= array();
while(true)
{
   
    
    $newJob = new Video();
    $obj = $newJob->get_list(array("isConverted" => 0));
    
    if(count($obj)>0)
        file_put_contents("job.lock", "working");
    else
    {
        sleep(1);
        continue;
    }
    foreach ($obj as $video)
    {

        $ffmpeg = new FFmpeg(Config::get('basedir') . "/public/upload/" . $video->filename);

        $ffmpeg->convertVideo("webm"); 
        
        $ffmpeg->convertVideo("mp4");
        



        if(!is_dir(Config::get('basedir') . "/public/video/".$video->user_id))
        {
            mkdir(Config::get('basedir') . "/public/video/".$video->user_id);
        }
        if(!is_dir(Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id))
        {
            mkdir(Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id);
        }
        rename(Config::get('basedir') . "/public/upload/".$video->filename.".mp4", 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/".$video->id.".mp4");
        rename(Config::get('basedir') . "/public/upload/".$video->filename.".webm", 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/".$video->id.".webm");

        rename(Config::get('basedir') . "/public/upload/".$video->filename, 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/".$video->id.".basefile");

        rename(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs1/00000001.png", 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/thumb1.png");
        rename(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs2/00000001.png", 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/thumb2.png");
        rename(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs3/00000001.png", 
                Config::get('basedir') . "/public/video/".$video->user_id."/".$video->id."/thumb3.png");


        rmdir(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs1");
        rmdir(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs2");
        rmdir(Config::get('basedir') . "/public/upload/".$video->filename."_thumbs3");
        
        $video->isConverted=1;
        $video->save();
        
        unlink("job.lock");

        
        
    }
    sleep(2);
   
}
?>
