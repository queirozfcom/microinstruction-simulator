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

$(document).ready(function() {

    $('#add-instruction-button').click(function() {
        $('#add-instruction-form').submit();
    });

    $("#NewInstructionForm_target_param").change(function() {

        if ($(this).val() == "CONSTANT") {
            $("#NewInstructionForm_target_constant").fadeIn('fast').focus();

        } else {
            $("#NewInstructionForm_target_constant").fadeOut('fast');
        }
    });
    $("#NewInstructionForm_source_param").change(function() {

        if ($(this).val() == "CONSTANT") {
            $("#NewInstructionForm_source_constant").fadeIn('fast').focus();
        } else {
            $("#NewInstructionForm_source_constant").fadeOut('fast');
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
});