<?php
require_once "helpers/PrimitiveFunctions.php";
require_once 'helpers/BinaryString.php';
class ALU {
	public function __construct() {
		//todo
	}
	public static function returnOpCodeForOperation($operation){
            if(!is_string($operation)){
                throw new ALUException('Operation must be a valid string');
            }
            switch ($operation) {
                case 'S=A':
                    return 1;
                    break;
                case 'S=B':
                    return 2;
                    break;
                case 'S=A+1':
                    return 3;
                    break;
                case 'S=B+1':
                    return 4;
                    break;
                default:
                    throw new ALUException('Unsupported operation');
            }
        }
        
        /**
	 * IMPORTANT: THIS WILL RETURN EITHER A BINARYSTRING OR AN INSTRUCTION!!
	 * 
	 * @param Register $a
	 * @param Register $b
	 * @param String $opCode
	 */
	public function operateOn(Register $a,Register $b, $opCode){

	}
	
	/**
	 * Performs an operation on the arguments and returns the result 
	 *
	 * @param String $operation
	 * @param BinaryString|Instruction arg1
	 * @param BinaryString|Instruction arg2
	 * 
	 * @return BinaryString|Instruction 
	 * 
	 * @example compute("ADD" , BinaryString , BinaryString)
	 * @example compute("SUB" , BinaryString , Instruction)
	 * @example compute("REPEAT1" , Instruction , BinaryString) ->will just output whatever was input for $arg1
	 * @example compute("REPEAT2" , ditto , ditto) ->will just output whatever was input for $arg2
	 */
	private function compute($operation,$arg1,$arg2){
		switch ($operation){
			case "REPEATA":
				return $arg1;
				break;
			case "REPEATB":
				return $arg2;
				break;
			case "INCREMENTA":
				return $this->increment($arg1);
			case "ADD":
				return $this->add($arg1,$arg2);
				break;
		}
	}
	/*
	 * all functions starting here will ONLY be used as helpers to function compute()
	 */
	private function increment($arg1){
		return $arg1->increment();
	}
	private function add($arg1,$arg2){
		//performs add on two binary strings
		echo $arg1 . " , " . $arg2;
	}
	//.
	//.
	//.

	/*
	 * END
	 */
	

}




?>