<?php

require_once '/var/www/micro/protected/models/Program.php';
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainMemoryTest
 *
 * @author felipe
 */
class MainMemoryTest extends PHPUnit_Framework_TestCase{
   
    private $memory;
    
    protected function setUp()
    {
        
        $prog = new Program();
        $this->memory = $prog->mainMemory;
        
    }
    
    public function testAppendInstruction(){
       $instr = new Instruction("add", 'r1', false, 'r2', false);
       $this->memory->append($instr);
        
       $this->assertEquals($instr, $this->memory[0]);    
    }
    public function testNonBinaryStringThrowsException(){
        $instr = new ALU();
        
        $this->setExpectedException('MainMemoryException');
        $this->memory->append($instr);
    }

}

?>
