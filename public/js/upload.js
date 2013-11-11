$(function() {
    if($('#upload_form').length!=0)
    {
        $("#upload").click(function(){
            var form = document.querySelector('form');
            var formData = new FormData(form);
            
            
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/video/manager/uploadFile', true);
            // Just one header should be send with this request to
            // detect an ajax request
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        
            xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                $(".meter").css("width", percentComplete+"%");
                
            }
            };

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = $.parseJSON(xhr.response);
                    
                     window.location ="/video/manager/editFile?video_id="+response.tmpFile;
                }
            }


            xhr.onload = function() {
            if (this.status == 200) {
            var resp = JSON.parse(this.response);

            console.log('Server got:', resp);

            };
            };

            xhr.send(formData);
            
        });
    }
    
    var selectedThumb=$("#thumb").val();
    var i=0;
    $(".video-thumbnail").each(function(){
        i++;
        if(i==selectedThumb)
            $(this).css("border-bottom", "4px solid #FF0000");
        
    });
   
    
   $(".video-thumbnail").click(
    function(){
        $(".video-thumbnail").css("border", "0px solid #ccc");
        
        $(this).css("border-bottom", "4px solid #FF0000");
        $("#thumb").val($(this).attr("thumb"));
    }
    ); 
    
});
