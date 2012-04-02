<?php
 require_once 'MainMemory.php'; 
 require_once 'Register.php'; 
 require_once 'ControlUnit.php';
 require_once 'ALU.php';
 
 class Program {
     
        public static function getValidTargetableRegisters(){
            $output = array();
            foreach(self::$targetableRegisters as $registerName){
                $output[$registerName]=$registerName;
            }
            return $output;
        }     
        private static $targetableRegisters = array('R0','R1','R2','R3','R4','CONSTANT');
        
 	//registers begin
	private $R0;
	private $R1;
	private $R2;
	private $R3;
	private $R4;
	private $AR1;
	private $AR2;
	
	private $MDR;//memory data register
	private $MAR;//memory address register
	
	private $IR;
	private $PC;
	//registers end
	private $ALU;
	private $mainMemory;
	private $controlUnit;
	//other
	private $currentMicroinstruction;
	private $currentInstruction;
	private $nextInstruction;
	public  $executionPhase = false;
	private $microInstructionLog;
	
        public function appendToMemory(BinaryString $bs){
            $this->mainMemory->append($bs);
        }
        
	public function addToLog(Microinstruction $micro){
		$this->microInstructionLog[] = $micro;
	}
	public function __get($a){
		return $this->$a;
	}
	public function getLog(){
		if(isset($this->microInstructionLog) ){
			 return $this->microInstructionLog;
		}
	}
	
	public function __construct(){
		$this->ALU          = new ALU();
		$this->mainMemory   = new MainMemory();
		$this->controlUnit  = new ControlUnit();
		$this->R0           = new Register();
		$this->R1           = new Register();
		$this->R2           = new Register();
		$this->R3           = new Register();
		$this->R4           = new Register();
		$this->AR1          = new Register();
		$this->AR2          = new Register();
		$this->PC           = new Register();
		$this->IR           = new Register();
		$this->MDR          = new Register();
		$this->MAR          = new Register();
	}
	/**
	 * Returns an associative array where each key is a register and its value is that register's content
	 * For testing purposes mainly.
	 * @return array The registers and their values
	 */
	public function dumpRegisters(){
		$registers         = array();
		$registers["R0"]   = $this->R0;
		$registers["R1"]   = $this->R1;
		$registers["R2"]   = $this->R2;
		$registers["R3"]   = $this->R3;
		$registers["R4"]   = $this->R4;
		$registers["AR1"]  = $this->AR1;
		$registers["AR2"]  = $this->AR2;
		$registers["MDR"]  = $this->MDR;
		$registers["MAR"]  = $this->MAR;
		$registers["IR"]   = $this->IR;
		
		return $registers;		
	}
        
	public function showNextInstruction(){
		$memoryIndex = $this->PC->asInt();
		if($this->executionPhase){
			echo $this->mainMemory[$memoryIndex];
		}
	}
	/**
	 * Start routine, tells the program to fetch the first instruction in the $mainMemory 
	 * and bring it over to the IR Register.
	 */
	public function bootstrap(){
		$this->setCurrentInstruction("bootstrap");
		$this->executionPhase     = true;
		
		$this->PC->setContent(new BinaryString(0));
	}
	public function fetch(){
		$this->setCurrentInstruction("fetch");
		
		$bootstrapMicroprogram  = $this->controlUnit->decode("fetch");
		foreach ($bootstrapMicroprogram as $microinstruction){
			$this->runMicroinstruction($microinstruction);
			$this->addToLog($microinstruction);			
		}
	}
	public function runInstruction(BinaryString $inst){
		$microProgram   = $this->controlUnit->decode($inst);
		foreach ($microProgram as $microinstruction){
			$this->runMicroinstruction($microinstruction);
			$this->addToLog($microinstruction);
		}
	}
 	/**
	 * This function will  take microinstructions from the decoder as arguments 
	 * and execute the ACTUAL WORK, that is move data from/to registers, and so on
	 * whatever the microinstruction "instructs".
	 * The microinstructions that "arrive" here from the decoder will be of 4 types mainly:
	 * - microinstructions to move data from one register to another, passing through the ALU
	 * - microinstructions that tell the ALU to perform some calculation on its operands (A,B) and
	 *   load the result in a target Register
	 * - move read data from last memory read into MDR
	 * - microinstructions that require a memory read- or write- routine. 
	 * 
	 * @param Microinstruction $microinstruction
	 */
	private function runMicroinstruction(Microinstruction $microinstruction){
		$this->setCurrentMicroinstruction($microinstruction);


		if ($microinstruction->isMemoryRead()) {

		}elseif($microinstruction->isMemoryWrite()){

		}elseif($microinstruction->isLoadReadDataIntoMDR()){
		
		}else{
			
			/*the microinstruction is neither a memory read nor write, or load read data into MDR,so
			* it involves passing data around, and will also include performing an ALU operation (in fact,
			* even if it's just a simple MOV, it will require ALU involvement because we will tell it
			* to output the inputs, [S=A or S=B])
			*/
			
		}			
	}
	/**
	 * For GUI purposes...
	 * @param Microinstruction $micro
	 */
	private function setCurrentMicroinstruction(Microinstruction $micro){
		$this->currentMicroinstruction=$micro;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param String|Instruction $param
	 */
	private function setCurrentInstruction($param){
			$this->currentInstruction=$param;
	}
	/**
	 * For a microinstruction that involves moving data, the ALU will be used, and therefore we must
	 * set a target register, where the ALU output will be placed.
	 * @param Microinstruction $micro
	 */
	private function returnEnabledRegisterToReceiveData(Microinstruction $micro){

	}
	/**
	 *This takes whatever is in MAR and feeds it to the memory. To access the returned data, do
	 *$mainMemory->getReturnedValue();
	 */
	private function performMemoryRead(){

	}
	/**
	 * This function does the following:
	 * Writes the contents of $this->memDataReg on the memory, on line $this->memAddrReg
	 */
	private function performMemoryWrite(){

	}
	/**
	 * This function gets the last piece of data returned from a memory read and places it
	 * in the MDR register
	 */
	private function loadReadDataIntoMDR(){

	}

	public function incrementPC(){
		$microProgram=$this->controlUnit->decode("incrementPC");
		foreach ($microProgram as $microinstruction){
			$this->runMicroinstruction($microinstruction);
			$this->addToLog($microinstruction);
		}
		
	}
 }
  
 ?>