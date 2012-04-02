<?php
	require_once "helpers/BinaryString.php";
	require_once "helpers/InstructionException.php";
	require_once "helpers/PrimitiveFunctions.php";
	class Instruction extends BinaryString{
		
		/**
                 *@var array
                 *@static 
                 */
		protected static $validMnemonics = array(
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
                                                'NEG',
                                                'SHL',
                                                'SHR',
                                                'BRZ',
                                                'BRN',
                                                'BRE',
                                                'BRL',
                                                'BRG',
                                                'BRC');
                
                
                protected $length = 37;		
		protected $string;
                
                public static function getValidInstructions(){
                    $output = array();
                    foreach(self::$validMnemonics as $mnemonic){
                        $output[$mnemonic]=$mnemonic;
                    }
                    return $output;
                }
                                
		public function __construct(VOInstruction $vo){
                  
			$this->setArg1($vo->getArg1());
			$this->setArg2($vo->getArg2());
                        
                        if($vo->arg1IsConstant()){
                            $this->setConstant1($vo->getConstant1());
                        }
                        if($vo->arg2IsConstant()){
                            $this->setConstant2($vo->getConstant2());
                        }
                        
                        if($vo->arg1IsIndirection()){
                            $this->setOne(15);
                        }else{
                            $this->setZero(15);
                        }
                        
                        if($vo->arg2IsIndirection()){
                            $this->setOne(31);
                        }else{
                            $this->setZero(31);
                        }
                        
                        $this->setMnemonic($vo->getMnemonic());
      
                }

                public function getLength(){
                    return $this->length;
                }
                

		public function getMnemonic(){
			
		}
		public function getTargetRegister(){
			
		}
		public function getSourceRegister(){
			
		}
		public function requiresTwoArguments(){
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */

		}
		public function requiresOnlyOneArgument(){
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
		
		}                
                public function setArg1($regName){
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
			switch(strtoupper($regName)){
				case "R0":
					$this->setIntValueStartingAt(1, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "R1":
					$this->setIntValueStartingAt(2, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "R2":
					$this->setIntValueStartingAt(3, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "R3":
					$this->setIntValueStartingAt(4, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "R4":
					$this->setIntValueStartingAt(5, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "AR1":
					$this->setIntValueStartingAt(6, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "AR2":
					$this->setIntValueStartingAt(7, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
                                case "CONSTANT":
                                        $this->setZero(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14);
                                        break;
				default:
					throw new InstructionException('Invalid Argument1');
			}
		}
		public function setArg2($regName){
					/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
			switch(strtoupper($regName)){
				case "R0":
					$this->setIntValueStartingAt(1, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
				case "R1":
					$this->setIntValueStartingAt(2, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);    
                                        break;
				case "R2":
					$this->setIntValueStartingAt(3, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
				case "R3":
                                        $this->setIntValueStartingAt(4, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
				case "R4":
                                        $this->setIntValueStartingAt(5, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
				case "AR1":
                                        $this->setIntValueStartingAt(6, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
				case "AR2":
                                        $this->setIntValueStartingAt(7, 5, 16);
                                        $this->setOne(21,22,23,24,25,26,27,28,29,30);
					break;
                                case "CONSTANT":
                                        $this->setZero(16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);
                                        break;
				default:
					throw new InstructionException('Invalid Argument2');
			}
		}                
                
		public function setMnemonic($mnemonic){
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
			switch($mnemonic){
				case "MOV":
					$this->setIntValueStartingAt(1,5,32);
					break;
				case "ADD":
					$this->setIntValueStartingAt(2,5,32);
					break;
				case "SUB":
					$this->setIntValueStartingAt(3,5,32);
					break;
				case "AND":
					$this->setIntValueStartingAt(4,5,32);
					break;
				case "OR":
					$this->setIntValueStartingAt(5,5,32);
					break;
				case "NAND":
					$this->setIntValueStartingAt(6,5,32);
					break;
				case "NOR":
					$this->setIntValueStartingAt(7,5,32);
					break;
				case "XOR":
					$this->setIntValueStartingAt(8,5,32);
					break;
				case "CMP":
					$this->setIntValueStartingAt(9,5,32);
					break;
				case "CLR":
					$this->setIntValueStartingAt(10,5,32);
					break;
				case "NEG":
					$this->setIntValueStartingAt(11,5,32);
					break;
				case "SHL":
					$this->setIntValueStartingAt(12,5,32);
					break;
				case "SHR":
					$this->setIntValueStartingAt(13,5,32);
					break;
				case "BRZ":
					$this->setIntValueStartingAt(14,5,32);
					break;
				case "BRN":
					$this->setIntValueStartingAt(15,5,32);
					break;
				case "BRE":
					$this->setIntValueStartingAt(16,5,32);
					break;
				case "BRL":
					$this->setIntValueStartingAt(17,5,32);
					break;
				case "BRG":
					$this->setIntValueStartingAt(18,5,32);
					break;
				case "BRC":
					$this->setIntValueStartingAt(19,5,32);
					break;
				default:
					throw new InstructionException("Invalid Mnemonic for this machine.");
			}
		}
		
                public function setConstant1($intValue){
                    $this->setIntValueStartingAt($intValue, 15, 0);
                }
                public function setConstant2($intValue){
                    $this->setIntValueStartingAt($intValue, 15, 16);
                }
                
	}


?>