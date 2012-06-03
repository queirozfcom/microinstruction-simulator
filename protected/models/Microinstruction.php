<?php
	class Microinstruction extends BinaryString{
		private $ALUOPERATIONCODEINDEXRANGE = array(0,1,2,3,4,5,6,7);
		
		/**
		 * Will construct a default Microinstruction. All 0's.
		 */
		public function __construct(){
                        $this->length = MyConfig::$MICROINSTRUCTIONLENGTH;
                        
			for ($i = 0; $i < $this->length; $i++) {
				$this[$i]="0";
			}
		}
                public function setALUCode($operationName){
                    switch ($operationName) {
                        case 'SEQUALSA':
                            break;
                        case 'SEQUALSB':
                            break;

                        default:
                            throw new ALUException('Unsupported ALU operation: '.$operationName);
                    }
                }
                public function setSourceRegister($regName){
                    switch (strtoupper($regName)) {
                        case 'MDR':
                            $this[12]=1;
                            break;
                        case 'R0':
                            $this[13]=1;
                            break;
                        case 'R1':
                            $this[12]=1;
                            $this[13]=1;
                            break;
                        case 'PC':
                            $this[14]=1;
                            break;
                        case 'AR1':
                            $this[12]=1;
                            $this[14]=1;
                            break;
                        case 'R2':
                            $this[19]=1;
                            break;
                        case 'R3':
                            $this[20]=1;
                            break;
                        case 'R4':
                            $this[20]=1;
                            $this[19]=1;
                            break;
                        case 'AR2':
                            $this[21]=1;
                            break;
                        default:
                            $this->throwInvalidRegNameException();
                            break;
                    }
                }
                public function setTargetRegister($regName){
                    switch ($regName) {
                        case 'MDR':
                            $this[23]=1;//MUX C config
                            $this[24]=1;
                            break;
                        case 'MAR':
                            $this[27]=1;
                            break;
                        case 'R0':
                            $this[8]=1;
                            break;
                        case 'R1':
                            $this[9]=1;
                            break;
                        case 'PC':
                            $this[10]=1;
                            break;
                        case 'AR1':
                            $this[11]=1;
                            break;
                        case 'R2':
                            $this[15]=1;
                            break;
                        case 'R3':
                            $this[16]=1;
                            break;
                        case 'R4':
                            $this[17]=1;
                            break;
                        case 'AR2':
                            $this[18]=1;
                            break;
                        case 'IR':
                            $this[29]=1;
                           break;
                        default:
                            $this->throwInvalidRegNameException();
                    }
                }
                
                private function throwInvalidRegNameException(){
                    throw new InstructionException('Invalid register name used.');
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