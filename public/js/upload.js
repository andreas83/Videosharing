$(function() {
    
    var selectedThumb=$("#thumb").val();
    var i=0;
    $(".thumbnail").each(function(){
        i++;
        if(i==selectedThumb)
            $(this).find("img").css("border-bottom", "4px solid #FF0000");
        
    });
   
    
   $(".thumbnail").click(
    function(){
        $(".thumb").css("border", "0px solid #ccc");
        
        $(this).find("img").css("border-bottom", "4px solid #FF0000");
        $("#thumb").val($(this).find("img").attr("thumb"));
    }
    ); 
    
});
