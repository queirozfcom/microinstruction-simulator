<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of NewInstructionForm
 *
 * @author felipe
 */
class NewInstructionForm extends CFormModel{
    public $mnemonic;
    
    public $target_reg_indirection;
    public $target_constant;
    public $target_reg;
    
    public $source_reg_indirection;
    public $source_constant;
    public $source_reg;
    
    public function rules()
    {
            return array(
                    // username and password are required
                    array('mnemonic, target_reg', 'required'),
                    // rememberMe needs to be a boolean
                    array('target_reg_indirection, source_reg_indirection', 'boolean'),
                
                    array('target_reg','check_target_constant'),
                
                    array('source_reg','check_source_constant'),

                    array('target_constant, source_constant','remove_error')
            );
    }
    /**
        * Declares attribute labels.
        */
    public function attributeLabels()
    {
            return array(
                    'mnemonic'=>'Mnemonic',
                
                    'target_reg_indirection'=>'',
                    'target_constant'=>'',
                    'target_reg'=>'Target Register',
                
                    'source_reg_indirection'=>'',
                    'source_constant'=>'',
                    'source_reg'=>'Source Register'
            );
    }
    public function check_target_constant($attribute,$params){
        if($this->target_reg=="CONSTANT"){
            if($this->target_constant==""){
                $this->addError('target_reg', 'Constant must be a number');
            }
        }            
    }
    public function check_source_constant($attribute,$params){
        if($this->source_reg=="CONSTANT"){
            if($this->source_constant==""){
                $this->addError('source_reg', "Constant must be a number");
            }
        }             
    }
    
    public function remove_error($attribute,$params){
        if($this->$attribute!=""){
            if($attribute=='target_constant'){
                $this->clearErrors('target_reg');
            }elseif($attribute=='source_constant'){
                $this->clearErrors('source_reg');
            }
        }

    }
    

}

?>
