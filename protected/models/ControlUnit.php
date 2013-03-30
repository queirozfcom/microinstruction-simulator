<?php

class ControlUnit {

    private $decoder;

    public function __construct() {
        $this->decoder = new Decoder();
    }

    public function __get($a) {
        return $this->$a;
    }

    /**
     * This function decodes an Instruction (or, alternatively, a string) into an array of
     * Microinstruction objects, which are, in turn, executed by whoever called this.
     * 
     * @param Instruction|String $param An Instruction object or a string for commonly-used microprograms.
     */
    public function decode($param) {
        if (!$param instanceof BinaryString and !is_string($param))
            throw new ControlUnitException();

        return $this->decoder->decode($param);
    }

}

?>