$(document).ready(function(){
//   $(".select-input").selectmenu({
//                    style:'dropdown',
//                    menuWidth:'200',
//                    width:'200'                
//                }); 
                
   $('#add-instruction-button').click(function(){
      $('#add-instruction-form').submit(); 
   });
                
   $("#NewInstructionForm_target_reg-menu li").click(function(){
      value = $("#NewInstructionForm_target_reg-button span.ui-selectmenu-status").html();
      if(value=="CONSTANT"){
          $("#NewInstructionForm_target_constant").fadeIn('fast').focus();
          
      }else{
          $("#NewInstructionForm_target_constant").fadeOut('fast');
      }
   });
   $("#NewInstructionForm_source_reg-menu li").click(function(){
      value = $("#NewInstructionForm_source_reg-button span.ui-selectmenu-status").html();
      if(value=="CONSTANT"){
          $("#NewInstructionForm_source_constant").fadeIn('fast').focus();
      }else{
          $("#NewInstructionForm_source_constant").fadeOut('fast');
      }
   });
   
   $("#controls-div,#controls-div input").bind('keyup',function(e){
      if(e.which==13){
          $("#add-instruction-form").submit();
          return false;
      }
   });
});