<?php
require_once "helpers/PrimitiveFunctions.php";
require_once 'helpers/BinaryString.php';
class ALU {
	public function __construct() {
		//todo
	}
	/**
	 * IMPORTANT: THIS WILL RETURN EITHER A BINARYSTRING OR AN INSTRUCTION!!
	 * 
	 * @param Register $a
	 * @param Register $b
	 * @param String $opCode
	 */
	public function operateOn(Register $a,Register $b, $opCode){
		if($opCode==="00000001"){
			return $this->compute("REPEATA", $a->getContent(), $b->getContent());
		}elseif($opCode==="00000010"){
			return $this->compute("REPEATB",$a->getContent(), $b->getContent());
		}elseif($opCode==="00000011"){
			return $this->compute("INCREMENTA",$a->getContent(),$b->getContent());
		}
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
				if ($arg1 instanceof Instruction) throw new UnexpectedValueException("Can't increment an Instruction");
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