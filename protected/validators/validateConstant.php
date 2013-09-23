<?php

/**
 * Description of validateConstant
 *
 * @author felipe
 */
class validateConstant extends CValidator {

    protected function validateAttribute($object, $attribute) {
        if ($object->target_param_indirection === '0')
            if ($object->$attribute === 'CONSTANT')
                $object->addError($attribute, 'Direct Constants cannot be used as the target for an Instruction.');
            
//        $constantParamName = preg_replace('/_param/', '_constant', $attribute);
//        
//        $object->clearErrors($constantParamName);
            
    }

}

?>
