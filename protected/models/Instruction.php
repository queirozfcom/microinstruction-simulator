<?php 

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
                
                
                protected $length = 32;		
		protected $string;
                
                //switch to private upon end of testing
                
                private $arg1;
                private $constant1 = null;
                private $indirection1 = 0;
                
                private $arg2;
                private $constant2 = null;
                private $indirection2 = 0;
                
                private $mnemonic;

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
                            $this->indirection1 = true;
                            $this->setOne(10);
                        }else{
                            $this->indirection1 = false;
                            $this->setZero(10);
                        }
                        
                        if($vo->arg2IsIndirection()){
                            $this->indirection2 = true;
                            $this->setOne(21);
                        }else{
                            $this->indirection2 = false;
                            $this->setZero(21);
                        }
                        //these 5 bits aren't used up to now
                        $this->setOne(27,28,29,30,31);
                        $this->setMnemonic($vo->getMnemonic());
      
                }

                public function getLength(){
                    return $this->length;
                }
		public function getArg1(){
                    return $this->arg1;      
		}
                public function getIndirection1(){
                    return $this->indirection1;
                }
                public function getConstant1(){
                    return $this->constant1;
                }
		public function getArg2(){
                    return $this->arg2;
		}
                public function getIndirection2(){
                    return $this->indirection2;
                }
                public function getConstant2(){
                    return $this->constant2;
                }
		public function getMnemonic(){
                    return $this->mnemonic;
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
                public function setArg1($arg1){
                    $this->arg1 = strtoupper($arg1);
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
			switch(strtoupper($arg1)){
				case "R0":
					$this->setIntValueStartingAt(1, 5, 0);
                                        $this->setOne(5,6,7,8,9,10,11,12,13,14);
					break;
				case "R1":
					$this->setIntValueStartingAt(2, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
				case "R2":
					$this->setIntValueStartingAt(3, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
				case "R3":
					$this->setIntValueStartingAt(4, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
				case "R4":
					$this->setIntValueStartingAt(5, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
				case "AR1":
					$this->setIntValueStartingAt(6, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
				case "AR2":
					$this->setIntValueStartingAt(7, 5, 0);
                                        $this->setOne(5,6,7,8,9);
					break;
                                case "CONSTANT":
                                        $this->setZero(0,1,2,3,4,5,6,7,8,9);
                                        break;
				default:
					throw new InstructionException('Invalid Argument1');
			}
		}
		public function setArg2($arg2){
					/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
                    $this->arg2 = strtoupper($arg2);
			switch(strtoupper($arg2)){
				case "R0":
					$this->setIntValueStartingAt(1, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
				case "R1":
					$this->setIntValueStartingAt(2, 5, 11);
                                        $this->setOne(16,17,18,19,20);    
                                        break;
				case "R2":
					$this->setIntValueStartingAt(3, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
				case "R3":
                                        $this->setIntValueStartingAt(4, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
				case "R4":
                                        $this->setIntValueStartingAt(5, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
				case "AR1":
                                        $this->setIntValueStartingAt(6, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
				case "AR2":
                                        $this->setIntValueStartingAt(7, 5, 11);
                                        $this->setOne(16,17,18,19,20);
					break;
                                case "CONSTANT":
                                        $this->setZero(11,12,13,14,15,16,17,18,19,20);
                                        break;
                                case null:
                                        $this->arg2 = null;
                                        $this->setOne(11,12,13,14,15);
                                        $this->setOne(16,17,18,19,20);
                                        break;
				default:
					throw new InstructionException('Invalid Argument2');
			}
		}                
                
		public function setMnemonic($mnemonic){
                    $this->mnemonic = strtoupper($mnemonic);
		/*
		 * SWITCH TO PRIVATE UPON END OF TESTING
		 */
			switch(strtoupper($mnemonic)){
				case "MOV":
					$this->setIntValueStartingAt(1,5,22);
					break;
				case "ADD":
					$this->setIntValueStartingAt(2,5,22);
					break;
				case "SUB":
					$this->setIntValueStartingAt(3,5,22);
					break;
				case "AND":
					$this->setIntValueStartingAt(4,5,22);
					break;
				case "OR":
					$this->setIntValueStartingAt(5,5,22);
					break;
				case "NAND":
					$this->setIntValueStartingAt(6,5,22);
					break;
				case "NOR":
					$this->setIntValueStartingAt(7,5,22);
					break;
				case "XOR":
					$this->setIntValueStartingAt(8,5,22);
					break;
				case "CMP":
					$this->setIntValueStartingAt(9,5,22);
					break;
				case "CLR":
					$this->setIntValueStartingAt(10,5,22);
					break;
				case "NEG":
					$this->setIntValueStartingAt(11,5,22);
					break;
				case "SHL":
					$this->setIntValueStartingAt(12,5,22);
					break;
				case "SHR":
					$this->setIntValueStartingAt(13,5,22);
					break;
				case "BRZ":
					$this->setIntValueStartingAt(14,5,22);
					break;
				case "BRN":
					$this->setIntValueStartingAt(15,5,22);
					break;
				case "BRE":
					$this->setIntValueStartingAt(16,5,22);
					break;
				case "BRL":
					$this->setIntValueStartingAt(17,5,22);
					break;
				case "BRG":
					$this->setIntValueStartingAt(18,5,22);
					break;
				case "BRC":
					$this->setIntValueStartingAt(19,5,22);
					break;
				default:
					throw new InstructionException("Invalid Mnemonic for this machine.");
			}
		}
		
                public function setConstant1($intValue){
                    $this->constant1 = $intValue;
                    $this->setIntValueStartingAt($intValue, 10, 0);
                }
                public function setConstant2($intValue){
                    $this->constant2 = $intValue;
                    $this->setIntValueStartingAt($intValue, 10, 11);
                }
                public function humanReadableForm(){
                    $output = "";
                    
                    $output .= $this->mnemonic;
                    
                    $output .= "(";
                    
                    $output .= $this->indirection1? "[":"";
                    
                    $output .= $this->arg1 === 'CONSTANT'? "#".$this->constant1 : $this->arg1;
                        
                    $output .= $this->indirection1? "]":"";
                    
                    if(is_null($this->arg2)){
                        $output .= ")";
                        return $output;
                    }
                    
                    $output .= ",";
                    
                    $output .= $this->indirection2? "[":"";
                    
                    $output .= $this->arg2 === 'CONSTANT'? "#".$this->constant2 : $this->arg2;
                        
                    $output .= $this->indirection2? "]":"";
                    
                    $output .= ")";
                    
                    return $output;
                }
                
	}


?>