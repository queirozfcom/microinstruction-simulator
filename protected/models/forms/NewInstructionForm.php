<?php

Yii::import('application.validators.*');

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
            return [
                    // username and password are required
                    ['mnemonic, source_param', 'required'],
                    ['target_constant,source_constant','numerical'],
                    ['target_constant,source_constant','nonEmptyConstant'],
                    // rememberMe needs to be a boolean
                    ['target_param_indirection, source_param_indirection', 'boolean'],
                    
                    ['target_param','validateConstant'],
                    ['target_param','presentForInstructionsThatRequireTwoParameters'],
                
                    ['source_param','validateConstantIfInstructionIsOneParameterInstruction']
            ];
    }
    /**
        * Declares attribute labels.
        */
    public function attributeLabels()
    {
            return [
                    'mnemonic'=>'Mnemonic',
                
                    'target_param_indirection'=>'',
                    'target_constant'=>'',
                    'target_param'=>'Target Parameter',
                
                    'source_param_indirection'=>'',
                    'source_constant'=>'',
                    'source_param'=>'Source parameter'
            ];
    }

    

}

?>
