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
     * to decode that particular kind (mov,add,sub, etc) of Instruction, && then returns the Microprogram.
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
        } elseif ($inst->getMnemonic() === 'SHL' || $inst->getMnemonic() === 'SHR' || $inst->getMnemonic() === 'NOT' || $inst->getMnemonic() === 'NEG' ) {
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
                //MNEM([CONST])
                //check
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
                //MNEM([REG])
                //check
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
            //no indirection
            if ($inst->hasConstant()) {
                throw new DecoderException('Unable to shift a pure number. It should be either an indirected constant or a register or an indirected register.');
            } else {
               //MNEM(REG)
                //check
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
                if ($inst->getParam2() !== "CONSTANT" && $inst->getParam1() === "CONSTANT" && !$inst->getIndirection2() && $inst->getIndirection1()) {
                    //MNEM([CONST],REG)
                    
                    $returnMicroprogram=[];
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    //the actual value to be written sits in mdr
                    
                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    //$mi[self::getTargetMicroinstructionIndexFromRegister($inst->getParam1())] = 1;
                    $mi->setTargetIndexFromTargetRegister($inst->getParam2());

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam2() !== "CONSTANT" && $inst->getParam1() === "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([CONST],[REG])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $targetAR = self::getOppositeARFromRegisterName($inst->getParam2());
                    $mi->setTargetIndexFromTargetRegister($targetAR);

                    $returnMicroprogram[] = $mi;

                    unset($mi);
                    $mi = new Microinstruction;
                    $mi->setTargetIndexFromTargetRegister('MAR');
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam2());
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
                } elseif ($inst->getParam2() !== "CONSTANT" && $inst->getParam1() === "CONSTANT" && $inst->getIndirection2() && !$inst->getIndirection1()) {
                    //MNEM(CONST,[REG])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam2());
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() === "CONSTANT" && $inst->getIndirection2() && !$inst->getIndirection1()) {
                    //MNEM(CONST,[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $mi->setTargetIndexFromTargetRegister('AR1');//the value which will be written

                    $returnMicroprogram[] = $mi;

                    $returnMicroprogram[] = new Microinstruction('increment_pc');

                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    //the value is still in ar1, the address is in mdr
                    
                    
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar');
                    
                    $mi = new Microinstruction();
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('ar1');
                    $mi->setTargetIndexFromTargetRegister('mdr');
                    $mi->setWrite();
                    
                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() === "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([CONST],[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    //the value to be written sits in mdr

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('mdr');
                    $mi->setTargetIndexFromTargetRegister('ar1');
                    $returnMicroprogram[] = $mi;
                    
                    //the value to be written is now in AR1

                    $returnMicroprogram[] = new Microinstruction('increment_pc'); //5
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar');
                    

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('AR1');
                    $mi->setTargetIndexFromTargetRegister('mdr');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam2() === 'CONSTANT' && $inst->getParam1() !== "CONSTANT" && $inst->getIndirection2() && !$inst->getIndirection1()) {
                    //MNEM(REG,[CONST])
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar');

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam1());
                    $mi->setTargetIndexFromTargetRegister('mdr');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getParam2() === 'CONSTANT' && $inst->getParam1() !== "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([REG],[CONST])

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');


                    $mi = new Microinstruction;
                    $targetAR = self::getOppositeARFromRegisterName($inst->getParam1());
                    $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');
                    $mi->setTargetIndexFromTargetRegister($targetAR);

                    $returnMicroprogram[] = $mi;

                    $mi = new Microinstruction;
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($inst->getParam1());
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
                if ($inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([REG],[REG])

                    $returnMicroprogram = [];
                    
                    $mi = new Microinstruction;
                    $source = $inst->getParam1();
                    $mi->setMuxAndALUValueForMOVFromSourceRegister($source);
                    //$mi->setOne(27, 25);
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setRead();
                    
                    $returnMicroprogram[] = $mi;

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    //no mdr está o valor a ser escrito
                    
                    
                    
                    $mi = new Microinstruction;
                    $target = $inst->getParam2();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($target);
                    $mi->setTargetIndexFromTargetRegister('mar');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } elseif ($inst->getIndirection2()) {
                    //MNEM(REG,[REG])
                    $mi = new Microinstruction;
                    $source = $inst->getParam2();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($source);
                    $mi->setTargetIndexFromTargetRegister('mar');

                    $returnMicroprogram[] = $mi;

                    $mi = new Microinstruction;

                    $source = $inst->getParam1();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($source);

                    $mi->setTargetIndexFromTargetRegister('mdr');
                    $mi->setWrite();

                    $returnMicroprogram[] = $mi;


                    return $returnMicroprogram;
                } elseif ($inst->getIndirection1()) {
                    //MNEM([REG],REG)
                    $mi = new Microinstruction;

                    $source = $inst->getParam1();

                    $mi->setMuxAndALUValueForMOVFromSourceRegister($source);

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
                    $mi->setTargetIndexFromTargetRegister($inst->getParam2());

                    $returnMicroprogram[] = $mi;

                    return $returnMicroprogram;
                } else {
                    throw new DecoderException('Unknown format found');
                }
            }
        } else {//simpler case: no indirection
            if ($inst->hasConstant()) {
                // if it's no indirection && constants, there can be only one way:
                // MNEM(CONST,REG);
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
                $mi->setTargetIndexFromTargetRegister($inst->getParam2());

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            } else {//still simpler case: no constants && no indirections
                //MNEM(REG,REG)
                $mi = new Microinstruction;

                $source = $inst->getParam1(); 
                $target = $inst->getParam2(); 

                $mi->setMuxAndALUValueForMOVFromSourceRegister($source);

                $mi->setTargetIndexFromTargetRegister($target);

                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        }
    }

    private function decodeArithmeticInstruction(Instruction $inst) {
        $mnemonic = $inst->getMnemonic();
        
        $returnMicroprogram = [];

        if ($inst->hasIndirection()) {
            if ($inst->hasConstant()) {
                if ($inst->getParam1() === "CONSTANT" && $inst->getParam2() !== "CONSTANT" && $inst->getIndirection1() && !$inst->getIndirection2()) {
                    //ARIT([CONST],REG)
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction($mnemonic, 'mdr', false, $inst->getParam2(), false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                }elseif($inst->getParam1()!=="CONSTANT" && $inst->getParam2()==="CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()){
                    //MNEM([REG],[CONST])
                    //check
                    $lines=$this->decode(new Instruction('mov', $inst->getParam1(), false,'mar', false));
                    $lines[0]->setRead();
                    
                    assert(!array_key_exists(1, $lines));
                    $returnMicroprogram[] = $lines[0];
                    
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    $returnMicroprogram[] = $this->decode(new Instruction('mov', 'mdr', false,'ar2', false))[0];
                    
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    $lines = $this->decode(new Instruction($mnemonic,'ar2',false,'mdr',false));
                    $lines[0]->setWrite();
                    
                    $returnMicroprogram[] = $lines[0];
                    
                    return $returnMicroprogram;
                    
                }elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() !== "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //ARIT([CONST],[REG])
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); 
                    //mdr contains the value in memory position defined by CONSTANT
                    
                    //we need to save it in ar2 because we'll now use mdr 
                    foreach ($this->decode(new Instruction('mov', 'mdr', false, 'ar2', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction('mov', $inst->getParam2(), false, 'mar', false));

                    assert(!array_key_exists(1, $lines));
                    
                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];
                    
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    //mdr now holds the contents of the address pointed to by REG
                    //now we need to perform the calculation and write the value back to REG

                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false, 'mdr', false));
                    
                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    assert(!array_key_exists(1, $lines));
                    
                    return $returnMicroprogram;
                }
                
                elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() !== "CONSTANT" && $inst->getIndirection2() && !$inst->getIndirection1()) {
                    //MNEM(CONST,[REG])
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'mdr', false, 'ar2', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $lines = $this->decode(new Instruction('mov', $inst->getParam2(), false, 'mar', false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false, 'mdr', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() === "CONSTANT" && !$inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM(CONST,[CONST])
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    //need to save the value of mdr so we can use it later on 
                    foreach ($this->decode(new Instruction('mov', 'mdr', false, 'ar2', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');


                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false, 'mdr', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === "CONSTANT" && $inst->getParam2() === "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([CONST],[CONST])
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    
                    //need to save the value of mdr so we can use it later on 
                    foreach ($this->decode(new Instruction('mov', 'mdr', false, 'ar2', false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }
                    
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    //the address sits in MAR. the value that was in the 2nd CONSt is now in MDR
                    

                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false, 'mdr', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() !== 'CONSTANT' && $inst->getParam2() === "CONSTANT" && !$inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM(REG,[CONST])
                    //check
                    $returnMicroprogram[] = new Microinstruction('increment_pc');
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr'); //mdr contains the value in memory position defined by CONSTANT

                    $lines = $this->decode(new Instruction($mnemonic, $inst->getParam1(), false,'mdr', false));

                    $sizeOfMicroInstructionArray = count($lines);

                    $lines[$sizeOfMicroInstructionArray - 1]->setWrite();

                    foreach ($lines as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } elseif ($inst->getParam1() === 'CONSTANT' && $inst->getParam2() !== "CONSTANT" && $inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([CONST],[REG])
                    //check
                    $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    $returnMicroprogram[] = new Microinstruction('mdr_to_mar_read');
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    $lines = $this->decode(new Instruction('mov','mdr',false,'ar2',false));
                    //data to be written was saved in ar2
                    
                    $lines = $this->decode(new Instruction('mov', $inst->getParam2(), false, 'mar', false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];
                    
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false, 'mdr', false));

                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                }
            } else {//indirection but no constants
                if ($inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM([REG],[REG])
                    //check
                    $lines = $this->decode(new Instruction('mov',$inst->getParam1() , false, 'mar', false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    foreach ($this->decode(new Instruction('mov', 'mdr', false,'ar2' , false)) as $line) {
                        $returnMicroprogram[] = $line;
                    }
                    
                    //o valor a ser escrito está em AR2

                    $lines = $this->decode(new Instruction('mov', $inst->getParam2(), false,'mar' , false));
                    
                    assert(!array_key_exists(1, $lines));
                    
                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    
                    //em MDR está o valor apontado pelo segundo REG
                    //e em MAR está o endereço correto
                    
                    $lines = $this->decode(new Instruction($mnemonic, 'ar2', false,'mdr' , false));
                    
                    assert(!array_key_exists(1, $lines));
                    
                    $lines[0]->setWrite();

                    $returnMicroprogram[] = $lines[0];

                    return $returnMicroprogram;
                } elseif ($inst->getIndirection1() && !$inst->getIndirection2()) {
                    //MNEM([REG],REG)
                    //check
                    $lines = $this->decode(new Instruction('mov',$inst->getParam1() , false, 'mar', false));

                    $lines[0]->setRead();

                    $returnMicroprogram[] = $lines[0];

                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                    $lines = $this->decode(new Instruction($mnemonic, 'mdr', false, $inst->getParam2(), false));

                    foreach ($lines as $line) {
                        $returnMicroprogram[] = $line;
                    }

                    return $returnMicroprogram;
                } elseif (!$inst->getIndirection1() && $inst->getIndirection2()) {
                    //MNEM(REG,[REG])
                    //check
                    $lines = $this->decode(new Instruction('mov', $inst->getParam2(), false,'mar', false));

                    assert(!array_key_exists(1, $lines));
                    
                    $lines[0]->setRead();
                    
                    $returnMicroprogram[] = $lines[0];
                    
                    $returnMicroprogram[] = new Microinstruction('data_to_mdr');
                    
                    $lines = $this->decode(new Instruction($mnemonic, $inst->getParam1(), false, 'mdr', false));
                    
                    $sizeOfMicroInstructionArray = count($lines);

                    $lines[$sizeOfMicroInstructionArray-1]->setWrite();
                    
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
                // if it's no indirection && constants, there can be only one way:
                // ADD(CONST,REG);
                //check
                $returnMicroprogram[] = new Microinstruction('increment_pc');

                $returnMicroprogram[] = new Microinstruction('pc_to_mar_read');

                $returnMicroprogram[] = new Microinstruction('data_to_mdr');

                foreach ($this->decode(new Instruction($mnemonic, 'mdr', false, $inst->getParam2(), false)) as $line) {
                    $returnMicroprogram[] = $line;
                }

                return $returnMicroprogram;
            } else {//still simpler case: no constants && no indirections
                //ADD(REG,REG)
                //check
                $mi = new Microinstruction;
                              
                $source = $inst->getParam1(); //source
                $target = $inst->getParam2(); //target
                
                if (self::getSideFromSourceName($source) === self::getSideFromSourceName($target)) {
                    
                    $ar = self::getOppositeARFromRegisterName($source);
                    
                    foreach ($this->decodeMOVInstruction(new Instruction('mov', $source, false, $ar, false)) as $micro) {
                        $returnMicroprogram[] = $micro;
                    }

                    $mi = new Microinstruction;

                    $source = $ar;
                }
                
                $mi->setMuxAndALUValue($mnemonic,$source,$target);
                $mi->setTargetIndexFromTargetRegister($target);

                //this is to certify that, when mdr is selected as target, mux c values are also set.
                if($mi[24]==='1'){
                    assert(($mi[23])==='1');
                    assert(($mi[22])==='0');
                }
                
                $returnMicroprogram[] = $mi;

                return $returnMicroprogram;
            }
        }
    }

    public static function getOppositeARFromRegisterName($regName) {
        switch (strtoupper($regName)) {
            case 'R0':
                return 'AR2';
            case 'R1':
                return 'AR2';
            case 'PC':
                return 'AR2';
            case 'MDR':
                return 'AR2';
            case 'AR1':
                throw new DecoderException('Trying to get opposite AR from AR?');
            case 'R2':
                return 'AR1';
            case 'R3':
                return 'AR1';
            case 'R4':
                return 'AR1';
            case 'AR2':
                throw new DecoderException('Trying to get opposite AR from AR?');
            default:
                throw new DecoderException('Unsupported register set as source to ALU: ',$regName);
        }
    }

    public static function getSideFromSourceName($regName) {
        switch (strtoupper($regName)) {
            case 'MDR':
                return 'A';
            case 'R0':
                return 'A';
            case 'R1':
                return 'A';
            case 'PC':
                return 'A';
            case 'AR1':
                return 'A';
            case 'R2':
                return 'B';
            case 'R3':
                return 'B';
            case 'R4':
                return 'B';
            case 'AR2':
                return 'B';
            default:
                throw new DecoderException('Unsupported register set as source to ALU: '.  strtoupper($regName) );
        }
    }

    public static function getMUXValueFromRegister($regName) {
        switch (strtoupper($regName)) {
            case 'MDR':
                return '001';
            case 'R0':
                return '010';
            case 'R1':
                return '011';
            case 'PC':
                return '100';
            case 'AR1':
                return '101';
            case 'R2':
                return '001';
            case 'R3':
                return '010';
            case 'R4':
                return '011';
            case 'AR2':
                return '100';
            default:
                throw new DecoderException('Unsupported register set as source to MUX: ' . $regName);
        }
    }

    public static function getTargetMicroinstructionIndexFromRegister($regname) {
        if (!is_string($regname)) 
            throw new DecoderException('Register names must be strings.');
        
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