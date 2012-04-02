<?php
require_once 'Instruction.php';
require_once 'helpers/VOInstruction.php';
require_once 'helpers/InstructionFactoryException.php';
	class InstructionFactory {
		//SE UM ARGUMENTO Não é utilizado por esta instrução, passar NULL.
		private $instructionSet = array();
		
		public function createInstructionSet(VOInstruction $param){
			
			if($param->hasIndirection()){
				if($param->arg1IsIndirection()){
					
				}if($param->arg2IsIndirection()){
					//todo
				}
			}
			if($param->hasConstant()){
				if($param->arg1IsConstant()){
					//todo
				}
				if($param->arg2IsConstant()){
					//todo
				}
			}
			if(!$param->hasConstant() and !$param->hasIndirection()){
				$instr = new Instruction($param->getMnemonic(), $param->getArg1(), $param->getArg2());
				$this->instructionSet[] = $instr;
			}
			
			return $this->instructionSet;
		}
		public function addSetInstruction($data,$regName){
		/*
		 * switch to private upon end of testing
		 */
		}
		public function addLoadInstruction($regName,$regName){
		/*
		 * switch to private upon end of testing
		 */	
		}
		public function addStoreInstruction($regName,$regName){
		/*
		 * switch to private upon end of testing
		 */				
		}
	}
