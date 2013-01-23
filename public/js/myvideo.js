$(function() {
    
    jQuery(".mediaplayback").each(
        function(key, val){
            
             var options = {
                src: "http://andreas.lan/videostream/public/video/"+jQuery(this).attr("uid")+"/"+jQuery(this).attr("video")+"/"+jQuery(this).attr("video")+".mp4",
                width: 440,
                height: 280,
                swf: "http://andreas.lan/videostream/public/StrobeMediaPlayback.swf",
                poster: "http://andreas.lan/videostream/public/video/"+jQuery(this).attr("uid")+"/"+jQuery(this).attr("video")+"/thumb1.png"
            };
            
            // Now we are ready to generate the video tags
            $(this).strobemediaplayback(options);
           });
        }
    );
    
