<?php
require_once 'Instruction.php';
require_once 'helpers/MainMemoryException.php';
 class MainMemory implements ArrayAccess,Countable{
 	/**
 	 * Array of objects. Each object is a $MAXIMUMBITSPERLINE-bit BinaryString
 	 * array 
 	 */
 	private $memoryArea;
 	private $MAXIMUMBITSPERLINE=32;
 	private $NUMBEROFLINES=10;
 	/**
 	 * Whatever was last read from the memory. 
 	 * @var BinaryString
 	 */
 	private $returnedValue;
 	
 	public function __construct(){
            $this->memoryArea = array();
 	}
 	public function __get($a) {
 		return $this->$a;
 	}
 	public function __toString(){
 		$output="";
 		if (isset($this->memoryArea)) {
	 		foreach ($this->memoryArea as $line) {
	 			$output.=$line."<br />";
	 		} 			
 		}
 		return $output;
 	}
 	public function offsetGet($offset){
 		
 		return $this->memoryArea[$offset];
	}
	public function offsetExists($offset){
		/*
		 * empty
		 */	
	}
	public function offsetSet($offset, $value){
		if(!$value instanceof BinaryString) throw new MainMemoryException();
		
		$this->memoryArea[$offset]=$value;
	}
	public function offsetUnset($offset){
		/*
		 * empty
		 */
	}
	public function count(){
		return count($this->memoryArea);
	}
 	/**
 	 * Returns the last value returned as the result of a call to performRead()
 	 * 
 	 */
 	public function getReturnedValue(){
 		if(!$this->returnedValue instanceof BinaryString) throw new MainMemoryException("returned value not valid binary string");
 		return $this->returnedValue;
 	}
 	/**
 	 * 
 	 * Enter description here ...
 	 * @param int $line
 	 * @param $value
 	 */
 	public function setMemoryLine($line,$value) {
 		if(!$value instanceof BinaryString) throw new MainMemoryException();
 		$this->memoryArea[$line]=$instruction;
 	}
 	/**
 	 * 
 	 * Enter description here ...
 	 * @param Instruction $instruction
 	 */
 	public function append($binaryString){
 		if(!$binaryString instanceof BinaryString) throw new MainMemoryException("memory only supports Binary Strings as data");
 		if($binaryString->getLength()!=$this->MAXIMUMBITSPERLINE) throw new MainMemoryException("memory must support data word length");
 		$this->memoryArea[]=$binaryString;
 	}
 	/**
 	 * Takes an int/string $index as parameter and performs a memory Read. Does not return anything.
 	 * Access the returned value by calling getReturnedValue
 	 * @param Integer $line
 	 */
	public function performRead($line){
		$line2int    = (int)$line;
		$this->returnedValue = $this[$line2int];
	}
	/**
	 * 
	 * Enter description here ...
	 * @param Integer $line
	 * @param Instruction|BinaryString $data
	 */
	public function performWrite($line,$data){
		
	}
 }




?>