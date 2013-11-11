$(function() {
    if($('#upload_form').length!=0)
    {
    $('#upload_form').fileupload({
        dataType: 'json',
        add: function (e, data) {
            data.context = $('<button class="btn btn-inverse"/>').text('Upload')
                .appendTo("#upload_form")
                .click(function () {
                    $(this).replaceWith($('<p/>').text('Uploading...'));
                    data.submit();
                });
        },
        stop: function (e, data) {
            alert(e.data);
        },
        done: function (e, data) {
            window.location ="/video/manager/editFile?video_id="+data.result.tmpFile;
        },
        progressall: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('.bar').css(
            'width',
            progress + '%'
        );
        }
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
