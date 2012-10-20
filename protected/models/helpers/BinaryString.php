<?php
	require_once 'PrimitiveFunctions.php';
	require_once 'BinaryStringException.php';
	class BinaryString implements ArrayAccess,Countable{
		/**
		 * if most significant bit (leftmost) is 1, it means this number is written as two's-complement
		 */
		protected $length=32;
		protected $string;
		/**
		 * @example new BinaryString()          ->creates a default 32-bit binary string, every bit set to 0
		 * @example new BinaryString($length)   ->creates a $length-bit binary string every bit set to 0
		 * @example new BinaryString($length,$intVal) ->creates a $size-bit BinaryString and
		 * 	sets $intVal as binary as its string
		 */
		public function __construct(){
			$numberOfArguments = func_num_args();
			if($numberOfArguments===0){
				$this->setIntegerValue(0);
			}elseif($numberOfArguments===1){
				$length   = func_get_arg(0);
				if(!is_int($length)) throw new BinaryStringException("length must be an int value");
				
				$this->length=$length;					
				$this->setIntegerValue(0);
		
			}elseif($numberOfArguments===2){
				$length  = func_get_arg(0);
				if(!is_int($length)) {
                                    throw new BinaryStringException("length must be an int value");
                                }
				$intVal  = func_get_arg(1);
                                
                                if(!is_numeric($intVal)) {
                                    throw new BinaryStringException("intVal must be an int value or a numeric string");
                                }
                                if($intVal > pow(2,32)){
                                    //this is because php can't override the OS number limits.
                                    throw new BinaryStringException("Number too large.");
                                }
                                
                                if(is_numeric($intVal) and is_int($intVal)==false){
                                    $intVal = intval($intVal);
                                }
                                
                                $this->length = $length;
                                $this->setIntegerValue($intVal);
			}
		}
		public function getLength(){
			return $this->length;
		}
		public function __toString(){
//                        $output = "";
//                        
//                        foreach($this as $index=>$value){
//                            $output.=$value." ";
//                        }
//                        return trim($output);
			
                    return (String)$this->string;
                    
		}
                public function humanReadableForm(){
                    return $this->asInt();
                }
		public function showDetailedDescription(){
			$return = "";
			for ($i = 0; $i < $this->length; $i++) {
				$return.="[".$i."]:".$this[$i]." ";
			}
			echo (String) trim($return);
		}
		/**
		 * Converts $num to binary, pads it to $length bits and stores it as a string.
		 * @param  $num
		 */
		public function setIntegerValue($intVal){
			if(!is_int($intVal)) {
                            throw new BinaryStringException("you tried to set an int value to a binary string using something which isn't an int");
                        }
                        if($intVal>pow(2,($this->length)-1)){
                            throw new BinaryStringException("Number too large.");
                        }
			
			$paddedBinaryString = int2PaddedBinaryString($intVal,$this->length);
			
			$this->string       = $paddedBinaryString;
		}
		public function asInt(){
			if($this[$this->length-1]==0){
				//is positive
				$int       = bindec($this->string);
				return $int;
			}elseif ($this[$this->length-1]==1){
				//is negative->is written as 2's complement
				$onesComplement="";
				for($i=$this->length-1;$i>=0;$i--) {
					if($this[$i]==0){
						$onesComplement .= "1";
					}elseif ($this[$i]==1){
						$onesComplement .= "0";
					}
				}
				$intValue = bindec($onesComplement);
				
				$intValue = $intValue+1;
				
				$intValue = $intValue - 2*($intValue);
				
				return $intValue;
			}
			
		}
		public function offsetGet($offset){
			/*
			 * why the newOffset? Because BinaryStrings are often written backwards.
			 */
			$newOffset   = $this->length-($offset+1);
			return $this->string[$newOffset];
		}
		public function offsetExists($offset){
			
		}
		public function offsetSet($offset, $value){
			/*
			 * why the newOffset? Because BinaryStrings are often written backwards.
			 */
			$newOffset   = $this->length-($offset+1);
			$this->string[$newOffset]=$value;
		}
		public function offsetUnset($offset){
			return false;
		}
		public function count(){
			return count($this->string);
		}
		public function increment(){
			$decimal        = $this->asInt();
			$increment      = $decimal+1;
			
			$temp           = new BinaryString($this->length,$increment);
			
			$this->string   = $temp->string;
		}
		/**
		 * This function sets the value 0 to every index supplied as argument 
		 * @example setZero(1,2,3) This sets the value 0 to the indexes 1,2 and 3
		 * @throws Exception
		 */
		public function setZero(){
			$numberOfArgumentsPassed = func_num_args();
			if ($numberOfArgumentsPassed===0) throw new MicroinstructionException("TOO FEW ARGUMENTS");
			elseif($numberOfArgumentsPassed>$this->length)throw new MicroinstructionException("TOO MANY ARGUMENTS");
			else{
				$args    = func_get_args();
				foreach ($args as $arg){
					$this->set("zero",$arg);
				}
			}
		}
		/**
		 * This function sets the value 1 to every index supplied as argument 
		 * 
		 * @example see example for setZero()
		 * 
		 * @throws Exception
		 */
		public function setOne(){
			$numberOfArgumentsPassed = func_num_args();
			
			if ($numberOfArgumentsPassed===0) throw new MicroinstructionException("TOO FEW ARGUMENTS");
			elseif($numberOfArgumentsPassed>$this->length)throw new MicroinstructionException("TOO MANY ARGUMENTS");
			else{
				$args    = func_get_args();
				foreach ($args as $arg){
					$this->set("one",$arg);
				}
			}
		}
		/**
		 * Internal function that only gets called by set*** functions, above
		 */
		private function set($what,$index){
			if($what=="zero"){
                                
				$this[$index] = 0;
				return;
			}elseif($what=="one"){
				$this[$index] = 1;
				return;
			}
		}
		/**
		 * For testing purposes, I'll refer to this functions as func2, due to its large name.
		 * 
		 * @param int $intVal
		 * @param int $length
		 * @param int $startingIndex
                 * @example setIntValueStratingAt(34,6,5) -> 34 in binary is 100010 
                 * 
		 * @throws BinaryStringException
		 * @throws Exception
		 */
		public function setIntValueStartingAt($intVal,$length,$startingIndex){
			if(($length+$startingIndex)>$this->length) throw new BinaryStringException("you're trying to store a number that does not fit into your string");
			if($intVal>pow(2, $length))                throw new BinaryStringException(" this number is too big for the given length");
			$paddedBinString   = int2PaddedBinaryString($intVal,$length);
			$reversedString    = strrev($paddedBinString);
			
			for ($i = 0; $i < $length; $i++) {
				$this[$i+$startingIndex] = $reversedString[$i];
			}
		}
                
                public function getIntValueStartingAt($length,$startingIndex){
                    if($startingIndex+$length>$this->length){
                        throw new BinaryStringException('$startingIndex + $length cannot exceed this BinaryString\' length');
                    }
                    
                    $string = "";
                    for($i=0;$i<=$length-1;$i++){
                        $string.= $this[$i];
                    }
                    
                    $reversedString = strrev($string);
                    return bindec($reversedString);
                }
                
                
	}

?>