<?php
/**
 * Description of presentForInstructionsThatRequireTwoParameters
 *
 * @author felipe
 */
class presentForInstructionsThatRequireTwoParameters extends CValidator{
    protected function validateAttribute($object, $attribute) {
        $mnemonic = mb_strtoupper($object->mnemonic);
        
        if($mnemonic=='')
            return;
        else{
            if($object->$attribute === "")
                if(!$this->requiresOnlyOneArgument($mnemonic))
                    $object->addError($attribute,'This parameter must be set for this Instruction.');
                
        }
        
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
}

?>
