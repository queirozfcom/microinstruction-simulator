<?php

class Instruction extends BinaryString {

    /**
     * @var array
     * @static 
     */
    protected static $validMnemonics = [
        'MOV',
        'ADD',
        'SUB',
        'AND',
        'OR',
        'NAND',
        'NOR',
        'XOR',
        'CMP',
        'CLR',
        'NOT',
        'SHL',
        'SHR',
        'BRZ',
        'BRN',
        'BRE',
        'BRL',
        'BRG',
        'RJMP'
    ];
    protected $length = 32;
    protected $string;
    //switch to private upon end of testing

    private $param1 = null;
    private $indirection1 = false;
    private $param2 = null;
    private $indirection2 = false;
    private $mnemonic;

    public static function getValidInstructions() {
        $output = [];
        foreach (self::$validMnemonics as $mnemonic) {
            $output[$mnemonic] = $mnemonic;
        }
        return $output;
    }

    /**
     * 
     * @param type $mnem
     * @param type $param1
     * @param type $ind1
     * @param type $param2
     * @param type $ind2
     * @throws InstructionException
     */
    public function __construct() {

        if (func_num_args() === 5) {
            //normal usage
            $this->setIntegerValue(0);
            $this->setMnemonic(func_get_arg(0));
            $this->setParam1(func_get_arg(1));
            $this->setIndirection1(func_get_arg(2));
            $this->setParam2(func_get_arg(3));
            $this->setIndirection2(func_get_arg(4));

            if (is_null($this->param2) && $this->requiresTwoArguments())
                throw new InstructionException('Missing argument 2 for instruction that needs 2 arguments');

            if ($this->param2 === "CONSTANT" && !$this->indirection2 && !$this->isBranch())
                throw new InstructionException('Cannot use a direct CONSTANT as target for an Instruction.');
        }elseif (func_num_args() === 2) {
            //branch-type instructions
            //normal usage
            $this->setIntegerValue(0);

            $this->setMnemonic(func_get_arg(0));
            $this->setParam1('constant');
            $this->setBranchOffset(func_get_arg(1));
        }
    }

    public function getLength() {
        return $this->length;
    }

    public function getParam1() {
        return $this->param1;
    }

    public function getIndirection1() {
        return $this->indirection1;
    }

    public function getParam2() {
        return $this->param2;
    }

    public function getIndirection2() {
        return $this->indirection2;
    }

    public function getMnemonic() {
        return $this->mnemonic;
    }

    public function humanReadableForm() {
        $output = "";
        $output .= $this->mnemonic;
        $output .= "(";
        $output .= $this->indirection1 ? "[" : "";
        $output .= ($this->param1 === "CONSTANT") ? "#" . $this->param1 : $this->param1;
        $output .= $this->indirection1 ? "]" : "";

        if (is_null($this->param2)) {
            $output .= ")";
            return $output;
        }

        $output .= ",";
        $output .= $this->indirection2 ? "[" : "";
        $output .= ($this->param2 === "CONSTANT") ? "#" . $this->param2 : $this->param2;
        $output .= $this->indirection2 ? "]" : "";
        $output .= ")";

        return $output;
    }

    public function hasConstant() {
        if (($this->param1 === 'CONSTANT') || ($this->param2 === 'CONSTANT'))
            return true;
        else
            return false;
    }

    private function requiresTwoArguments() {
        return !$this->requiresOnlyOneArgument();
    }

    public function hasIndirection() {
        return $this->indirection1 || $this->indirection2;
    }

    public function isBranch() {
        if ($this->mnemonic === 'BRZ' || $this->mnemonic === 'BRN' || $this->mnemonic === 'BRE' || $this->mnemonic === 'BRL' || $this->mnemonic === 'BRG' || $this->mnemonic === 'RJMP')
            return true;
        else
            return false;
    }

    public function getBranchOffset() {
        $negativeOffset = $this->getIntValueStartingAt(7, 14);
        $positiveOffset = $this->getIntValueStartingAt(7, 0);

        if ($negativeOffset !== 0 && $positiveOffset !== 0)
            throw new InstructionException('A Branch Instruction should have either a positive offset or a negative offset. We found a Instruction that has both. Negative offset=' . $negativeOffset . ' and positive offset=' . $positiveOffset . '.');

        if ($negativeOffset === 0)
            return $positiveOffset;
        elseif ($positiveOffset === 0)
            return $negativeOffset * (-1);
        else
            throw new InstructionException('One branch offset should be a nonzero integer and the other should be zero');
    }

    private function requiresOnlyOneArgument() {
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
        if (in_array(strtoupper($this->mnemonic), $arr))
            return true;
        else
            return false;
    }

    private function setBranchOffset($offset) {
        if (!is_int($offset) && is_numeric($offset))
            $offset = intval($offset);

        if ($offset === 0)
            throw new InstructionException('Nonzero Integer needed. ' . $offset . ' found.');

        if ($offset < 0)
            $this->setIntValueStartingAt($offset * (-1), 7, 14);
        elseif ($offset > 0)
            $this->setIntValueStartingAt($offset, 7, 0);
    }

    private function setIndirection1($value) {
        if (!is_bool($value)) {
            throw new InstructionException('indirection1 value must be a boolean');
        }
        if ($value) {
            $this->indirection1 = true;
            $this->setOne(12);
        } else {
            $this->indirection1 = false;
            $this->setZero(12);
        }
    }

    private function setIndirection2($value) {
        if (!is_bool($value)) {
            throw new InstructionException('indirection2 value must be a boolean');
        }
        if ($value) {
            $this->indirection2 = true;
            $this->setOne(18);
        } else {
            $this->indirection2 = false;
            $this->setZero(18);
        }
    }

    private function setParam1($param) {
        $this->param1 = strtoupper($param);

        switch (strtoupper($param)) {
            case "R0":
                $this->setIntValueStartingAt(1, 5, 7);
                break;
            case "R1":
                $this->setIntValueStartingAt(2, 5, 7);
                break;
            case "R2":
                $this->setIntValueStartingAt(3, 5, 7);
                break;
            case "R3":
                $this->setIntValueStartingAt(4, 5, 7);
                break;
            case "R4":
                $this->setIntValueStartingAt(5, 5, 7);
                break;
            case "AR1":
                $this->setIntValueStartingAt(6, 5, 7);
                break;
            case "AR2":
                $this->setIntValueStartingAt(7, 5, 7);
                break;
            case "CONSTANT":
                $this->setIntValueStartingAt(8, 5, 7);
                break;
            case "MAR":
                $this->setIntValueStartingAt(9, 5, 7);
                break;
            case "MDR":
                $this->setIntValueStartingAt(10, 5, 7);
                break;
            default:
                throw new InstructionException('Invalid Param1: ' . $param);
        }
    }

    private function setParam2($param) {
        $this->param2 = strtoupper($param);
        switch (strtoupper($param)) {
            case "R0":
                $this->setIntValueStartingAt(1, 5, 13);
                break;
            case "R1":
                $this->setIntValueStartingAt(2, 5, 13);
                break;
            case "R2":
                $this->setIntValueStartingAt(3, 5, 13);
                break;
            case "R3":
                $this->setIntValueStartingAt(4, 5, 13);
                break;
            case "R4":
                $this->setIntValueStartingAt(5, 5, 13);
                break;
            case "AR1":
                $this->setIntValueStartingAt(6, 5, 13);
                break;
            case "AR2":
                $this->setIntValueStartingAt(7, 5, 13);
                break;
            case "CONSTANT":
                $this->setIntValueStartingAt(8, 5, 13);
                break;
            case "MDR":
                $this->setIntValueStartingAt(9, 5, 13);
                break;
            case "MAR":
                $this->setIntValueStartingAt(10, 5, 13);
                break;
            case null:
                $this->param2 = null;
                break;
            default:
                throw new InstructionException('Invalid Param2: ' . $param);
        }
    }

    private function setMnemonic($mnemonic) {
        $this->mnemonic = strtoupper($mnemonic);
        switch (strtoupper($mnemonic)) {
            case "MOV":
                $this->setIntValueStartingAt(1, 6, 26);
                break;
            case "ADD":
                $this->setIntValueStartingAt(2, 6, 26);
                break;
            case "SUB":
                $this->setIntValueStartingAt(3, 6, 26);
                break;
            case "MUL":
                $this->setIntValueStartingAt(4, 6, 26);
                break;
            case "DIV":
                $this->setIntValueStartingAt(5, 6, 26);
                break;
            case "AND":
                $this->setIntValueStartingAt(6, 6, 26);
                break;
            case "OR":
                $this->setIntValueStartingAt(7, 6, 26);
                break;
            case "NAND":
                $this->setIntValueStartingAt(8, 6, 26);
                break;
            case "NOR":
                $this->setIntValueStartingAt(9, 6, 26);
                break;
            case "XOR":
                $this->setIntValueStartingAt(10, 6, 26);
                break;
            case "CMP":
                $this->setIntValueStartingAt(11, 6, 26);
                break;
            case "CLR":
                $this->setIntValueStartingAt(12, 6, 26);
                break;
            case "NOT":
                $this->setIntValueStartingAt(13, 6, 26);
                break;
            case "SHL":
                $this->setIntValueStartingAt(14, 6, 26);
                break;
            case "SHR":
                $this->setIntValueStartingAt(15, 6, 26);
                break;
            case "BRZ":
                $this->setIntValueStartingAt(16, 6, 26);
                break;
            case "BRN":
                $this->setIntValueStartingAt(17, 6, 26);
                break;
            case "BRE":
                $this->setIntValueStartingAt(18, 6, 26);
                break;
            case "BRL":
                $this->setIntValueStartingAt(19, 6, 26);
                break;
            case "BRG":
                $this->setIntValueStartingAt(20, 6, 26);
                break;
            case "NEG":
                $this->setIntValueStartingAt(21, 6, 26);
                break;
            case "RJMP":
                $this->setIntValueStartingAt(22, 6, 26);
                break;
            default:
                throw new InstructionException('Invalid Mnemonic for this machine : "' . strtoupper($mnemonic) . '".');
        }
    }

}

?>