<?php
	require_once 'ValueObject.php';
	class VOInstruction extends ValueObject{
		
		private $mnemonic;
		
		private $arg1;
		private $indirection1;
		private $constant1;
		
		private $arg2;
		private $indirection2;
		private $constant2;
		
		/**
                 * @example $vo = new VOInstruction('ADD','R1',false,null,'constant',true,'1234'); -> this would create ADD(R1,[#1234]) , add to R1 the value stored at position 1234 and the write the result back on to register R1
                 *                  
                 * @param type $mnemonic
                 * @param type $arg1
                 * @param type $indirection1
                 * @param type $constant1
                 * @param type $arg2
                 * @param type $indirection2
                 * @param type $constant2
                 * @throws InvalidArgumentException
                 * @throws InstructionException 
                 */
		public function __construct($mnemonic,$arg1,$indirection1,$constant1,$arg2,$indirection2,$constant2){
			if(!is_string($mnemonic)){            
				throw new InvalidArgumentException('$mnemonic must be a string');
			}
                        if(strtolower($arg1)==='constant'){
                            if(!is_numeric($constant1)){ 
                                    throw new InstructionException("constants must be numeric");
                            }
			}
			if(strtolower($arg2)==='constant'){
				if(!is_numeric($constant2)){	
					throw new InstructionException("constants must be numeric");
				}
			}
			
			$this->mnemonic     = $mnemonic;
			$this->arg1         = $arg1;
			$this->indirection1 = $indirection1;
			$this->constant1    = $constant1;
			$this->arg2         = $arg2;
			$this->indirection2 = $indirection2;
			$this->constant2    = $constant2;
		}
                
                public function representsABranch(){
                    if(strtoupper($this->mnemonic)==='BRZ' || strtoupper($this->mnemonic)==='BRN' ||
                            strtoupper($this->mnemonic)==='BRE' || strtoupper($this->mnemonic)==='BRL' ||
                            strtoupper($this->mnemonic)==='BRG')
                        return true;
                    else 
                        return false;
                }
                
		/**
		 * @return the $mnemonic
		 */
		public function getMnemonic() {
			return $this->mnemonic;
		}
		/**
		 * @return the $arg1
		 */
		public function getArg1() {
			return $this->arg1;
		}
		/**
		 * @return the $indirection1
		 */
		public function getIndirection1() {
			return $this->indirection1;
		}
			/**
		 * @return the $constant1
		 */
		public function getConstant1() {
			return $this->constant1;
		}
		/**
		 * @return the $arg2
		 */
		public function getArg2() {
			return $this->arg2;
		}
		/**
		 * @return the $indirection2
		 */
		public function getIndirection2() {
			return $this->indirection2;
		}
		/**
		 * @return the $constant2
		 */
		public function getConstant2() {
			return $this->constant2;
		}
		public function hasIndirection(){
			if($this->indirection1===true or $this->indirection2===true){
				return true;
			}
			return false;
		}
		public function arg1IsIndirection(){
			if($this->indirection1===true){
				return true;
			}
			return false;
		}
		public function arg2IsIndirection(){
			if($this->indirection2===true){
				return true;
			}
			return false;
		}
		public function hasConstant(){
			if(strtolower($this->arg1)==='constant' or strtolower($this->arg2)==='constant'){
			 	return true;
			}
			return false;
		}
		public function arg1IsConstant(){
			if(strtolower($this->arg1)==='constant'){
				return true;
			}
			return false;
		}
		public function arg2IsConstant(){
			if(strtolower($this->arg2)==='constant'){
				return true;
			}
			return false;
		}
		public function hasOnlyOneParameter(){
			if(is_null($this->arg2)){
				return true;
			}else{
				return false;
			}
		}
		public function hasTwoParameters(){
			$returnValue = $this->hasOnlyOneParameter();
			return !$returnValue;	
		}
	}