<?php
require_once 'Microinstruction.php';
require_once 'Decoder.php';
require_once 'helpers/ControlUnitException.php';

  class ControlUnit {
  	private $decoder;
	private $flags;
	private $clock;
  	
	public function __construct(){
		$this->flags         = array('z'=>0,'n'=>0,'e'=>0,'l'=>0,'g'=>0,'c'=>0);
		$this->decoder       = new Decoder();
	}
	
  	public function __get($a){
		return $this->$a;
	}
	/**
	 * This function decodes an Instruction (or, alternatively, a string) into an array of
	 * Microinstruction objects, which are, in turn, executed by whoever called this.
	 * 
	 * @param Instruction|String $param An Instruction object or a string for commonly-used microprograms.
	 */
  	public function decode($param){
  		if(!$param instanceof BinaryString and !is_string($param)) throw new ControlUnitException();
  		return $this->decoder->decode($param);
  	}
  }


?>