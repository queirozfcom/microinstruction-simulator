<?php
	class Microinstruction extends BinaryString{
		/**
		 * Will construct a default Microinstruction. All 0's.
		 */
		public function __construct(){
                    parent::__construct();
                    
                    if(func_num_args()==1 and is_string(func_get_arg(0)) and !is_numeric(func_get_arg(0))){
                        $this->initAlias(func_get_arg(0));
                    }
		}
                private function initAlias($alias){
                    switch ($alias) {
                        case 'data_to_mdr':
                            $this[22]=1;
                            $this[24]=1;
                            break;
                        case 'increment_pc':
                            $this[14]= 1;
                            $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A+1'),8,0);
                            $this[10] = 1;
                            break;
                        case 'pc_to_mar_read':
                            $this[14]=1;
                            $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'),8,0);
                            $this[27]=1;
                            $this[25]=1;
                            break;
                        case 'mdr_to_mar':
                            $this[12]=1;
                            $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'),8,0);
                            $this[27]=1;
                            $this[25]=1;
                            break;
                        
                        default:
                            throw new MicroinstructionException('Unsupported alias: '.$alias);
                    }
                }
                public function setSourceRegister($regName){
                    switch (strtoupper($regName)) {
                        case 'MDR':
                            $this[12]='1';
                            break;
                        case 'R0':
                            $this[13]='1';
                            break;
                        case 'R1':
                            $this[12]='1';
                            $this[13]='1';
                            break;
                        case 'PC':
                            $this[14]='1';
                            break;
                        case 'AR1':
                            $this[12]='1';
                            $this[14]='1';
                            break;
                        case 'R2':
                            $this[19]='1';
                            break;
                        case 'R3':
                            $this[20]='1';
                            break;
                        case 'R4':
                            $this[20]='1';
                            $this[19]='1';
                            break;
                        case 'AR2':
                            $this[21]='1';
                            break;
                        default:
                            $this->throwInvalidRegNameException();
                            break;
                    }
                }
                public function setTargetRegister($regName){
                    switch ($regName) {
                        case 'MDR':
                            $this[23]='1';//MUX C config
                            $this[24]='1';
                            break;
                        case 'MAR':
                            $this[27]='1';
                            break;
                        case 'R0':
                            $this[8]='1';
                            break;
                        case 'R1':
                            $this[9]='1';
                            break;
                        case 'PC':
                            $this[10]='1';
                            break;
                        case 'AR1':
                            $this[11]='1';
                            break;
                        case 'R2':
                            $this[15]='1';
                            break;
                        case 'R3':
                            $this[16]='1';
                            break;
                        case 'R4':
                            $this[17]='1';
                            break;
                        case 'AR2':
                            $this[18]='1';
                            break;
                        case 'IR':
                            $this[29]='1';
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