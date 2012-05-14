$(document).ready(function(){
    $("#bootstrap-button").click(function(){
        var url = $(this).attr('targeturl');
        $.post(
        url,
        "",
        function(data){
           update_fields(data); 
        },
        'json');
    });
    $("#fetch-first-instruction-button").click(function(){
        var url = $(this).attr('targeturl');
        $.post(
        url,
        "",
        function(data){
           update_fields(data); 
        },
        'json');
    });
});

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
    var bootstrap_button = '#bootstrap-button';
    var fetch_first_instruction_button = '#fetch-first-instruction-button';
    
    if(data.execution_phase){
        $(bootstrap_button).css('display', 'none');
        $(fetch_first_instruction_button).css('display','inline-block');
    }
    if(data.fetch_first){
        $(fetch_first_instruction_button).css('display','none');
    }
    
    $(current_macro_field).html(data.current_macro);
    $(RO_field+" div.reg-contents-input").html(data.R0);
    
    
    
    
}
