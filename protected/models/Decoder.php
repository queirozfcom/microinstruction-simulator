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
			$tempMicro->setZero(12);
			$tempMicro->setOne(13,14);
			$tempMicro->setZero(0,1,2,3,4,5,6);
			$tempMicro->setOne(7);
			$tempMicro->setOne(26);
			$returnMicroprogram[] = $tempMicro;//MAR<-PC
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
			$tempMicro->setOne(24);
			$tempMicro->setZero(25);
			$returnMicroprogram[] = $tempMicro;//memory read
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
			$tempMicro->setZero(22);
			$tempMicro->setOne(23);
			$returnMicroprogram[] = $tempMicro;//MDR<-Read data
			unset($tempMicro);
			
			$tempMicro            = new Microinstruction();
			$tempMicro->setZero(12,13,14);
			$tempMicro->setZero(0,1,2,3,4,5,6);
			$tempMicro->setOne(7);
			$tempMicro->setOne(27);
			$returnMicroprogram[] = $tempMicro; //IR<-MDR
			
		}		
		return $returnMicroprogram;
	}
	/**
	 * 
	 * Tests what kind of Instruction it was sent. Then redirects to the correct method
	 * to decode that particular kind (mov,add,sub,etc) of Instruction.
	 * @param Instruction $inst
	 * 
	 * @return Array Microprogram
	 */
	private function getMicroprogramFromInstruction(Instruction $inst){

	}
	private function decodeMOVInstruction(Instruction $inst){
		
	}
	
	private function decodeADDInstruction(Instruction $inst){
		 
	}
	
	private function getSideFromSourceName($regName){

	}
}