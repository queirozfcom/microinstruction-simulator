<?php

class Decoder {

    /**
     *  This is the main entry point for this class.
     *  
     *  For a given (macro)Assembler-level instruction, return the required steps (microinstructions)
     *  to execute it.
     *  
     * @param Instruction|String This is usually an object of type BinaryString (which can be resolved to an instruction)
     *  but, in special circumstances, such as for commonly used routines (instruction 
     *  fetching, PC upgrade,and so on) a String alias can be used.
     * 
     * @param Array an optional array of flags. Some instructions need flags 
     * 
     * @return array A Microinstruction array, i.e. a microProgram
     */
    public function decode($instruction) {
        $returnMicroprogram = array();
        if (is_string($instruction)) {
            $returnMicroprogram = $this->getMicroprogramFromAlias($instruction);
        } elseif ($instruction instanceof Instruction) {
            $returnMicroprogram = $this->getMicroprogramFromInstruction($instruction);
        }
        return $returnMicroprogram;
    }

    /**
     * For a given String $alias, returns a Microinstruction array, i.e. a microprogram.
     * @param String $alias
     */
    private function getMicroprogramFromAlias($alias) {
        $returnMicroprogram = array();

        if ($alias === "fetch") {
            /*
             * "fetch" means the following:
             *  MAR <- PC ,MemoryRead;
             *  MDR <- DataReturnedByMemory;
             *  IR  <- MDR;
             */
            $tempMicro = new Microinstruction();
            $tempMicro->setOne(14);
            $tempMicro->setOne(0);
            $tempMicro->setOne(27);
            $tempMicro->setOne(25);
            $returnMicroprogram[] = $tempMicro; //MAR<-PC
            unset($tempMicro);

            $tempMicro = new Microinstruction();
            $tempMicro->setOne(22);
            $tempMicro->setOne(24);
            $returnMicroprogram[] = $tempMicro; //MDR<-Read data
            unset($tempMicro);

            $tempMicro = new Microinstruction();
            $tempMicro->setOne(12);
            $tempMicro->setOne(0);
            $tempMicro->setOne(28);
            $returnMicroprogram[] = $tempMicro; //IR<-MDR
        }
        return $returnMicroprogram;
    }

    /**
     * 
     * Tests what kind of Instruction it was sent. Then redirects to the correct method
     * to decode that particular kind (mov,add,sub, etc) of Instruction, and then returns the Microprogram.
     * @param Instruction $inst
     * 
     * @return Array Microprogram -> an Array of Microinstructions
     */
    private function getMicroprogramFromInstruction(Instruction $inst) {
        
        
        if ($inst->getMnemonic() == 'MOV') {
            return $this->decodeMOVInstruction($inst);
        } elseif ($inst->getMnemonic() === 'ADD' || $inst->getMnemonic() === "SUB" ||
                $inst->getMnemonic() === "MUL" || $inst->getMnemonic() === "AND" ||
                $inst->getMnemonic() === "OR" || $inst->getMnemonic() === "NAND" ||
                $inst->getMnemonic() === "NOR" || $inst->getMnemonic() === "XOR" ||
                $inst->getMnemonic() === "CMP") {
            return $this->decodeArithmeticInstruction($inst);
        } elseif ($inst->getMnemonic() === 'SHL' || $inst->getMnemonic() === 'SHR' || $inst->getMnemonic() === 'NOT' ) {
            return $this->decodeOneOperandInstruction($inst);
        } elseif($inst->getMnemonic()==='BRZ' || $inst->getMnemonic()==='BRN' || 
                $inst->getMnemonic()==='BRE' || $inst->getMnemonic()==='BRL' || 
                $inst->getMnemonic()==='BRG') {
            return $this->decodeBranchInstruction($inst);
        }
        else
            throw new DecoderException('not able to decode instruction whose mnemonic is ' . $inst->getMnemonic());
        
    }
    
    private function decodeBranchInstruction(Instruction $inst){
        
        $returnMicroprogram=[];
        if ($inst->isBranch() && $inst->hasConstant()){
            
            //just one micro. A special kind of micro.
            $branchType = $inst->getMnemonic();
            $offset = $inst->getBranchOffset();
            
            $m = new Microinstruction();
            
            $m->setBranchType($branchType);
            $m->setBranchOffset($offset);
            
            $returnMicroprogram[] = $m;
        }
       
        return $returnMicroprogram;
    }
    
    private function decodeOneOperandInstruction(Instruction $inst) {
        $returnMicroprogram = [];

        if ($inst->hasIndirection()) {
            if ($inst->hasConstant()) {
                $returnMicroprogram[] = new Microinstruction('increment_pc');
                $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                $mi = new Microinstruction;
                $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                $mi->setTargetIndexFromTargetRegister('AR2');

                $returnMicroprogram[] = $mi;

                $mi = new Microinstruction();
                $mi->setMuxAndALUValueFromSourceRegisterAndMnemonic('AR2', $inst->getMnemonic());
                $mi->setTargetIndexFromTargetRegister('AR2');

                $returnMicroprogram[] = $mi;

                //now place the value back into the right register, I mean, memory line

                $mi = new Microinstruction;
                $mi->setMuxAndALUValueForMOVFromSourceRegister('AR2');
                $mi->setTargetIndexFromTargetRegister('MDR');
                $mi->setWrite();

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            } else {
                $mi = new Microinstruction();
                $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam1());
                $mi->setTargetIndexFromTargetRegister('MAR');
                $mi->setRead();

                $returnMicroprogram[] = $mi;

                $returnMicroprogram[] = new Microinstruction('data_to_mdr');


                $mi = new Microinstruction;
                $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                $mi->setTargetIndexFromTargetRegister('AR2');

                $returnMicroprogram[] = $mi;

                $mi = new Microinstruction;
                $mi->setMuxAndALUValueFromSourceRegisterAndMnemonic('AR2', $inst->getMnemonic());
                $mi->setTargetIndexFromTargetRegister('AR2');

                $returnMicroprogram[] = $mi;

                //now place the value back into the right register

                $mi = new Microinstruction;
                $mi->setMuxAndALUValueForMOVFromSourceRegister('AR2');
                $mi->setTargetIndexFromTargetRegister('MDR');
                $mi->setWrite();

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        } else {
            if ($inst->hasConstant()) {
                throw new DecoderException('not able to shift a pure number. It should be either an indirected constant or a register');
            } else {
                $mi = new Microinstruction;

                $mi->setMuxAndALUValueFromSourceRegisterAndMnemonic($inst->getParam1(), $inst->getMnemonic());
                $mi->setTargetIndexFromTargetRegister($inst->getParam1());

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        }
    }

    private function decodeMOVInstruction(Instruction $inst) {
        $returnMicroprogram = array();

        if ($inst->hasIndirection()) {
            if ($inst->hasConstant()) {
                if ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and !$inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM(REG,[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    //$mi[self::getTargetMicroinstructionIndexFromRegister($inst->getParam1())] = 1;
                    $mi->setTargetIndexFromTargetRegister($inst->getParam1());

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([REG],[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $targetAR = self::getOppositeARFromRegisterName($inst->getParam1());
                    $mi->setTargetIndexFromTargetRegister($targetAR);

                    $returnMicroprogram[] = $mi;

                    unset($mi);
                    $mi = new Microinstruction;
                    $mi->setTargetIndexFromTargetRegister('MAR');
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam1());
                    $mi[25] = 1;

                    $returnMicroprogram[] = $mi;

                    unset($mi);

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction('mdr_to_mar_read');
                    $mi[25] = 0;
                    $returnMicroprogram[] = $mi;

                    unset($mi);

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($targetAR);
                    $mi->setTargetIndexFromTargetRegister('MDR');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([REG],CONST)
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam1());
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([CONST],CONST)
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $mi->setTargetIndexFromTargetRegister('AR1');

                    $returnMicroprogram[] = $mi;

                    $returnMicroprogram[] = new Microinstruction('increment_pc');

                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('AR1');
                    $mi->setTargetIndexFromTargetRegister('MAR');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([CONST],[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('mdr');
                    $mi->setTargetIndexFromTargetRegister('ar1');
                    $returnMicroprogram[] = $mi;

                    $returnMicroprogram[] = new Microinstruction('increment_pc'); //5
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('AR1');
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === 'CONSTANT' and $inst->getParam2() !== "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([CONST],REG)
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam2());
                    $mi->setTargetIndexFromTargetRegister('mdr');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === 'CONSTANT' and $inst->getParam2() !== "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([CONST],[REG])

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');


                    $mi = new Microinstruction;
                    $targetAR = self::getOppositeARFromRegisterName($inst->getParam2());
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $mi->setTargetIndexFromTargetRegister($targetAR);

                    $returnMicroprogram[] = $mi;

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam2());
                    $mi->setTargetIndexFromTargetRegister('MAR');
                    $mi->setRead();

                    $returnMicroprogram[] = $mi;

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($targetAR);
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                }
            } else {//indirection but no constants
                if ($inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([REG],[REG])

                    $mi = new Microinstruction;
                    $reg2 = $inst->getParam2();
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($reg2);
                    $mi->setOne(27, 25);

                    $returnMicroprogram[] = $mi;

                    unset($mi);

                    $mi = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = $mi;
                    unset($mi);

                    $mi = new Microinstruction;
                    $reg1 = $inst->getParam1();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($reg1);

                    $mi->setOne(27, 26);

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getIndirection1()) {
                    //MNEM([REG],REG)
                    $mi = new Microinstruction;
                    $reg2 = $inst->getParam2();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($reg2);

                    $mi->setOne(23, 24);

                    $returnMicroprogram[] = $mi;

                    unset($mi);

                    $mi = new Microinstruction;

                    $reg1 = $inst->getParam1();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($reg1);

                    $mi->setOne(27, 26);

                    $returnMicroprogram[] = $mi;


                    return $returnMicroprogram;
                } elseif ($inst->getIndirection2()) {
                    //MNEM(REG,[REG])
                    $mi = new Microinstruction;

                    $reg2 = $inst->getParam2();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($reg2);

                    //MAR is to receive the data
                    $mi[27] = 1;
                    $mi[25] = 1;

                    $returnMicroprogram[] = $mi;

                    unset($mi);


                    //data to mdr
                    $mi = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = $mi;

                    unset($mi);

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $mi->setTargetIndexFromTargetRegister($inst->getParam1());

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } else {
                    throw new DecoderException('Unknown format found');
                }
            }
        } else {//simpler case: no indirection
            if ($inst->hasConstant()) {
                // if it's no indirection and constants, there can be only one way:
                // MNEM(REG,CONST);
                $mi = new Microinstruction('increment_pc');

                $returnMicroprogram[] = $mi;
                unset($mi);
                $mi = new Microinstruction('pc_to_mar_read');

                $returnMicroprogram[] = $mi;
                unset($mi);

                $mi = new Microinstruction('data_to_mdr');
                $returnMicroprogram[] = $mi;
                unset($mi);

                $mi = new Microinstruction;
                $mi[12] = 1;
                $mi->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'), 8, 0);
                $mi->setTargetIndexFromTargetRegister($inst->getParam1());

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            } else {//still simpler case: no constants and no indirections
                //MNEM(REG,REG)
                $mi = new Microinstruction;

                $reg1 = $inst->getParam1(); //target
                $reg2 = $inst->getParam2(); //source

                $mi->setMuxAndALUValueForMOVFromSourceRegister($reg2);

                $mi->setTargetIndexFromTargetRegister($reg1);

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        }
    }

    private function decodeArithmeticInstruction(Instruction $inst) {
        $mnemonic = $inst->getMnemonic();
        
        $returnMicroprogram = array();

        if ($inst->hasIndirection()) {
            if ($inst->hasConstant()) {
                if ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and !$inst->getIndirection1() and $inst->getIndirection2()) {
                    //ARIT(REG,[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction($mnemonic, $inst->getParam1(), false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //ARIT([REG],[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT

                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam1(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() !== "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([REG],CONST)
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam1(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([CONST],CONST)
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    //need to save the value of mdr so we can use it later on 
                    foreach ($this->decode(new Instruction('mov', 'ar1', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT



                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'mar', false, 'ar1', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" and $inst->getParam2() === "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([CONST],[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    //need to save the value of mdr so we can use it later on 
                    foreach ($this->decode(new Instruction('mov', 'ar1', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT



                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT

                    foreach ($this->decode(new Instruction('mov', 'mar', false, 'ar1', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === 'CONSTANT' and $inst->getParam2() !== "CONSTANT" and $inst->getIndirection1() and !$inst->getIndirection2()) {
                    //MNEM([CONST],REG)
//                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
//                        $returnMicroprogram[] = $line;
//                    }

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, $inst->getParam2(), false));

                    $sizeOfMicroInstructionArray = count($lines);

                    $lines[$sizeOfMicroInstructionArray - 1]->setWrite();

                    foreach ($lines as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === 'CONSTANT' and $inst->getParam2() !== "CONSTANT" and $inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([CONST],[REG])

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam2(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                }
            } else {//indirection but no constants
                if ($inst->getIndirection1() and $inst->getIndirection2()) {
                    //MNEM([REG],[REG])

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam2(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'ar2', false, 'mdr', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam1(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, 'ar2', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getIndirection1()) {
                    //MNEM([REG],REG)

                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam1(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, $inst->getParam2(), false));

                    $sizeOfMicroInstructionArray = count($lines);

                    $lines[$sizeOfMicroInstructionArray - 1]->setWrite();

                    foreach ($lines as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } elseif ($inst->getIndirection2()) {
                    //MNEM(REG,[REG])
                    $lines = $this->decode(new Instruction('mov', 'mar', false, $inst->getParam2(), false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, $inst->getParam1(), false, 'mdr', false));

                    $sizeOfMicroInstructionArray = count($lines);

                    foreach ($lines as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } else {
                    throw new DecoderException('Unknown format found');
                }
            }
        } else {//simpler case: no indirection
            if ($inst->hasConstant()) {
                // if it's no indirection and constants, there can be only one way:
                // ADD(REG,CONST);

                $returnMicroprogram[] = new Microinstruction('increment_pc');

                $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');

                $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                foreach ($this->decode(new Instruction($mnemonic, $inst->getParam1(), false, 'mdr', false)) as $line) {
                    $returnMicroprogram[] = $line;
                }

                return $returnMicroprogram;
            } else {//still simpler case: no constants and no indirections
                //ADD(REG,REG)
                $mi = new Microinstruction;

                $reg1 = $inst->getParam1(); //target
                $reg2 = $inst->getParam2(); //source

                if (self::getSideFromSourceName($reg1) === self::getSideFromSourceName($reg2)) {
                    $ar = self::getOppositeARFromRegisterName($reg2);

                    foreach ($this->decodeMOVInstruction(new Instruction('mov', $ar, false, $reg2, false)) as $micro) {
                        $returnMicroprogram[] = $micro;
                    }

                    $mi = new Microinstruction;

                    $reg2 = $ar;
                }

                $mi->setMuxAndALUValueFromSourceRegisterAndMnemonic($reg1, $reg2, $mnemonic);
                $mi->setTargetIndexFromTargetRegister($reg1);

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        }
    }

    public static function getOppositeARFromRegisterName($regName) {
        switch (strtoupper($regName)) {
            case 'R0':
                return 'AR2';
                break;
            case 'R1':
                return 'AR2';
                break;
            case 'PC':
                return 'AR2';
                break;
            case 'MDR':
                return 'AR2';
                break;
            case 'AR1':
                throw new DecoderException('Trying to get opposite AR from AR?');
                break;
            case 'R2':
                return 'AR1';
                break;
            case 'R3':
                return 'AR1';
                break;
            case 'R4':
                return 'AR1';
                break;
            case 'AR2':
                throw new DecoderException('Trying to get opposite AR from AR?');
                break;
            default:
                throw new DecoderException('Unsupported register set as source to ALU');
        }
    }

    public static function getSideFromSourceName($regName) {
        switch (strtoupper($regName)) {
            case 'MDR':
                return 'A';
                break;
            case 'R0':
                return 'A';
                break;
            case 'R1':
                return 'A';
                break;
            case 'PC';
                return 'A';
                break;
            case 'AR1':
                return 'A';
                break;
            case 'R2':
                return 'B';
                break;
            case 'R3':
                return 'B';
                break;
            case 'R4':
                return 'B';
                break;
            case 'AR2':
                return 'B';
                break;
            default:
                throw new DecoderException('Unsupported register set as source to ALU: '.  strtoupper($regName) );
        }
    }

    public static function getMUXValueFromRegister($regName) {
        switch (strtoupper($regName)) {
            case 'MDR':
                return '001';
                break;
            case 'R0':
                return '010';
                break;
            case 'R1':
                return '011';
                break;
            case 'PC':
                return '100';
                break;
            case 'AR1':
                return '101';
                break;
            case 'R2':
                return '001';
                break;
            case 'R3':
                return '010';
                break;
            case 'R4':
                return '011';
                break;
            case 'AR2':
                return '100';
                break;
            default:
                throw new DecoderException('Unsupported register set as source to MUX: ' . $regName);
        }
    }

    public static function getTargetMicroinstructionIndexFromRegister($regname) {
        if (!is_string($regname)) {
            throw new DecoderException('Register names must be strings.');
        }
        switch (strtoupper($regname)) {
            case 'R0':
                return 8;
                break;
            case 'R1':
                return 9;
                break;
            case 'PC':
                return 10;
                break;
            case 'AR1':
                return 11;
                break;
            case 'R2':
                return 15;
                break;
            case 'R3':
                return 16;
                break;
            case 'R4':
                return 17;
                break;
            case 'AR2':
                return 18;
                break;
            case 'MAR':
                return 27;
                break;
            case 'IR':
                return 28;
                break;
            case 'MDR':
                return 24;
                break;
            default:
                throw new DecoderException('Unsupported register name');
        }
    }

}