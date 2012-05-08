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
    
    
    protected function setUp()
    {
        $this->validInstructions = Instruction::getValidInstructions();
        $this->errorvo = new VOInstruction("", "", false,null,"r", false, null);
        $this->voinst = new VOInstruction("MOV","R2",false,null,"R0",true,null);
        $this->with_constant1 = new VOInstruction("ADD","CONSTANT",true,1000,"r0",false,null);
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
    public function test_constructWithSImpleMovMnemonicWritesTheCorrectValueOntoTheInstruction(){
        $ins = new Instruction($this->voinst);
        $this->assertEquals(1, $ins[22]);
        
        for ($i=23;$i<26;$i++){
            $this->assertEquals(0,$ins[$i]);
        }
    }
    
    public function testTheConstantBitsGetSetTo1WhenArg1IsNotConstant(){
        $vo = new VOInstruction("MOV","R2",false,null,"AR2",false,null);
        
        $inst = new Instruction($vo);
        
        for($i=5;$i<9;$i++){
            $this->assertEquals($inst[$i], 1);
        }
        
        for($j=16;$j<20;$j++){
            $this->assertEquals($inst[$j],1);
        }
    }

    public function testTheConstantBitsGetSetTo1WhenArg2IsNotConstant(){
        $vo = new VOInstruction("MOV","constant",false,700,"AR2",true,null);
        
        $inst = new Instruction($vo);
        
    
        for($j=16;$j<20;$j++){
            $this->assertEquals($inst[$j],1);
        }
    }
        
    public function testConstant1GetsSetCorrectly(){
        $vo = new VOInstruction("NOR",'constant',false,949,"R4",true,null);
        
        $inst = new Instruction($vo);
        
        //4569 in binary is 1110110101
        $this->assertEquals($inst[0], 1);
        $this->assertEquals($inst[1], 0);
        $this->assertEquals($inst[2], 1);
        $this->assertEquals($inst[3], 0);
        $this->assertEquals($inst[4], 1);
        $this->assertEquals($inst[5], 1);
        $this->assertEquals($inst[6], 0);
        $this->assertEquals($inst[7], 1);
        $this->assertEquals($inst[8], 1);
        $this->assertEquals($inst[9], 1);
        
        //for argument2, the constant field is all 1's
        for($j=16;$j<20;$j++){
            $this->assertEquals($inst[$j],1);
        }
    }
    
    
    public function testMnemonicArg2AndArg1GetCorrectlySetForAComplexVOGivenAsInput(){
        $vo = new VOInstruction("NAND","R2",true,null,"constANT",false,800);
        

        $inst = new Instruction($vo);

        //arg1
        $this->assertEquals($inst[0], 1);        
        $this->assertEquals($inst[1], 1);        
        $this->assertEquals($inst[2], 0);        
        $this->assertEquals($inst[3], 0);        
        $this->assertEquals($inst[4], 0);
    
        //constant1
        $this->assertEquals($inst[5],  1);        
        $this->assertEquals($inst[6],  1);        
        $this->assertEquals($inst[7],  1);        
        $this->assertEquals($inst[8],  1);        
        $this->assertEquals($inst[9],  1);
        
        //indirection1
        $this->assertEquals($inst[10], 1);
        
         
        //arg2
        //800 in binary is 1100100000       
    
        $this->assertEquals($inst[11], 0);        
        $this->assertEquals($inst[12], 0);        
        $this->assertEquals($inst[13], 0);        
        $this->assertEquals($inst[14], 0);        
        $this->assertEquals($inst[15], 0);        
        $this->assertEquals($inst[16], 1);            
        $this->assertEquals($inst[17], 0);        
        $this->assertEquals($inst[18], 0);        
        $this->assertEquals($inst[19], 1);        
        $this->assertEquals($inst[20], 1);   
             
        
        $this->assertEquals($inst[16], 1);        
        $this->assertEquals($inst[17], 0);        
        $this->assertEquals($inst[18], 0);        
        $this->assertEquals($inst[19], 1);        
        $this->assertEquals($inst[20], 1);        
        $this->assertEquals($inst[26], 0);        
        
//        $this->assertEquals($inst[31], 0);        
        
       
        
    }
    public function test_SettingOfIndirectionIsTrueWhenItShould(){
        $vo = new VOInstruction('SUB','R2',true,null,'r1',false,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals($inst[10],1);
        
    }
    public function test_settingOfIndirectionBitIsFalseWhenItShould(){
        $vo = new VOInstruction('AND','r2',false,null,'constant',true,800);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals($inst[10],0);
        
    }
    public function test_IndirectionBitIsGettingSetWhereItMust(){
        $vo = new VOInstruction('MOV','r1',true,null,'r2',false,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals(1,$inst[10]);
        $this->assertEquals(0,$inst[21]);
        
    }
    
    
    public function testsizeIsCorrectForVOWithNoIndirection(){
        $vo = new VOInstruction('MOV','r1',false,null,'r2',false,null);
        
        $inst = new Instruction($vo);
        
        $this->AssertEquals(32,count($inst));
    }
    
    public function testFieldsGetWrittenCorrectly(){
        $vo = new VOInstruction("SUB",'r1',false,null,'constant',true,800);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals("SUB",$inst->getMnemonic());
        
        $this->assertEquals("R1",$inst->getArg1());
        $this->assertEquals("CONSTANT",$inst->getArg2());
        $this->assertEquals(800,$inst->getConstant2());
    }
    public function testHumanReadableFormForSimpleInstruction(){
        $vo = new VOInstruction("SUB",'r1',false,null,'constant',true,800);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals("SUB(R1,[#800])",$inst->humanReadableForm());

    }
    public function testCorrectValuesGetSetForAnInstructionThatTakesOnlyOneParam(){
        $vo = new VOInstruction("SHR",'r1',false,null,null,true,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals("SHR",$inst->getMnemonic());
        $this->assertEquals(null,$inst->getArg2());
    }
    public function testHUmanReadableFormFOrRelativelyCOmplexInstruction(){
        $vo = new VOInstruction("SHL",'r1',false,null,null,false,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals("SHL(R1)",$inst->humanReadableForm());
    }
    
    
    
}

?>
