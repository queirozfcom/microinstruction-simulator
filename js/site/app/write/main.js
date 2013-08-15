//for browsers that don't ship this function
if (!Array.prototype.indexOf) {
    Array.prototype.indexOf = function(needle) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] === needle) {
                return i;
            }
        }
        return -1;
    };
}

function fnValidateActiveForm(param, parent) {
                var form = $(parent), settings = form.data('settings');
                $.each(settings.attributes, function (i, attribute) {
                    $.fn.yiiactiveform.updateInput(attribute, param, form);
                });
};

$(document).ready(function() {

    $('#add-instruction-button').click(function() {
        $('#add-instruction-form').submit();
    });

    $("#NewInstructionForm_target_param").change(function() {

        if ($(this).val() == "CONSTANT") {
            $("#NewInstructionForm_target_constant").fadeIn('fast').focus();

        } else {
            $("#NewInstructionForm_target_constant").fadeOut('fast');
            //remove error class if it's there
            $("#NewInstructionForm_target_constant").removeClass('error');
            //these may lag behind because they're not in the same div
            $("#NewInstructionForm_target_constant").val('');
            $("#NewInstructionForm_target_constant_em_").hide();
        }
    });
    $("#NewInstructionForm_source_param").change(function() {

        if ($(this).val() == "CONSTANT") {
            $("#NewInstructionForm_source_constant").fadeIn('fast').focus();
        } else {
            $("#NewInstructionForm_source_constant").fadeOut('fast');
            //remove error class if it's there
            $("#NewInstructionForm_source_constant").removeClass('error');
            //these may lag behind because they're not in the same div
            $("#NewInstructionForm_source_constant").val('');
            $("#NewInstructionForm_source_constant_em_").hide();
        }
    });

    $("#controls-div,#controls-div input").bind('keyup', function(e) {
        if (e.which == 13) {
            $("#add-instruction-form").submit();
            return false;
        }
    });

    var mnemonicsThatRequire2Arguments = [
        "MOV",
        "ADD",
        "SUB",
        "AND",
        "OR",
        "NAND",
        "NOR",
        "XOR",
        "CMP"
    ];

    var mnemonicsThatRequire1Argument = [
        "CLR",
        "NOT",
        "SHL",
        "SHR",
        "BRZ",
        "BRN",
        "BRE",
        "BRL",
        "BRG",
        "RJMP"
    ];

    $("#NewInstructionForm_mnemonic").change(function() {
        var currentMnemonic = $(this).val();
        var sourceDiv = $("div.source");
        var targetDiv = $("div.target");

        if (currentMnemonic === "") {
            return;
        } else {

            if (mnemonicsThatRequire2Arguments.indexOf(currentMnemonic) > -1) {
                targetDiv.show();
            } else if (mnemonicsThatRequire1Argument.indexOf(currentMnemonic) > -1) {
                targetDiv.hide();
            }

        }
    });
    
    
    $("#NewInstructionForm_target_param_indirection").click(function(e){
        if($(this).prop('checked')){
            if($('#NewInstructionForm_target_param').parent().hasClass('error')){
                
                if($('#NewInstructionForm_target_param_em_').html()==='Direct Constants cannot be used as the target for an Instruction.'){
                    $('#NewInstructionForm_target_param').parent().removeClass('error');
                    $('#NewInstructionForm_target_param').parent().addClass('success');
                    $('#NewInstructionForm_target_param_em_').html('').hide();
                }
                
            }
        }else{
            //this makes it invalid.
            $('#NewInstructionForm_target_param').parent().removeClass('success').addClass('error');
            $('#NewInstructionForm_target_param_em_').show().html('Direct Constants cannot be used as the target for an Instruction.');
        }
        
    });
    
});