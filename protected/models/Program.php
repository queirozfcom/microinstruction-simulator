<?php

class Program {

    public static function getValidTargetableRegisters() {
        $output = [];
        foreach (self::$targetableRegisters as $registerName) {
            $output[$registerName] = $registerName;
        }
        return $output;
    }

    private $flags = [
        'Z' => false,
        'N' => false,
        'E' => false,
        'L' => false,
        'G' => false,
        'R' => true//dummy flag, always set to true
    ];
    private static $targetableRegisters = ['R0', 'R1', 'R2', 'R3', 'R4', 'CONSTANT'];
    //registers begin
    private $R0;
    private $R1;
    private $R2;
    private $R3;
    private $R4;
    private $AR1;
    private $AR2;
    private $MDR; //memory data register
    private $MAR; //memory address register
    private $IR;
    private $PC;
    //registers end
    private $ALU;

    /**
     *
     * @var MainMemory
     */
    private $mainMemory;

    /**
     *
     * @var ControlUnit
     */
    private $controlUnit;
    //other
    private $currentMicroinstruction;
    private $nextInstruction;
    public $executionPhase = false;
    private $log = [];
    //private $fetchFirst = false;

    private $_currentMicroprogram = null;
    private $_currentMicroprogramIndex = 0;

    /**
     * Increment starts true because we want fetch to be the first thing to happen.
     * As this is thought out, this happens when the last microprogram was an 'increment'. 
     * That's why 'increment' is set to true at the start.
     * 
     * @var array 
     */
    private $_currentMicroProgramType = [
        'fetch' => false,
        'regular' => false,
        'increment' => true
    ];

    public function run() {
        while (true) {
            try {
                $this->runNextInstruction();
            } catch (ProgramException $exc) {
                if ($exc->getMessage() === 'End of program reached.')
                    return;
                else
                    throw $exc;
            }
        }
    }

    private function incrementMicroprogramIndex() {
        $this->_currentMicroprogramIndex +=1;
    }

    /**
     * This gets called by the controller.
     * 
     * This will check what the current microinstruction is and it will run the next one.
     * If the end of the current microprogram was reached, we will start the next one.
     * 
     */
    public function runNextMicroinstruction() {
        if (count($this->_currentMicroprogram) === $this->_currentMicroprogramIndex) {
            //we've executed the last microinstruction in this microprogram so we need to go to the next microprogram.
            if ($this->isFetch()) {
                $this->_currentMicroprogram = $this->controlUnit->decode($this->IR->getContent());
                $this->resetMicroprogramIndex();
                $this->runMicroinstruction($this->_currentMicroprogram[$this->_currentMicroprogramIndex]);
                $this->incrementMicroprogramIndex();
                $this->setIsRegular();
            } elseif ($this->isIncrement()) {
                $this->_currentMicroprogram = $this->controlUnit->decode("fetch");
                $this->resetMicroprogramIndex();
                $this->runMicroinstruction($this->_currentMicroprogram[$this->_currentMicroprogramIndex]);
                $this->incrementMicroprogramIndex();
                $this->setIsFetch();
            } elseif ($this->isRegular()) {
                $this->_currentMicroprogram = $this->controlUnit->decode("increment_pc");
                $this->resetMicroprogramIndex();
                $this->runMicroinstruction(($this->_currentMicroprogram[$this->_currentMicroprogramIndex]));
                $this->incrementMicroprogramIndex();
                $this->setIsIncrement();
            }
        } else {
            $this->runMicroinstruction($this->_currentMicroprogram[$this->_currentMicroprogramIndex]);
            $this->incrementMicroprogramIndex();
        }
    }

    private function resetMicroprogramIndex() {
        $this->_currentMicroprogramIndex = 0;
    }

    private function isFetch() {
        return $this->_currentMicroProgramType['fetch'];
    }

    private function isRegular() {
        return $this->_currentMicroProgramType['regular'];
    }

    private function isIncrement() {
        return $this->_currentMicroProgramType['increment'];
    }

    private function setIsFetch() {
        $this->_currentMicroProgramType['fetch'] = true;
        $this->_currentMicroProgramType['regular'] = false;
        $this->_currentMicroProgramType['increment'] = false;
    }

    private function setIsRegular() {
        $this->_currentMicroProgramType['fetch'] = false;
        $this->_currentMicroProgramType['regular'] = true;
        $this->_currentMicroProgramType['increment'] = false;
    }

    private function setIsIncrement() {
        $this->_currentMicroProgramType['fetch'] = false;
        $this->_currentMicroProgramType['regular'] = false;
        $this->_currentMicroProgramType['increment'] = true;
    }

    private function setNextMicroInstruction() {
        
    }

    public function appendToMemory(BinaryString $bs) {
        $this->mainMemory->append($bs);
    }

    public function reset() {
        $this->PC = new Register(new BinaryString(32, 0));
        $this->log = [];
    }

    public function addToLog(Microinstruction $micro) {
        $this->log[] = $micro;
    }

    public function __get($a) {
        return $this->$a;
    }

    public function getLog() {
        if (isset($this->log))
            return $this->log;
    }

    public function __construct() {
        $this->ALU = new ALU();
        $this->mainMemory = new MainMemory();
        $this->controlUnit = new ControlUnit();
        $this->R0 = new Register(new BinaryString(32, 0));
        $this->R1 = new Register(new BinaryString(32, 0));
        $this->R2 = new Register(new BinaryString(32, 0));
        $this->R3 = new Register(new BinaryString(32, 0));
        $this->R4 = new Register(new BinaryString(32, 0));
        $this->AR1 = new Register(new BinaryString(32, 0));
        $this->AR2 = new Register(new BinaryString(32, 0));
        $this->PC = new Register(new BinaryString(32, 0));
        $this->IR = new Register(new BinaryString(32, 0));
        $this->MDR = new Register(new BinaryString(32, 0));
        $this->MAR = new Register(new BinaryString(32, 0));
    }

    /**
     * Returns an associative array where each key is a register and its value is that register's content
     * For testing purposes mainly.
     * @return array The registers and their values
     */
    public function dumpInfo() {

        $output = [];
        $output["R0"] = $this->R0;
        $output["R1"] = $this->R1;
        $output["R2"] = $this->R2;
        $output["R3"] = $this->R3;
        $output["R4"] = $this->R4;
        $output["PC"] = $this->PC;

        $output["AR1"] = $this->AR1;
        $output["AR2"] = $this->AR2;
        $output["MDR"] = $this->MDR;
        $output["MAR"] = $this->MAR;
        $output["IR"] = $this->IR;
        
        $output["Z"] = ($this->flags['Z'])? '1' : '0';
        $output["N"] = ($this->flags['N'])? '1' : '0';
        $output["E"] = ($this->flags['E'])? '1' : '0';
        $output["L"] = ($this->flags['L'])? '1' : '0';
        $output["G"] = ($this->flags['G'])? '1' : '0';
        
        $output['log'] = array_map(function($e) {
                    return ($e->__toString());
                }, $this->log);

        return $output;
    }

    public function runNextInstruction() {
        $this->fetch();

        $nextInstruction = $this->IR->getContent();

        if (get_class($nextInstruction) === 'BinaryString')
            throw new ProgramException('End of program reached.');

        $this->runInstruction($nextInstruction);
        $this->incrementPC();
    }

    public function incrementPC() {
        $this->runMicroinstruction(new Microinstruction('increment_pc'));
    }

    public function getFlag($flag) {
        if (!in_array(strtoupper($flag), array_keys($this->flags)))
            throw new ProgramException("Flag $flag is not valid for this machine");

        return $this->flags[$flag];
    }

    /**
     * Start routine, tells the program to fetch the first instruction in the $mainMemory 
     * and bring it over to the IR Register.
     */
    public function fetch() {

        $fetchMicroprogram = $this->controlUnit->decode("fetch");

        foreach ($fetchMicroprogram as $microinstruction) {

            try {
                $this->runMicroinstruction($microinstruction);
            } catch (MainMemoryException $e) {
                if ($e->getMessage() === "Returned value not valid binary string. Maybe you've reached the end.")
                    throw new ProgramException('End of program reached.');
                else
                    throw $e;
            }
        }
    }

    private function runInstruction(Instruction $inst) {
        $microProgram = $this->controlUnit->decode($inst);
        foreach ($microProgram as $microinstruction) {
            $this->runMicroinstruction($microinstruction);
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
    private function runMicroinstruction(Microinstruction $microinstruction) {
        $this->setCurrentMicroinstruction($microinstruction);
        $this->addToLog($microinstruction);

        if ($microinstruction->isBranch()) {
            $this->evaluateBranch($microinstruction); //if flag is set, branch will be performed in there
        } else {
            if ($microinstruction == new Microinstruction('pc_to_mar_read')) {
                $this->MAR->setContent($this->PC->getContent());
                $this->performMemoryRead();
            } elseif ($microinstruction == new Microinstruction('mdr_to_mar'))
                $this->MAR->setContent($this->MDR->getContent());
            elseif ($microinstruction == new Microinstruction('mdr_to_mar_read')) {
                $this->MAR->setContent($this->MDR->getContent());
                $this->performMemoryRead();
            } elseif ($microinstruction == new Microinstruction('data_to_mdr')) {
                $this->MDR->setContent($this->mainMemory->getReturnedValue());
            } elseif ($microinstruction == new Microinstruction('mdr_to_ir'))
                $this->IR->setContent($this->MDR->getContent());
            elseif ($microinstruction == new Microinstruction('increment_pc'))
                $this->PC->setContent(new BinaryString(32, $this->PC->getContent()->asInt() + 1));
            else {
                $targetRegName = self::getRegisterNameFromTargetIndex($microinstruction->getTargetRegIndex());
                $leftRegName = self::getRegisterNameFromMUXAValue($microinstruction->getMUXAValue());
                $rightRegName = self::getRegisterNameFromMUXBValue($microinstruction->getMUXBValue());

                $ALUOpCode = $microinstruction->getALUOperationCode();

                if (is_null($leftRegName)) {
                    $leftReg = null;
                    $leftRegContents = null;
                } else {
                    $leftReg = $this->$leftRegName;
                    $leftRegContents = $leftReg->getContent();
                }

                if (is_null($rightRegName)) {
                    $rightReg = null;
                    $rightRegContents = null;
                } else {
                    $rightReg = $this->$rightRegName;
                    $rightRegContents = $rightReg->getContent();
                }

                $this->resetFlags();
                //result is a BinaryString
                $result = $this->ALU->operateOn($leftRegContents, $rightRegContents, $ALUOpCode);

                $this->setFlags($result);


                if (!ALU::isCMPOperation($ALUOpCode)) {
                    //CMP is the same as SUB but no results are stored in the target register

                    $this->$targetRegName->setContent($result);

                    if ($microinstruction->isMemoryRead())
                        $this->performMemoryRead();

                    if ($microinstruction->isMemoryWrite())
                        $this->performMemoryWrite();
                }
            }
        }
    }

    private function evaluateBranch(Microinstruction $microinstruction) {

        $branchFlag = $microinstruction->getBranchFlagType();

        $offset = $microinstruction->getBranchOffset();

        //rather than defining new logic for branches that DON'T depend upon flags (as is the case with unconditional jumps), i've set a dummy variable 'R' which is always true and therefore the branch always take place
        if ($this->flags[$branchFlag]) {

            $currentPCContentsAsInt = $this->PC->asInt();

            $newPCContentsAsInt = $currentPCContentsAsInt + ($offset);

            $newPCContents = new BinaryString();
            $newPCContents->setIntegerValue($newPCContentsAsInt);

            $this->PC->setContent($newPCContents);
        }
    }

    private static function getRegisterNameFromTargetIndex($index) {
        //$index is an integer

        switch ($index) {
            case 8:
                return 'R0';
            case 9:
                return 'R1';
            case 10:
                return 'PC';
            case 11:
                return 'AR1';
            case 15:
                return 'R2';
            case 16:
                return 'R3';
            case 17:
                return 'R4';
            case 18:
                return 'AR2';
            case 24:
                return 'MDR';
            case 27:
                return 'MAR';
            case 28:
                return 'IR';
            default:
                throw new ProgramException("Invalid target Index: $index");
        }
    }

    private static function getRegisterNameFromMUXAValue($MUXAValue) {
        switch ($MUXAValue) {
            case '000':
                return null;
            case '001':
                return 'MDR';
            case '010':
                return 'R0';
            case '011':
                return 'R1';
            case '100':
                return 'PC';
            case '101':
                return 'AR1';
            default:
                throw new ProgramException("$MUXAValue is not a valid state for MUX A");
                break;
        }
    }

    private static function getRegisterNameFromMUXBValue($MUXBValue) {
        switch ($MUXBValue) {
            case '000':
                return null;
            case '001':
                return 'R2';
            case '010':
                return 'R3';
            case '011':
                return 'R4';
            case '100':
                return 'AR2';
            default:
                throw new ProgramException("$MUXBValue is not a valid state for MUX B");
                break;
        }
    }

    /**
     * For GUI purposes...
     * @param Microinstruction $micro
     */
    private function setCurrentMicroinstruction(Microinstruction $micro) {
        $this->currentMicroinstruction = $micro;
    }

    /**
     * 
     * Enter description here ...
     * @param String|Instruction $param
     */
    private function setCurrentInstruction($param) {
        $this->_currentInstruction = $param;
    }

    /**
     * For a microinstruction that involves moving data, the ALU will be used, and therefore we must
     * set a target register, where the ALU output will be placed.
     * @param Microinstruction $micro
     */
    private function returnEnabledRegisterToReceiveData(Microinstruction $micro) {
        
    }

    /**
     * This takes whatever is in MAR and feeds it to the memory. To access the returned data, do
     * $mainMemory->getReturnedValue();
     */
    private function performMemoryRead() {

        //syslog(LOG_ALERT,'memread');
        $intval = $this->MAR->asInt();
        $this->mainMemory->performRead($intval);
    }

    /**
     * This function does the following:
     * Writes the contents of $this->memDataReg on the memory, on line $this->memAddrReg
     */
    private function performMemoryWrite() {
        $index = $this->MAR->asInt();
        $data = $this->MDR->getContent();

        //syslog(LOG_ALERT,'memwrite');
        $this->mainMemory->performWrite($index, $data);
    }

    /**
     * This function gets the last piece of data returned from a memory read and places it
     * in the MDR register
     */
    private function loadReadDataIntoMDR() {
        
    }

    private function setFlags(BinaryString $result) {

        //TODO a flag CARRy? como fazer isso? não fazer.

        if ($result->asInt() === 0) {
            $this->flags['Z'] = true;
            $this->flags['E'] = true;
        } elseif ($result->asInt() < 0) {
            $this->flags['N'] = true;
            $this->flags['L'] = true;
        } elseif ($result->asInt() > 0)
            $this->flags['G'] = true;
        else
            throw new ProgramException('Something is wrong: the number "' . $result->asInt() . '" looks like it\'s not an Integer. What is it, then?');
    }

    private function resetFlags() {
        $this->flags['Z'] = false;
        $this->flags['N'] = false;
        $this->flags['E'] = false;
        $this->flags['L'] = false;
        $this->flags['G'] = false;
        $this->flags['R'] = true;
    }

}

?>