<?php


/**
 * FFmpeg class
 *
 * @author andreas
 */
class FFmpeg {
    
    //os comunity battle arround ffmpeg and avconv
    //look like debian/ubuntu will use avconv in the near future
    //avconv is just a fork of ffmpeg
    public $converter = "ffmpeg";
    
    
    /**
     * filename
     * 
     * @var string
     */
    public $filename = "";
    
    //how many thread should ffmpeg use while converting 
    //0 = mean all available threads
    public $threads = "8";
    
    /**
     * 
     * @param type $filename
     */
    function __construct($filename) {
        $this->filename = $filename;
    }
    
    
    /**
     * Get File Information
     * 
     * 
     * @return array with duration, bitrate, resoloution
     */
    public function getFileInformation()
    {
        exec($this->converter ." -i $this->filename 2>&1", $output);
        foreach ($output as $line)
        {
            if( strpos($line, "Duration:") )
            {
                $data = explode(',', trim($line));
                $duration = str_replace("Duration: ", "", $data[0]);
                $bitrate  = str_replace("bitrate: ", "", trim($data[2]));
             
            }
            if(preg_match_all("/(\d+)x(\d+)/", $line, $resoloution))
                $resoloution = $resoloution[0][0];
           
        }
        $data = array("Duration" => $duration, "Bitrate" => $bitrate, "Resoloution" => $resoloution);
        return $data;
    }
    
    /**
     * create Thumbnail
     * 
     * It turns out that mplayer is much faster than ffmpeg
     * Also the ffmpeg team recomends to use mplayer for thumbs
     * 
     * @param string $res
     * @throws RuntimeException
     */
    public function createThumbnail($res = "320x240")
    {
        //first we need to know how long the video is
        // to create thumbs from the beginning, middle and end
        $info=$this->getFileInformation();
        $duration = substr($info['Duration'], 0, -3);

        $parse = array();
        if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2}):(?<secs>[\d]{2})$#',$duration,$parse)) {
            
             throw new RuntimeException ("Hour Format not valid");
        }
        $sec = (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60 + (int) $parse['secs'];
        
        //we create the first thumb at 30 %, next at 60% and thelast at 90% of the video
        $start = floor(30*$sec/100);
        $middle = floor(60*$sec/100);
        $end = floor(90*$sec/100);

        exec("mplayer ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$start))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs1 2>&1", $out);
        exec("mplayer ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$middle))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs2 2>&1", $out);
        exec("mplayer ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$end))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs3 2>&1", $out);
        
       /**
        *      
        * Attention if converter is set to avconv 
        * you get allways the same thumbnail   

        echo $duration."<br>";
        echo $start."<br>";
        echo $middle."<br>";
        echo $end."<br>";
        
        
        exec($this->converter ." -itsoffset -".$start." -i ".$this->filename." -vcodec mjpeg -vframes 1 -an -f rawvideo -s ".$res." ".$this->filename."start.jpg 2>&1", $output);
        exec($this->converter ." -itsoffset -".$middle." -i ".$this->filename." -vcodec mjpeg -vframes 1 -an -f rawvideo -s ".$res." ".$this->filename."middle.jpg 2>&1", $output);
        exec($this->converter ." -itsoffset -".$end." -i ".$this->filename." -vcodec mjpeg -vframes 1 -an -f rawvideo -s ".$res." ".$this->filename."end.jpg 2>&1", $output);
        echo $this->converter ." -itsoffset -".$end." -i ".$this->filename." -vcodec mjpeg -vframes 1 -an -f rawvideo -s ".$res." ".$this->filename."end.jpg 2>&1";
         *  */
        

    }
    
    public function convertVideo($type="webm"){
        $start=date("U");
        if($type=="webm")
            exec($this->converter ." -i ".$this->filename." -threads ".$this->threads."  -acodec libvorbis -ac 2 -ab 96k -ar 44100 -b 345k -s 640x360 $this->filename.".$type);
        
        //480p at 500kbit/s
        if($type=="mp4"){
            exec($this->converter ." -i ".$this->filename." -threads ".$this->threads." -vcodec libx264 -vprofile high -preset slow -b:v 500k -maxrate 500k -bufsize 1000k -vf scale=-1:480 -threads 0 -acodec libfdk_aac -b:a 128k $this->filename.".$type);
            
        }
        $end=date("U");
        $benchmark=$end-$start;
        echo $benchmark ." sek to long for  ".$type."<br/>";
    }
}

?>
