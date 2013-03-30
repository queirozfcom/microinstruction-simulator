$(document).ready(function(){
    $("#reset-button").click(function(){
        $("div.tooltip").hide();
        
        var url = $(this).attr('targeturl');
        call(url);
    });
    $("#run-next-instruction-button").click(function(){
        $("div.tooltip").hide();
        var url = $(this).attr('targeturl');
        call(url);
    });
    $("#run-everything-button").click(function(){
        $("div.tooltip").hide();
        var url = $(this).attr('targeturl');
        call(url);
    });



    $("[rel=tooltip]").tooltip();
});

function call(url){
        $.post(
        url,
        "",
        function(data){
           update_fields(data); 
        },
        'json');      
}

function update_fields(data){
    var RO_field = '#r0-contents';
    var R1_field = '#r1-contents';
    var R2_field = '#r2-contents';
    var R3_field = '#r3-contents';
    var R4_field = '#r4-contents';
    var AR1_field = '#ar1-contents';
    var AR2_field = '#ar2-contents';
    var PC_field = '#pc-contents';
    var IR_field = '#ir-contents';
    var MAR_field = '#mar-contents';
    var MDR_field = '#mdr-contents';
    var current_macro_field = '#current-instruction-span';
    var log = '#log-contents';
    
    $(current_macro_field).html(data.current_macro);
    $(PC_field+" div.reg-contents-input").html(data.PC);
    
    
    $(RO_field+" div.reg-contents-input").html(data.R0);
    $(R1_field+" div.reg-contents-input").html(data.R1);
    $(R2_field+" div.reg-contents-input").html(data.R2);
    $(R3_field+" div.reg-contents-input").html(data.R3);
    $(R4_field+" div.reg-contents-input").html(data.R4);
    
    $(AR1_field+" div.reg-contents-input").html(data.AR1);
    $(AR2_field+" div.reg-contents-input").html(data.AR2);
    
    
    
    $(MAR_field+" div.reg-contents-input").html(data.MAR);
    $(MDR_field+" div.reg-contents-input").html(data.MDR);
    
    $(IR_field+" div.reg-contents-input").html(data.IR);
    
    $(log).html("");
    for(var i=0;i<data.log.length;i++){
        $(log).append(data.log[i]+'<br />');    
    }
    
    $(log).scrollTop($(log)[0].scrollHeight);
   
    $("[rel=tooltip]").tooltip();
}
