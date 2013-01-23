
//check if video is ready 
$(function() {
var videofile = jQuery("#mediaplayback").attr("video");
if (videofile)
{
    setTimeout(waitingforConversion, 1000);
 
}

    function waitingforConversion()
    {
        $.ajax({url: "/videostream/video/manager/checkStatus", data: {"video": jQuery("#mediaplayback").attr("video"), "pid": jQuery("#mediaplayback").attr("pid")}}).done(function(data) { 
            if(data.isConverted == true)
            {
                $(function(){
                var options = {
                    src: "http://andreas.lan/videostream/public/upload/"+jQuery("#mediaplayback").attr("video")+".mp4",
                    width: 640,
                    height: 480,
                    swf: "http://andreas.lan/videostream/public/StrobeMediaPlayback.swf",
                    poster: jQuery("#thumb1").attr("src")
                };
                // Now we are ready to generate the video tags
                $("#mediaplayback").strobemediaplayback(options);
                
            });
            }
            else
            {
                setTimeout(waitingforConversion, 1000);
            }
            

        });
    }
});