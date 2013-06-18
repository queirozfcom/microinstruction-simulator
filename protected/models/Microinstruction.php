<?php

class Microinstruction extends BinaryString {

    /**
     * Will construct a default Microinstruction. All 0's.
     */
    public function __construct() {
        parent::__construct();

        if (func_num_args() === 1 and is_string(func_get_arg(0)) and !is_numeric(func_get_arg(0))) {
            $this->initAlias(func_get_arg(0));
        }
    }

    private function initAlias($alias) {
        switch ($alias) {
            case 'data_to_mdr':
                $this[22] = 1;
                $this[24] = 1;
                break;
            case 'increment_pc':
                $this[14] = 1;
                $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A+1'), 8, 0);
                $this[10] = 1;
                break;
            case 'pc_to_mar_read':
                $this[14] = 1;
                $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'), 8, 0);
                $this[27] = 1;
                $this[25] = 1;
                break;
            case 'mdr_to_mar_read':
                $this[12] = 1;
                $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'), 8, 0);
                $this[27] = 1;
                $this[25] = 1;
                break;
            case 'mdr_to_mar':
                $this[12] = 1;
                $this[0] = 1;
                $this[27] = 1;
                break;
            case 'mdr_to_ir':
                $this[12] = 1;
                $this[0] = 1;
                $this[28] = 1;
                break;
            default:
                throw new MicroinstructionException('Unsupported alias: ' . $alias);
        }
    }

    public function getTargetRegIndex() {
        $targetRegisterIndexes = [27, 28, 8, 9, 10, 11, 15, 16, 17, 18];

        foreach ($targetRegisterIndexes as $index) {
            if ($this[$index] === '1')
                return $index;
        }

        if ($this[24] === '1') {
            
            if (($this[23] === '1') && ($this[22] === '0'))
                return 24;
            else
                throw new MicroinstructionException('You\'re trying to send a value to MDR but you haven\'t set MUX C value accordingly. If this is an operation of type LOAD DATA TO MDR, you should handle that first in  Program::runMicroinstruction() function ');
        }
    }

    public function setWrite() {
        $this[26] = 1;
        $this[25] = 0;
    }

    public function setRead() {
        $this[26] = 0;
        $this[25] = 1;
    }

    public function setMuxAndALUValueForMOVFromSourceRegister($regName) {
        $sideSource = Decoder::getSideFromSourceName($regName);

        $muxVal = Decoder::getMUXValueFromRegister($regName);

        if ($sideSource == 'B') {
            $this[21] = $muxVal{0};
            $this[20] = $muxVal{1};
            $this[19] = $muxVal{2};

            $intALUOpCode = ALU::returnOpCodeForOperation('S=B');
        } elseif ($sideSource == 'A') {
            $this[14] = $muxVal{0};
            $this[13] = $muxVal{1};
            $this[12] = $muxVal{2};

            $intALUOpCode = ALU::returnOpCodeForOperation('S=A');
        }
        else
            throw new DecoderException('Unsupported side. Must be either A or B.');

        $this->setIntValueStartingAt($intALUOpCode, 8, 0);
    }

    public function setMuxAndALUValue($mnemonic, $sourceRegister, $targetRegister) {

        $sourceSide = Decoder::getSideFromSourceName($sourceRegister);
        $targetSide = Decoder::getSideFromSourceName($targetRegister);

        if ($sourceSide === $targetSide)
            throw new MicroinstructionException('Can\'t add two registers that are on the same side. Please move one of them to an Auxiliary Register (AR1 or AR2)');

        $sourceMuxVal = Decoder::getMUXValueFromRegister($sourceRegister);
        $targetMuxVal = Decoder::getMUXValueFromRegister($targetRegister);

//        print $sourceMuxVal. "\n";
//        print $targetMuxVal . "\n";

        if ($targetSide == 'B') {
            $this[21] = $targetMuxVal{0};
            $this[20] = $targetMuxVal{1};
            $this[19] = $targetMuxVal{2};

            $this[14] = $sourceMuxVal{0};
            $this[13] = $sourceMuxVal{1};
            $this[12] = $sourceMuxVal{2};
        } else {
            $this[14] = $targetMuxVal{0};
            $this[13] = $targetMuxVal{1};
            $this[12] = $targetMuxVal{2};

            $this[21] = $sourceMuxVal{0};
            $this[20] = $sourceMuxVal{1};
            $this[19] = $sourceMuxVal{2};
        }

        if (strtoupper($mnemonic) == 'ADD')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=A+B');
        elseif (strtoupper($mnemonic) == "SUB") {
            if ($targetSide == 'B')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=B-A');
            else
                $intALUOpCode = ALU::returnOpCodeForOperation('S=A-B');
        } elseif (strtoupper($mnemonic) == 'MUL')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=A*B');
        elseif (strtoupper($mnemonic) == 'AND')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=AandB');
        elseif (strtoupper($mnemonic) == 'OR')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=AorB');
        elseif (strtoupper($mnemonic) == 'NAND')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=AnandB');
        elseif (strtoupper($mnemonic) == 'NOR')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=AnorB');
        elseif (strtoupper($mnemonic) == 'XOR')
            $intALUOpCode = ALU::returnOpCodeForOperation('S=AxorB');
        elseif (strtoupper($mnemonic) == 'CMP') {
            if ($targetSide == 'B')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=BcmpA');
            else
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AcmpB');
        }
        else
            throw new MicroinstructionException('Invalid operation: ' . $mnemonic);


        $this->setIntValueStartingAt($intALUOpCode, 8, 0);
    }

    /**
     * 
     * @throws MicroinstructionException
     */
    public function setMuxAndALUValueFromSourceRegisterAndMnemonic() {
        if (func_num_args() === 3) {

            throw new Exception('Use Microinstruction::setMuxAndALUValue method instead');

            $mnemonic = func_get_arg(0);
            $sourceReg = func_get_arg(1);
            $targetReg = func_get_arg(2);

            $targetSide = Decoder::getSideFromSourceName($targetReg);
            $sourceSide = Decoder::getSideFromSourceName($sourceReg);

            if ($sourceSide === $targetSide)
                throw new MicroinstructionException('Can\'t add two registers that are on the same side. Please move one of them to an Auxiliary Register (AR1 or AR2)');


            $targetMuxVal = Decoder::getMUXValueFromRegister($targetReg);
            $sourceMuxVal = Decoder::getMUXValueFromRegister($sourceReg);

            if ($targetSide == 'B') {
                $this[21] = $targetMuxVal{0};
                $this[20] = $targetMuxVal{1};
                $this[19] = $targetMuxVal{2};

                $this[14] = $sourceMuxVal{0};
                $this[13] = $sourceMuxVal{1};
                $this[12] = $sourceMuxVal{2};
            } else {
                $this[14] = $targetMuxVal{0};
                $this[13] = $targetMuxVal{1};
                $this[12] = $targetMuxVal{2};

                $this[21] = $sourceMuxVal{0};
                $this[20] = $sourceMuxVal{1};
                $this[19] = $sourceMuxVal{2};
            }
            if (strtoupper($mnemonic) == 'ADD') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=A+B');
            } elseif (strtoupper($mnemonic) == "SUB") {
                if ($targetSide == 'B') {
                    $intALUOpCode = ALU::returnOpCodeForOperation('S=B-A');
                } else {
                    $intALUOpCode = ALU::returnOpCodeForOperation('S=A-B');
                }
            } elseif (strtoupper($mnemonic) == 'MUL') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=A*B');
            } elseif (strtoupper($mnemonic) == 'AND') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AandB');
            } elseif (strtoupper($mnemonic) == 'OR') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AorB');
            } elseif (strtoupper($mnemonic) == 'NAND') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AnandB');
            } elseif (strtoupper($mnemonic) == 'NOR') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AnorB');
            } elseif (strtoupper($mnemonic) == 'XOR') {
                $intALUOpCode = ALU::returnOpCodeForOperation('S=AxorB');
            } elseif (strtoupper($mnemonic) == 'CMP') {
                if ($targetSide == 'B') {
                    $intALUOpCode = ALU::returnOpCodeForOperation('S=BcmpA');
                } else {
                    $intALUOpCode = ALU::returnOpCodeForOperation('S=AcmpB');
                }
            } else {
                throw new MicroinstructionException('Invalid operation: ' . $mnemonic);
            }

            $this->setIntValueStartingAt($intALUOpCode, 8, 0);
        } elseif (func_num_args() === 2) {

            $reg = func_get_arg(0);
            $mnemonic = func_get_arg(1);

            $side = Decoder::getSideFromSourceName($reg);

            $sourceMuxVal = Decoder::getMUXValueFromRegister($reg);


            if ($side == 'B') {
                $this[21] = $sourceMuxVal{0};
                $this[20] = $sourceMuxVal{1};
                $this[19] = $sourceMuxVal{2};

                $this[14] = 0;
                $this[13] = 0;
                $this[12] = 0;
            } else {
                $this[14] = $sourceMuxVal{0};
                $this[13] = $sourceMuxVal{1};
                $this[12] = $sourceMuxVal{2};

                $this[21] = 0;
                $this[20] = 0;
                $this[19] = 0;
            }

            if (strtoupper($mnemonic) === 'SHR')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=shr');
            elseif (strtoupper($mnemonic) === 'SHL')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=shl');
            elseif (strtoupper($mnemonic) === 'NOT')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=not');
            elseif (strtoupper($mnemonic) === 'NEG')
                $intALUOpCode = ALU::returnOpCodeForOperation('S=neg');
            elseif (strtoupper($mnemonic) === 'CLR')
                $intALUOpCode = ALU::returnOpCodeForOperation ('S=clr');
            else
                throw new MicroinstructionException('Invalid operation: ' . $mnemonic);


            $this->setIntValueStartingAt($intALUOpCode, 8, 0);
        }
    }

    public function setTargetIndexFromTargetRegister($regname) {
        if (!is_string($regname))
            throw new MicroinstructionException('Register names must be strings.');

        switch (strtoupper($regname)) {
            case 'R0':
                $this[8] = 1;
                break;
            case 'R1':
                $this[9] = 1;
                break;
            case 'PC':
                $this[10] = 1;
                break;
            case 'AR1':
                $this[11] = 1;
                break;
            case 'R2':
                $this[15] = 1;
                break;
            case 'R3':
                $this[16] = 1;
                break;
            case 'R4':
                $this[17] = 1;
                break;
            case 'AR2':
                $this[18] = 1;
                break;
            case 'MAR':
                $this[27] = 1;
                break;
            case 'IR':
                $this[28] = 1;
                break;
            case 'MDR':
                $this->setOne(23, 24);
                $this->setZero(22);
                break;
            default:
                throw new DecoderException('Unsupported register name: ' . $regname);
        }
    }

    public function isMemoryRead() {
        if ($this[26] == 0 and $this[25] == 1)
            return true;
        else
            return false;
    }

    public function isMemoryWrite() {
        if ($this[26] == 1 and $this[25] == 0)
            return true;
        else
            return false;
    }

    public function isLoadReadDataIntoMDR() {
        
    }

    public function getALUOperationCode() {
        return $this->getIntValueStartingAt(8, 0);
    }

    public function getMUXAValue() {
        return $this[14] . $this[13] . $this[12];
    }

    public function getMUXBValue() {
        return $this[21] . $this[20] . $this[19];
    }

    public function setBranchType($type) {
        if (!is_string($type))
            throw new MicroinstructionException('"' . $type . '" is not a valid branch type for a microinstruction.');
        if (!(mb_strlen($type) === 3 || mb_strlen($type) === 1 || mb_strlen($type) === 4 ))
            throw new MicroinstructionException('Only 1- or 3-letter branch types are allowed (the single letter or the 3-letter mnemonic). Given: "' . $type . '".');

        switch (mb_strtoupper($type, 'UTF-8')) {
            case 'Z':
            case 'BRZ':
                $this->setIntValueStartingAt(90, 8, 0);
                break;
            case 'N':
            case 'BRN':
                $this->setIntValueStartingAt(91, 8, 0);
                break;
            case 'E':
            case 'BRE':
                $this->setIntValueStartingAt(92, 8, 0);
                break;
            case 'L':
            case 'BRL':
                $this->setIntValueStartingAt(93, 8, 0);
                break;
            case 'G':
            case 'BRG':
                $this->setIntValueStartingAt(94, 8, 0);
                break;
            case 'R':
            case 'RJMP':
                $this->setIntValueStartingAt(95, 8, 0);
                break;
            default:
                throw new MicroinstructionException('Invalid branch type found: "' . $type . '".');
                break;
        }
    }

    public function getBranchFlagType() {
        $branchIntValue = $this->getIntValueStartingAt(8, 0);

        if ($branchIntValue < 90 || $branchIntValue > 95)
            throw new MicroinstructionException('Invalid value for branch int value: ' . $branchIntValue . '. Allowed values are from 90 to 95 inclusive');

        switch ($branchIntValue) {
            case 90:
                return 'Z';
            case 91:
                return 'N';
            case 92:
                return 'E';
            case 93:
                return 'L';
            case 94:
                return 'G';
            case 95:
                return 'R';
            default:
                throw new MicroinstructionException('Invalid value for branch int value: ' . $branchIntValue . '. Allowed values are from 90 to 95 inclusive');
        }
    }

    public function isBranch() {
        if (($this->getIntValueStartingAt(8, 0) >= 90) && ($this->getIntValueStartingAt(8, 0) <= 95))
            return true;
        else
            return false;
    }

    public function getBranchOffset() {
        $negativeOffset = $this->getIntValueStartingAt(7, 15);
        $positiveOffset = $this->getIntValueStartingAt(7, 8);

        if ($negativeOffset !== 0 && $positiveOffset !== 0)
            throw new MicroinstructionException('A Branch microinstruction should have either a positive offset or a negative offset. We found a Microinstruction that has both. Negative offset=' . $negativeOffset . ' and positive offset=' . $positiveOffset . '.');

        if ($negativeOffset === 0)
            return $positiveOffset;
        elseif ($positiveOffset === 0)
            return $negativeOffset * (-1);
        else
            throw new MicroinstructionException('One branch offset should be a nonzero integer and the other should be zero');
    }

    public function setBranchOffset($offset) {
        if (is_numeric($offset) && !is_int($offset))
            $offset = intval($offset);

        if ($offset > 0)
            $this->setIntValueStartingAt($offset, 7, 8);
        elseif ($offset < 0)
            $this->setIntValueStartingAt($offset * (-1), 7, 15);
        else
            throw new MicroinstructionException('Zero offset? What be this madness?');
    }

//    public function isMUXAActive() {
//        //returns true if MUX A value is != 0
//    }
//
//    public function isMUXBActive() {
//        
//    }
}

?>