<?php

require_once 'Instruction.php';
require_once 'helpers/MainMemoryException.php';

class MainMemory implements ArrayAccess, Countable {

    /**
     * Array of objects. Each object is a $MAXIMUMBITSPERLINE-bit BinaryString
     * array 
     */
    private $memoryArea;
    private $MAXIMUMBITSPERLINE = 32;
    private $NUMBEROFLINES = 1000;

    /**
     * Whatever was last read from the memory. 
     * @var BinaryString
     */
    private $returnedValue;

    public function __construct() {
        $this->memoryArea = [];
    }

    public function __get($a) {
        return $this->$a;
    }

    public function __toString() {
        $output = "";
        if (isset($this->memoryArea)) {
            foreach ($this->memoryArea as $line) {
                $output.=$line . "<br />";
            }
        }
        return $output;
    }

    public function offsetGet($offset) {

        if ($offset > $this->NUMBEROFLINES) 
            throw new MainMemoryException("Invalid offset: {$offset}. Maximum number of lines is {$this->NUMBEROFLINES}.");
        
            if(isset($this->memoryArea[$offset]))    
                return $this->memoryArea[$offset];
    }

    public function offsetExists($offset) {
        /*
         * empty
         */
    }

    public function offsetSet($offset, $value) {
        if ($offset > $this->NUMBEROFLINES) {
            throw new MainMemoryException('Maximum number of lines is ' . $this->NUMBEROFLINES);
        }
        if (!$value instanceof BinaryString)
            throw new MainMemoryException('trying to set into memory area a value that\'s not a BinaryString, ' . 'it\'s actually a ' . (is_object($value) ? get_class($value) : gettype($value)));

        $this->memoryArea[$offset] = $value;
    }

    public function offsetUnset($offset) {
        /*
         * empty
         */
    }

    public function count() {
        return count($this->memoryArea);
    }

    /**
     * Returns the last value returned as the result of a call to performRead()
     * 
     */
    public function getReturnedValue() {
        if (!$this->returnedValue instanceof BinaryString)
            throw new MainMemoryException("Returned value not valid binary string. Maybe you've reached the end.");
        return $this->returnedValue;
    }

    /**
     * 
     * Enter description here ...
     * @param int $line
     * @param $value
     */
    public function setMemoryLine($line, $value) {
        if (!$value instanceof BinaryString)
            throw new MainMemoryException();
        $this->memoryArea[$line] = $instruction;
    }

    /**
     * 
     * Enter description here ...
     * @param Instruction $instruction
     */
    public function append($binaryString) {
        if (!$binaryString instanceof BinaryString){
            throw new MainMemoryException("memory only supports Binary Strings as data");
        }
        if ($binaryString->getLength() != $this->MAXIMUMBITSPERLINE){
            throw new MainMemoryException("memory must support data word length");
        }
        $this->memoryArea[] = $binaryString;
    }

    /**
     * Takes an int/string $index as parameter and performs a memory Read. Does not return anything.
     * Access the returned value by calling getReturnedValue
     * @param Integer $line
     */
    public function performRead($index) {
        $index2int = (int) $index;
        $this->returnedValue = $this[$index2int];
    }

    /**
     * 
     * Enter description here ...
     * @param Integer $line
     * @param Instruction|BinaryString $data
     */
    public function performWrite($line, $data) {
        $this[$line] = $data;
    }

}

?>