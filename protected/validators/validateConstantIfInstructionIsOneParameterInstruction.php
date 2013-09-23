<?php

/**
 * Description of validateConstantIfInstructionIsOneParameterInstruction
 *
 * @author felipe
 */
class validateConstantIfInstructionIsOneParameterInstruction extends CValidator {

    //if there's only one parameter, then it shouldn't be a direct constant either. Jumps are exceptions.
    protected function validateAttribute($object, $attribute) {
        $mnemonic = mb_strtoupper($object->mnemonic);
  
        if($this->requiresOnlyOneArgument($mnemonic))
            if($this->isNotJump($mnemonic))
                if($object->$attribute==='CONSTANT')
                    if($object->source_param_indirection==="0")
                        $object->addError($attribute,"Direct constants cannot be used here.");
    }

    private function requiresOnlyOneArgument($mnemonic) {
        $arr = [
            'CLR',
            'NOT',
            'NEG',
            'SHL',
            'SHR',
            'BRZ',
            'BRN',
            'BRE',
            'BRL',
            'BRG',
            'RJMP'
        ];
        if (in_array(strtoupper($mnemonic), $arr))
            return true;
        else
            return false;
    }
    
    private function isNotJump($mnemonic){
        
        $mnemonic = mb_strtoupper($mnemonic);
        
        return ($mnemonic !== 'BRZ' &&
                $mnemonic !== 'BRN' &&
                $mnemonic !== 'BRE' &&
                $mnemonic !== 'BRL' &&
                $mnemonic !== 'BRG' &&
                $mnemonic !== 'RJMP');
    }

}

?>
