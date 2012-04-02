<?php
require_once 'helpers/MicroinstructionException.php';
require_once 'helpers/BinaryString.php';
require_once 'config/config.php';

	class Microinstruction extends BinaryString{
		private   $ALUOPERATIONCODEINDEXRANGE = array(0,1,2,3,4,5,6,7);
		
		/**
		 * Will construct a default Microinstruction. All 0's.
		 */
		public function __construct(){
			for ($i = 0; $i < Config::MICROINSTRUCTIONLENGTH; $i++) {
				$this->string[$i]="0";
			}
		}
		public function isMemoryRead(){
		
		}
		public function isMemoryWrite(){
		
		}
		public function isLoadReadDataIntoMDR(){

		}
		public function getALUOperationCode(){

		}
		public function getMUXACode(){ 

		}
		public function getMUXBCode(){
			
		}
		
	}

?>