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
                
                private $param1 = null;
                private $indirection1 = false;
                
                private $param2 = null;
                private $indirection2 = false;
                
                private $mnemonic;

                public static function getValidInstructions(){
                    $output = array();
                    foreach(self::$validMnemonics as $mnemonic){
                        $output[$mnemonic]= $mnemonic;
                    }
                    return $output;
                }
                                
		public function __construct($mnem,$param1,$ind1,$param2,$ind2){
                    for($i=0;$i<$this->length-1;$i++){
                        $this[$i]=0;
                    }
                                          
                    $this->setMnemonic($mnem);
                    $this->setParam1($param1);
                    $this->setIndirection1($ind1);
                    $this->setParam2($param2);
                    $this->setIndirection2($ind2);
                        
                    if(is_null($this->param2) and $this->requiresTwoArguments()){
                        throw new InstructionException('Missing argument 2 for instruction that needs 2 arguments');
                    }
                    if($this->param1==="CONSTANT" and !$this->indirection1){
                        throw new InstructionException('Cannot use a direct CONSTANT as target for an Instruction.');
                    }
      
                }

                public function getLength(){
                    return $this->length;
                }
		public function getParam1(){
                    return $this->param1;      
		}
                public function getIndirection1(){
                    return $this->indirection1;
                }
         	public function getParam2(){
                    return $this->param2;
		}
                public function getIndirection2(){
                    return $this->indirection2;
                }
             	public function getMnemonic(){
                    return $this->mnemonic;
		}                
		private function requiresTwoArguments(){
                    return !$this->requiresOnlyOneArgument();
		}
                public function hasIndirection(){
                    return $this->indirection1 or $this->indirection2;
                }
                
		private function requiresOnlyOneArgument(){
                    $arr = array('CLR',
                                'NEG',
                                'SHL',
                                'SHR',
                                'BRZ',
                                'BRN',
                                'BRE',
                                'BRL',
                                'BRG',
                                'BRC');
                      if(in_array(strtoupper($this->mnemonic), $arr)){
                          return true;
                      }else{
                          return false;
                      }
                      
		
		}
                private function setIndirection1($value){
                    if(!is_bool($value)){
                        throw new InstructionException('indirection1 value must be a boolean');
                    }
                    if($value){
                        $this->indirection1 = true;
                        $this->setOne(12);
                    }else{
                        $this->indirection1 = false;
                        $this->setZero(12);
                    }
                }
                
                private function setIndirection2($value){
                    if(!is_bool($value)){
                        throw new InstructionException('indirection2 value must be a boolean');
                    }
                    if($value){
                        $this->indirection2 = true;
                        $this->setOne(18);
                    }else{
                        $this->indirection2 = false;
                        $this->setZero(18);
                    }                    
                }
                
                private function setParam1($param){
                    $this->param1 = strtoupper($param);

			switch(strtoupper($param)){
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
				default:
					throw new InstructionException('Invalid Param1');
			}
		}
		private function setParam2($param){
                    $this->param2 = strtoupper($param);
			switch(strtoupper($param)){
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
                                case null:
                                    $this->param2 = null;
                                    break;
				default:
					throw new InstructionException('Invalid Param2');
			}
		}                
                
		private function setMnemonic($mnemonic){
                    $this->mnemonic = strtoupper($mnemonic);
			switch(strtoupper($mnemonic)){
				case "MOV":
					$this->setIntValueStartingAt(1,6,26);
					break;
				case "ADD":
					$this->setIntValueStartingAt(2,6,26);
					break;
				case "SUB":
					$this->setIntValueStartingAt(3,6,26);
					break;
				case "AND":
					$this->setIntValueStartingAt(4,6,26);
					break;
				case "OR":
					$this->setIntValueStartingAt(5,6,26);
					break;
				case "NAND":
					$this->setIntValueStartingAt(6,6,26);
					break;
				case "NOR":
					$this->setIntValueStartingAt(7,6,26);
					break;
				case "XOR":
					$this->setIntValueStartingAt(8,6,26);
					break;
				case "CMP":
					$this->setIntValueStartingAt(9,6,26);
					break;
				case "CLR":
					$this->setIntValueStartingAt(10,6,26);
					break;
				case "NEG":
					$this->setIntValueStartingAt(11,6,26);
					break;
				case "SHL":
					$this->setIntValueStartingAt(12,6,26);
					break;
				case "SHR":
					$this->setIntValueStartingAt(13,6,26);
					break;
				case "BRZ":
					$this->setIntValueStartingAt(14,6,26);
					break;
				case "BRN":
					$this->setIntValueStartingAt(15,6,26);
					break;
				case "BRE":
					$this->setIntValueStartingAt(16,6,26);
					break;
				case "BRL":
					$this->setIntValueStartingAt(17,6,26);
					break;
				case "BRG":
					$this->setIntValueStartingAt(18,6,26);
					break;
				case "BRC":
					$this->setIntValueStartingAt(19,6,26);
					break;
				default:
					throw new InstructionException("Invalid Mnemonic for this machine.");
			}
		}
		
                public function humanReadableForm(){
                    $output = "";
                    
                    $output .= $this->mnemonic;
                    
                    $output .= "(";
                    
                    $output .= $this->indirection1? "[":"";
                    
                    $output .= ($this->param1==="CONSTANT")? "#".$this->param1 : $this->param1;
                        
                    $output .= $this->indirection1? "]":"";
                    
                    if(is_null($this->param2)){
                        $output .= ")";
                        return $output;
                    }
                    
                    $output .= ",";
                    
                    $output .= $this->indirection2? "[":"";
                    
                    $output .= ($this->param2==="CONSTANT")? "#".$this->param2 : $this->param2;
                        
                    $output .= $this->indirection2? "]":"";
                    
                    $output .= ")";
                    
                    return $output;
                }
                public function hasConstant(){
                    if(($this->param1==='CONSTANT') or ($this->param2==='CONSTANT')){
                        return true;
                    }else{
                        return false;
                    }
                }
                
	}


?>