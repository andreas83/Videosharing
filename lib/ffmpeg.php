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
        

    }
    
    public function convertVideo($type="webm"){
        $start=date("U");
        if($type=="webm")
            exec("nohup ".$this->converter ." -i ".$this->filename." -threads ".$this->threads."  -acodec libvorbis -ac 2 -ab 96k -ar 44100 -b 345k -s 640x360 ".$this->filename."-notFinished.".$type. "  > /dev/null 2> /dev/null & echo $");
            exec("nohub mv ".$this->filename."-notFinished.".$type." " .$this->filename.".".$type. " > /dev/null 2> /dev/null & echo $");
        //480p at 500kbit/s
        if($type=="mp4"){
            //exec($this->converter ." -i ".$this->filename." -threads ".$this->threads."  -vcodec libx264 -level 12 -b 1048576 -r 25 -bt 1179648 -s 240x192 -coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 2 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid+wpred+dct8x8+fastpskip -f mp4 -acodec libfaac -ab 458752 -ac 2 -ar 48000 $this->filename.".$type);
            $pid=shell_exec("nohup ".$this->converter ." -i ".$this->filename." -preset slow -threads ".$this->threads."  -vcodec libx264 -level 12 -b 1048576 -r 25 -bt 1179648 -s 240x192 -coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 2 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid+wpred+dct8x8+fastpskip -f mp4 -acodec libfaac -ab 458752 -ac 2 -ar 48000 $this->filename-notFinished.".$type. " > /dev/null 2> /dev/null & echo $!");            
            //$pid=shell_exec("nohup ".$this->converter ." -y -i ".$this->filename." -threads ".$this->threads." -y -r 24000/1001 -b 6144k -bt 8192k -vcodec libx264 -pass 1 -flags +loop -me_method dia -g 250 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 16 -b_strategy 1 -i_qfactor 0.71 -cmp +chroma -subq 1 -me_range 16 -coder 1 -sc_threshold 40 -flags2 -bpyramid-wpred-mixed_refs-dct8x8+fastpskip -keyint_min 25 -refs 1 -trellis 0 -directpred 1 -partitions -parti8x8-parti4x4-partp8x8-partp4x4-partb8x8-an $this->filename-notFinished.".$type. " > /dev/null 2> /dev/null & echo $!");
            
        }
        return trim($pid);

        $end=date("U");
        $benchmark=$end-$start;
        //echo $benchmark ." sek to long for  ".$type."<br/>";
    }
    
    public static function markFinished($video)
    {
        exec("mv ".$video."-notFinished.mp4 " .$video.".mp4");
        
    }
    
    public static function isProcessRunning($PID)
    {
        exec("ps $PID", $ProcessState);
        return(count($ProcessState) >= 2);
    }
}

?>
