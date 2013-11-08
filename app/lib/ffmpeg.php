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
    //0 = mean all available threads / or guess how many 
    public $threads = "0";
    
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

        exec("mplayer -nosound -vf screenshot ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$start))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs1 2>&1", $out);
        exec("mplayer -nosound -vf screenshot ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$middle))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs2 2>&1", $out);
        exec("mplayer -nosound -vf screenshot ".$this->filename." -ss ".date('H:i:s', mktime(0,0,$end))." -frames 1 -vo png:z=9:outdir=".$this->filename."_thumbs3 2>&1", $out);

    }
    
    public function convertVideo($type="webm"){
        
        if($type=="webm")
            //$pid=shell_exec($this->converter ." -i ".$this->filename." -threads ".$this->threads."  -acodec libvorbis -ac 2 -ab 96k -ar 44100 -b 345k -s 640x360 ".$this->filename.".".$type);
            
            //Standard web video (480p at 600kbit/s):
            $pid=shell_exec($this->converter ." -i ".$this->filename." -vcodec libvpx -quality good -vb 600k -maxrate 600k -bufsize 1200k -qmin 10 -qmax 42 -vf scale=-1:480 -threads 0 -strict experimental -acodec libvorbis -ab 128k ".$this->filename.".".$type);
            
            //High-quality SD video for archive/storage (PAL at 1.2Mbit/s):
            //$pid=shell_exec($this->converter ." -i ".$this->filename."-codec:v libvpx -quality good -cpu-used 0 -b:v 1200k -maxrate 1200k -bufsize 2400k -qmin 10 -qmax 42 -vf scale=-1:480 -threads 4 -codec:a vorbis -b:a 128k ".$this->filename.".".$type);
        if($type=="mp4"){
            //exec($this->converter ." -i ".$this->filename." -threads ".$this->threads."  -vcodec libx264 -level 12 -b 1048576 -r 25 -bt 1179648 -s 240x192 -coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 2 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid+wpred+dct8x8+fastpskip -f mp4 -acodec libfaac -ab 458752 -ac 2 -ar 48000 $this->filename.".$type);
            //$pid=shell_exec("nohup ".$this->converter ." -i ".$this->filename." -preset slow -threads ".$this->threads."  -vcodec libx264 -level 12 -b 2000000 -r 25 -bt 1179648 -s 240x192 -coder 1 -flags +loop -cmp +chroma -partitions +parti8x8+parti4x4+partp8x8+partb8x8 -me_method hex -subq 7 -me_range 16 -g 250 -keyint_min 25 -sc_threshold 40 -i_qfactor 0.71 -b_strategy 1 -qcomp 0.6 -qmin 10 -qmax 51 -qdiff 4 -bf 2 -refs 1 -directpred 1 -trellis 0 -flags2 +bpyramid+wpred+dct8x8+fastpskip -f mp4 -acodec libfaac -ab 458752 -ac 2 -ar 48000 $this->filename-notFinished.".$type. " > /dev/null 2> /dev/null & echo $!");            
           $preset=Config::get('basedir').'/app/lib/ffmpeg-presets/libx264-hq.ffpreset';
           $preset=Config::get('basedir').'/app/lib/ffmpeg-presets/libx264-veryfast.ffpreset -crf 28';
           $pid=shell_exec($this->converter ." -i ".$this->filename." -vcodec libx264 -fpre $preset -threads ".$this->threads." $this->filename.".$type);
           // echo $this->converter ." -i ".$this->filename." -fpre $preset -s 640x360 -b 2000000 -threads 0 -deinterlace -f mp4 $this->filename-notFinished.".$type ;
        }
        return array(trim($pid) => $this->filename);

    }
    

    
    public static function isProcessRunning($PID)
    {
        exec("ps $PID", $ProcessState);
        return(count($ProcessState) >= 2);
    }
}

?>
