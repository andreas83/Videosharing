$(function() {
    
    jQuery(".mediaplayback").each(
        function(key, val){
            
             var options = {
                src: jQuery("#base").attr("href")+"/public/video/"+jQuery(this).attr("uid")+"/"+jQuery(this).attr("video")+"/"+jQuery(this).attr("video")+".mp4",
                width: 440,
                height: 280,
                swf: jQuery("#base").attr("href")+"/public/StrobeMediaPlayback.swf",
                poster: jQuery("#base").attr("href")+"/public/video/"+jQuery(this).attr("uid")+"/"+jQuery(this).attr("video")+"/thumb1.png"
            };
            
            // Now we are ready to generate the video tags
            $(this).strobemediaplayback(options);
           });
        }
    );
    
