<?php

require_once "helpers/PrimitiveFunctions.php";
require_once 'helpers/BinaryString.php';

class ALU {

    public function __construct() {
        
    }

    public static function returnOpCodeForOperation($operation) {
        if (!is_string($operation)) {
            throw new ALUException('Operation must be a valid string');
        }
        switch ($operation) {
            case 'S=A':
                return 1;
                break;
            case 'S=B':
                return 2;
                break;
            case 'S=A+1':
                return 3;
                break;
            case 'S=B+1':
                return 4;
                break;
            default:
                throw new ALUException('Unsupported operation: ' . $operation);
        }
    }

    private static function returnOpNameForOpCode($opcode) {
        switch ($opcode) {
            case 1:
                return 'S=A';
            case 2:
                return 'S=B';
            case 3:
                return 'S=A+1';
            case 4:
                return 'S=B+1';
            case 5:
                return 'S=A+B';
            default:
                throw new ALUException("$opcode is not a valid operation code for this ALU");
        }
    }

    /**
     * IMPORTANT: THIS WILL RETURN EITHER A BINARYSTRING OR AN INSTRUCTION!!
     * 
     * @param BinaryString $left
     * @param BinaryString $right
     * @param String $opCode
     */
    public function operateOn($left, $right, $opCode) {
        if (!$left instanceof BinaryString and !is_null($left)) {
            throw new ALUException(get_class($left) . " is an invalid type for ALU left-operand");
        }
        if (!$right instanceof BinaryString and !is_null($right)) {
            throw new ALUException(get_class($right) . "is an invalid type for ALU right-operand");
        }
        if(!is_null($left) and !is_null($right)){
            if($left->getLength()!==$right->getLength()){
                throw new ALUException('This ALU can only operateOn two BinaryStrings if they have the same length');
            }
        }
        
        $opname = self::returnOpNameForOpCode($opCode);
        
        return $this->compute($opname, $left, $right);
    }

    /**
     * Performs an operation on the arguments and returns the result 
     *
     * @param String $operation
     * @param BinaryString|Instruction arg1
     * @param BinaryString|Instruction arg2
     * 
     * @return BinaryString|Instruction 
     * 
     * @example compute("S=A+B" , BinaryString , BinaryString)
     * @example compute("S=A-B" , BinaryString , Instruction)
     * @example compute("S=A" , Instruction , BinaryString) ->will just output whatever was input for $arg1
     * @example compute("S=B" , ditto , ditto) ->will just output whatever was input for $arg2
     */
    private function compute($opName, $arg1, $arg2) {
        $upper = strtoupper(trim($opName));

        switch ($upper) {
            case "S=A":
                return $arg1;
            case "S=B":
                return $arg2;
            case "S=A+1":
                return $this->increment($arg1);
            case "S=A+B":
                return $this->add($arg1, $arg2);
            default:
                throw new ALUException("$upper is not a valid Operation Name for this ALU");
        }
    }

    /*
     * all functions starting here will ONLY be used as helpers to function compute()
     */

    private function increment($arg1) {
        return $arg1->increment();
    }

    private function add($arg1, $arg2) {
        
        $length = $arg1->getLength();
        
        //performs add on two binary strings
        $intval1 = $arg1->asInt();
        $intval2 = $arg2->asInt();
        
        $intSum = $intval1+$intval2;
        
        $bs = new BinaryString($length,$intSum);
        
        return $bs;
    }

    /*
     * END
     */
}

?>