<?php
	class Microinstruction extends BinaryString{
		/**
		 * Will construct a default Microinstruction. All 0's.
		 */
		public function __construct(){
                    parent::__construct();
                    
                    if(func_num_args()===1 and is_string(func_get_arg(0)) and !is_numeric(func_get_arg(0))){
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
                        case 'mdr_to_mar_read':
                            $this[12]=1;
                            $this->setIntValueStartingAt(ALU::returnOpCodeForOperation('S=A'),8,0);
                            $this[27]=1;
                            $this[25]=1;
                            break;
                        case 'mdr_to_mar':
                            $this[12]=1;
                            $this[0]=1;
                            $this[27]=1;
                            break;
                        case 'mdr_to_ir':
                            $this[12]=1;
                            $this[0]=1;
                            $this[28]=1;
                            break;
                        default:
                            throw new MicroinstructionException('Unsupported alias: '.$alias);
                    }
                }
                
                /*
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
                */
                
                public function getTargetRegIndex(){
                    $targetRegisterIndexes = array(27,28,8,9,10,11,15,16,17,18);
                    
                    foreach($targetRegisterIndexes as $index){
                        if($this[$index]==1){
                            return $index;
                        }
                    }
                                   
                    if($this[24]==1){
                        if($this[23]==1 and $this[22]==0){
                            return 24;
                        }else{
                            throw new MicroinstructionException('You\'re trying to send a value to MDR but you haven\'t set MUX C value accordingly. If this is an operation of type LOAD DATA TO MDR, you should handle that first in  Program::runMicroinstruction() function ');
                        }
                    }
                    
                    
                }
                
                public function setWrite(){
                    //syslog(LOG_ALERT,'setwrite');
                    
                    $this[26]=1;
                    $this[25]=0;
                }
                public function setRead(){
                    $this[26]=0;
                    $this[25]=1;
                }
                
                public function setMuxAndALUValueForMOVFromSourceRegister($regName){
                    $sideSource = Decoder::getSideFromSourceName($regName);
                        
                    $muxVal = Decoder::getMUXValueFromRegister($regName);

                    if($sideSource == 'B'){
                        $this[21]  = $muxVal{0};
                        $this[20]  = $muxVal{1};
                        $this[19]  = $muxVal{2};

                        $intALUOpCode = ALU::returnOpCodeForOperation('S=B');
                    }elseif($sideSource == 'A'){
                        $this[14]  = $muxVal{0};
                        $this[13]  = $muxVal{1};
                        $this[12]  = $muxVal{2};

                        $intALUOpCode = ALU::returnOpCodeForOperation('S=A');
                    }else{
                        throw new DecoderException('Unsupported side. Must be either A or B.');
                    }
                    $this->setIntValueStartingAt($intALUOpCode, 8, 0);
                }
                
                public function setTargetIndexFromTargetRegister($regname){
                    if(!is_string($regname)){
                        throw new MicroinstructionException('Register names must be strings.');
                    }
                    switch (strtoupper($regname)) {
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
                        case 'MAR':
                            $this[27]=1;
                            break;
                        case 'IR':
                            $this[28]=1;
                            break;
                        case 'MDR':
                            $this[23]=1;
                            $this[24]=1;
                            break;
                        default:
                            throw new DecoderException('Unsupported register name: '.$regname);
                    }
                }
                
                private function throwInvalidRegNameException(){
                    throw new InstructionException('Invalid register name used.');
                }
                
		public function isMemoryRead(){
                    if($this[26]==0 and $this[25]==1){
                        return true;
                    }else{
                        return false;
                    }
		}
		public function isMemoryWrite(){
                    if($this[26]==1 and $this[25]==0){
                        return true;
                    }else{
                        return false;
                    }
		}
		public function isLoadReadDataIntoMDR(){

		}
		public function getALUOperationCode(){
                    return $this->getIntValueStartingAt(8, 0);
		}
		public function getMUXAValue(){ 
                    return $this[14].$this[13].$this[12];
		}
		public function getMUXBValue(){
                    return $this[21].$this[20].$this[19];
		}
                
                public function isMUXAActive(){
                    //returns true if MUX A value is != 0
                }
                public function isMUXBActive(){
                    
                }
		
	}

?>