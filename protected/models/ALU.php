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
            case 'S=B':
                return 2;
            case 'S=A+1':
                return 3;
            case 'S=B+1':
                return 4;
            case 'S=A+B':
                return 5;
            case 'S=A-B':
                return 6;
            case 'S=B-A':
                return 7;
            case 'S=A*B':
                return 8;
            case 'S=A/B':
                return 9;
            case 'S=AandB':
                return 10;
            case 'S=AorB';
                return 11;
            case 'S=AnandB':
                return 12;
            case 'S=AnorB':
                return 13;
            case 'S=AxorB':
                return 14;
            case 'S=AcmpB':
                return 15;
            case 'S=BcmpA':
                return 16;
            case 'S=shr':
                return 17;
            case 'S=shl':
                return 18;
            case 'S=not':
                return 19;
            default:
                throw new ALUException('Unsupported operation: ' . $operation);
        }
    }

    public static function isCMPOperation($opcode) {
        if (self::returnOpNameForOpCode($opcode) === 'S=AcmpB' || self::returnOpNameForOpCode($opcode) === 'S=BcmpA') {
            return true;
        } else {
            return false;
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
            case 6:
                return 'S=A-B';
            case 7:
                return 'S=B-A';
            case 8:
                return 'S=A*B';
            case 9:
                return 'S=A/B';
            case 10:
                return 'S=AandB';
            case 11:
                return 'S=AorB';
            case 12:
                return 'S=AnandB';
            case 13:
                return 'S=AnorB';
            case 14:
                return 'S=AxorB';
            case 15:
                return 'S=AcmpB';
            case 16:
                return 'S=BcmpA';
            case 17:
                return 'S=shr';
            case 18:
                return 'S=shl';
            case 19:
                return 'S=not';
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

        if (!$left instanceof BinaryString and !is_null($left))
            throw new ALUException(get_class($left) . " is an invalid type for ALU left-operand");

        if (!$right instanceof BinaryString and !is_null($right))
            throw new ALUException(get_class($right) . "is an invalid type for ALU right-operand");

        if (!is_null($left) and !is_null($right))
            if ($left->getLength() !== $right->getLength())
                throw new ALUException('This ALU can only operateOn two BinaryStrings if they have the same length');



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

        switch (trim($opName)) {
            case "S=A":
                return $arg1;
            case "S=B":
                return $arg2;
            case "S=A+1":
                return $this->increment($arg1);
            case "S=A+B":
                return $this->add($arg1, $arg2);
            case "S=A-B":
                return $this->subtract($arg1, $arg2);
            case "S=B-A":
                return $this->subtract($arg2, $arg1);
            case "S=A*B":
                return $this->multiply($arg1, $arg2);
            case "S=AandB":
                return $this->doAnd($arg1, $arg2);
            case "S=AorB":
                return $this->doOr($arg1, $arg2);
            case "S=AnandB":
                return $this->doNand($arg1, $arg2);
            case "S=AnorB":
                return $this->doNor($arg1, $arg2);
            case "S=AxorB":
                return $this->doXor($arg1, $arg2);
            case "S=AcmpB":
                return $this->subtract($arg1, $arg2);
            case "S=BcmpA":
                return $this->subtract($arg2, $arg1);
            case "S=shr":
                return $this->shiftRight($arg1, $arg2);
            case "S=shl":
                return $this->shiftLeft($arg1, $arg2);
            case 'S=not':
                return $this->doNot($arg1, $arg2);
            default:
                throw new ALUException("The function compute has not been defined for {$opName}.");
        }
    }

    /*
     * all functions starting here will ONLY be used as helpers to function compute()
     */

    private function increment(BinaryString $arg1) {
        return $arg1->increment();
    }

    /**
     * There are two arguments because we don't know, a priori, which of them will be the side that will get Not'd .
     * @param null | BinaryString $opt1
     * @param null | BinaryString $opt2
     */
    private function doNot($opt1, $opt2) {
        if (!($opt1 instanceof BinaryString) && !($opt2 instanceof BinaryString))
            throw new ALUException('Either $opt1 OR $opt2 need be a BinaryString, but $opt1\'s class is ' . get_class($opt1) . ' and $opt2\'s class is ' . get_class($opt2) . '.');

        if ($opt1 instanceof BinaryString) 
            return $opt1->not();
        
        if ($opt2 instanceof BinaryString) 
            return $opt2->not();
        
    }

    /**
     * There are two arguments because we don't know, a priori, which of them will be the side that will get to be shifted.
     * 
     * @param null | BinaryString $opt1
     * @param null | BinaryString $opt2
     * @throws ALUException
     */
    private function shiftRight($opt1, $opt2) {

        if (is_null($opt1) && !is_null($opt2)) {

            if (get_class($opt2) !== 'BinaryString') {
                throw new ALUException('Invalid operand of class ' . get_class($opt2));
            } else {

                return $opt2->shiftRight();
            }
        } elseif (is_null($opt2) && !is_null($opt1)) {
            if (get_class($opt1) !== 'BinaryString') {
                throw new ALUException('Invalid operand of class ' . get_class($opt1));
            } else {
                return $opt1->shiftRight();
            }
        } else {
            throw new ALUException('At least one of the arguments must be a valid BinaryString. What you tried to send was a ' . get_class($opt1) . ' for opt1 and a ' . get_class($opt2) . ' for opt2');
        }
    }

    private function shiftLeft($opt1, $opt2) {
        if (is_null($opt1) && !is_null($opt2)) {

            if (get_class($opt2) !== 'BinaryString') {
                throw new ALUException('Invalid operand of class ' . get_class($opt2));
            } else {
                return $opt2->shiftLeft();
            }
        } elseif (is_null($opt2) && !is_null($opt1)) {
            if (get_class($opt1) !== 'BinaryString') {
                throw new ALUException('Invalid operand of class ' . get_class($opt1));
            } else {
                return $opt1->shiftLeft();
            }
        } else {
            throw new ALUException('At least one of the arguments must be a valid BinaryString. What you tried to send was a ' . get_class($opt1) . ' for opt1 and a ' . get_class($opt2) . ' for opt2');
        }
    }

    private function add(BinaryString $arg1, BinaryString $arg2) {

        $length = $arg1->getLength();

        //performs add on two binary strings
        $intval1 = $arg1->asInt();
        $intval2 = $arg2->asInt();

        $intSum = $intval1 + $intval2;

        $bs = new BinaryString($length, $intSum);

        return $bs;
    }

    private function subtract(BinaryString $arg1, BinaryString $arg2) {

        $length = $arg1->getLength();

        //performs add on two binary strings
        $intval1 = $arg1->asInt();
        $intval2 = $arg2->asInt();

        $intDifference = $intval1 - $intval2;

        $bs = new BinaryString($length, $intDifference);

        return $bs;
    }

    private function multiply(BinaryString $arg1, BinaryString $arg2) {

        $length = $arg1->getLength();

        //performs add on two binary strings
        $intval1 = $arg1->asInt();
        $intval2 = $arg2->asInt();

        $intProduct = $intval1 * $intval2;

        $bs = new BinaryString($length, $intProduct);

        return $bs;
    }

    /**
     * and is a reserved word for php!!
     * @param BinaryString $arg1
     * @param BinaryString $arg2
     * @return \BinaryString
     */
    public function doAnd(BinaryString $arg1, BinaryString $arg2) {
        return $arg1->andWith($arg2);
    }

    public function doOr(BinaryString $arg1, BinaryString $arg2) {
        return $arg1->orWith($arg2);
    }

    public function doNand(BinaryString $arg1, BinaryString $arg2) {
        return $arg1->nandWith($arg2);
    }

    public function doNor(BinaryString $arg1, BinaryString $arg2) {
        return $arg1->norWith($arg2);
    }

    public function doXor(BinaryString $arg1, BinaryString $arg2) {
        return $arg1->xorWith($arg2);
    }

    /*
     * END
     */
}

?>