<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InstructionTest
 *
 * @author felipe
 */
class InstructionTest extends CDbTestCase{
    
    
    private $validInstructions;
    
    private $simplestPossibleVO;

    private $voinst;

    private $with_constant1;

    private $arg2_is_null;
    
    private $with_constant2;
    
    protected function setUp()
    {
        $this->validInstructions = Instruction::getValidInstructions();
        $this->errorvo = new VOInstruction("", "", false,null,"r", false, null);
        $this->voinst = new VOInstruction("MOV","R2",false,null,"R0",true,null);
        $this->with_constant1 = new VOInstruction("ADD","CONSTANT",true,1000,"r0",false,null);
        $this->with_constant2 = new VOInstruction("mov","CONSTANT",true,556,"r0",false,null);
        $this->arg2_is_null = new VOInstruction('SHL','R1',false,null,null,false,null);
        

    }
    public function testStaticMethodCallWorks(){
        $var = Instruction::getValidInstructions();
    }
    
    public function testReturnAnArrayWhenGetValidInstructions(){
        $this->assertNotEmpty($this->validInstructions);
        $this->assertArrayHasKey('ADD', $this->validInstructions);
    }
    public function testAddIndexHasAddValue(){
        $this->assertEquals($this->validInstructions['ADD'],'ADD');
    }
    
    public function testAllElementsHaveCorrespondingValues(){
         foreach($this->validInstructions as $key=>$value){
             $this->assertEquals($key,$value);
         }
    }
    
   
}

?>
