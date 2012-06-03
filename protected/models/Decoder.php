<?php

class Decoder{

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
	 * @return array A Microinstruction array, i.e. a microProgram
	 */
	public function decode($instruction){
		$returnMicroprogram    = array();
		if (is_string($instruction)) {
			$returnMicroprogram = $this->getMicroprogramFromAlias($instruction);
		}elseif ($instruction instanceof Instruction){
			$returnMicroprogram = $this->getMicroprogramFromInstruction($instruction);
		}
		return $returnMicroprogram;
	}
	
	/**
	 * For a given String $alias, returns a Microinstruction array, i.e. a microprogram.
	 * @param String $alias
	 */
	private function getMicroprogramFromAlias($alias){
		$returnMicroprogram = array();

		if($alias==="fetch"){
		/*
		 * "fetch" means the following:
		 *  MAR <- PC ;
		 *  MemoryRead;
		 *  MDR <- DataReturnedByMemory;
		 *  IR  <- MDR;
		 */			
			$tempMicro            = new Microinstruction();
			$tempMicro->setOne(14);
                        $tempMicro->setOne(0);
			$tempMicro->setOne(27);
			$returnMicroprogram[] = $tempMicro;//MAR<-PC
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
			$tempMicro->setOne(25);
			$returnMicroprogram[] = $tempMicro;//memory read
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
			$tempMicro->setOne(22);
			$tempMicro->setOne(24);
			$returnMicroprogram[] = $tempMicro;//MDR<-Read data
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
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
	 * to decode that particular kind (mov,add,sub, etc) of Instruction.
	 * @param Instruction $inst
	 * 
	 * @return Array Microprogram
	 */
	private function getMicroprogramFromInstruction(Instruction $inst){
            if($inst->getMnemonic()=='MOV'){
                return $this->decodeMOVInstruction($inst);
            }elseif($inst->getMnemonic()=='ADD'){
                return $this->decodeADDInstruction($inst);
            }else{
                throw new DecoderException('not able to decode instruction whose mnemonic is '.$inst->getMnemonic());
            }
	}
	private function decodeMOVInstruction(Instruction $inst){
            
            if($inst->hasIndirection()){
                
            }else{//simpler case: no indirection
                
                if($inst->hasConstant()){
                    
                }else{//still simpler case: no constants and no indirections
                    
                    if(self::getSideFromSourceName($inst->getArg1())==self::getSideFromSourceName($inst->getArg2())){
                    
                    }else{//simpler still. the regs are on different sides so mov can be done in one cpu cycle
                        $mi = new Microinstruction;
                        
                        $mi->setTargetRegister($inst->getArg1());
                        $mi->setSourceRegister($inst->getArg2());
                        $sourceSide = self::getSideFromSourceName($inst->getArg2());
                        
                        $mi->setALUCode('SEQUALS'.$sourceSide);
                    }
                }
                
            }
	}
	
	private function decodeADDInstruction(Instruction $inst){
		 
	}
	
	private static function getSideFromSourceName($regName){
            switch (strtoupper($regName)) {
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
                    throw new DecoderException('Unsupported register set as source to ALU');
            }
	}
        private static function getMUXValueFromRegister($regName){
            switch ($regName) {
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
                    throw new DecoderException('Unsupported register set as source to MUX');
            }
        }
        
}