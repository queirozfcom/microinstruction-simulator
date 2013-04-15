<?php
	require_once 'helpers/PrimitiveFunctions.php';
	require_once 'helpers/BinaryString.php';
	require_once 'helpers/RegisterException.php';

	/**
	 * 
	 * @author felipe
	 */
	class Register implements ArrayAccess{
		private $content;
		private $DATAWORDLENGTHINBITS=32;
                
                public function __construct($contents) {
                    if(!is_a($contents,'BinaryString')){
                        throw new RegisterException('Cannot set contents to a Register with anything other than a BinaryString');
                    }
                    $this->setContent($contents);
                    
                }
                
		/**
		 * Place a piece of binary data in this register
		 * @param BinaryString
		 */
		public function setContent($param) {
			if(!is_a($param,'BinaryString')) throw new RegisterException("Registers can only store Binary Data -> BinaryString objects");
			$this->content = $param;
		}
		public function __toString(){
			return (String) $this->content;
		}
                
                public function humanReadableForm(){
                    return $this->content->humanReadableForm();
                }
                
		public function asInt(){
			return $this->content->asInt();
		}
		public function getContent(){
			return ($this->content);
		}
		public function offsetGet($offset){
			return $this->content[$offset];
		}
		public function offsetExists($offset){
			//empty
		}
		public function offsetSet($offset, $value){
			$this->content[$offset]=$value;
		}
		public function offsetUnset($offset){
			//empty
		}
	}

?>