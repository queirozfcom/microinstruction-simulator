<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FactoryTest
 *
 * @author felipe
 */
class FactoryTest extends CDbTestCase{
    private $simplestPossibleVO;

    private $voinst;

    private $with_constant1;

    private $arg2_is_null;
    
    private $with_constant2;
    
    private $with_const1_too_large;
    
    private $two_constants;
    
    public function setUp() {
        $this->errorvo = new VOInstruction("", "", false,null,"r", false, null);
        $this->voinst = new VOInstruction("MOV","R2",false,null,"R0",true,null);
        $this->with_constant1 = new VOInstruction("ADD","CONSTANT",true,1000,"r0",false,null);
        $this->with_constant2 = new VOInstruction("mov","CONSTANT",true,556,"r0",false,null);
        $this->arg2_is_null = new VOInstruction('SHL','R1',false,null,null,false,null);
        $this->with_const1_too_large = new VOInstruction("MOV","constant",false,42866662237598321747823656,"r0",false,null);
        $this->two_constants = new VOInstruction("XOR",'constant',false,38743,'constant',true,93872);
        
    }
    
    public function test_vo_with_no_constants_yields_an_array(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->voinst);
        $this->assertTrue(is_array($arr));
    }
    public function test_array_is_size_1(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->voinst);
        $this->assertEquals(1,count($arr));
    }
    public function test_array_has_only_one_istruction(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->voinst);
        $this->assertTrue($arr[0] instanceof Instruction);
        
        $this->assertEquals("MOV",$arr[0]->getMnemonic());
    }
    public function test_vo_with_one_const_yields_array_with_two_elements(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->with_constant1);
        $this->assertEquals(2, count($arr));
    }
    public function test_vo_with_one_const_yields_array_with_one_instruction_and_one_constant(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->with_constant1);
        $this->assertTrue($arr[0] instanceof Instruction);
        $this->assertTrue($arr[1] instanceof BinaryString);
    }
    public function test_one_const_vo_gets_correct_number(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->with_constant1);
        $this->assertEquals(1000,$arr[1]->asInt());
    }
    public function test_exception_gets_for_too_big_a_number(){
        $this->setExpectedException('BinaryStringException');
        $arr = Factory::returnInstructionAndPossibleConstants($this->with_const1_too_large);
    }
    public function test_array_with_size_3_is_returned_for_a_const_const_VO(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->two_constants);
        $this->assertEquals(3,count($arr));
    }
    public function test_const_const_case_has_all_the_correct_types(){
        $arr = Factory::returnInstructionAndPossibleConstants($this->two_constants);
        $this->assertTrue($arr[0] instanceof Instruction);
        $this->assertTrue($arr[1] instanceof BinaryString);
        $this->assertTrue($arr[2] instanceof BinaryString);
    }
    public function test_foo(){
        $str = 'foo';
        $this->assertEquals('f',$str{0});
        $this->assertEquals('o',$str{2});
    }
}

?>
