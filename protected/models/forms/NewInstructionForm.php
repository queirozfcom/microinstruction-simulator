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
    
    public $target_param_indirection;
    public $target_constant;
    public $target_param;
    
    public $source_param_indirection;
    public $source_constant;
    public $source_param;
    
    public function rules()
    {
            return array(
                    // username and password are required
                    array('mnemonic, target_param', 'required'),
                    array('target_constant,source_constant','numerical'),
                    // rememberMe needs to be a boolean
                    array('target_param_indirection, source_param_indirection', 'boolean'),
                
       
            );
    }
    /**
        * Declares attribute labels.
        */
    public function attributeLabels()
    {
            return array(
                    'mnemonic'=>'Mnemonic',
                
                    'target_param_indirection'=>'',
                    'target_constant'=>'',
                    'target_param'=>'Target Parameter',
                
                    'source_param_indirection'=>'',
                    'source_constant'=>'',
                    'source_param'=>'Source parameter'
            );
    }

    

}

?>
