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
		const DATAWORDLENGTHINBITS=16;
		/**
		 * Place a piece of binary data in this register
		 * @param BinaryString
		 */
		public function setContent($param) {
			if(!$param instanceof BinaryString) throw new RegisterException("Registers can only store Binary Data -> BinaryString objects");
			$this->content = $param;
		}
		public function __toString(){
			return (String) $this->content;
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