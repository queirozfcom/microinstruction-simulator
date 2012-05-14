$(document).ready(function(){
    $("#bootstrap-button").click(function(){
        var url = $(this).attr('targeturl');
        $.post(url,
        "",
        function(data){
           alert(data.R0); 
        },
        'json');
    });
});


