<?php

/**
 * Description of validateConstant
 *
 * @author felipe
 */
class nonEmptyConstant extends CValidator {

    protected function validateAttribute($object, $attribute) {

        $paramAttribute = preg_replace('/constant/', 'param', $attribute);

        //only validate if there's not already an error message for $paramAttribute
        if (!($object->hasErrors($paramAttribute)))
            if ($object->$paramAttribute === 'CONSTANT'){
                
                if ($object->$attribute === '')
                     $object->addError($attribute, "This field cannot be blank");
            }
    }

}

?>
