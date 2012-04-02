<?php
    require_once '../../models/Instruction.php';
    require_once '../../models/helpers/VOInstruction.php';
    require_once 'PHPUnit/Framework/TestCase.php';

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of InstructionTest
 *
 * @author felipe
 */
class InstructionTest extends PHPUnit_Framework_TestCase{
    
    
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
        $this->assertEquals(1, $ins[32]);
        
        for ($i=33;$i<37;$i++){
            $this->assertEquals(0,$ins[$i]);
        }
    }
    
    public function testTheConstantBitsGetSetTo1WhenArg1IsNotConstant(){
        $vo = new VOInstruction("MOV","R2",false,null,"AR2",false,null);
        
        $inst = new Instruction($vo);
        
        for($i=5;$i<15;$i++){
            $this->assertEquals($inst[$i], 1);
        }
        
        for($j=21;$j<31;$j++){
            $this->assertEquals($inst[$j],1);
        }
    }

    public function testTheConstantBitsGetSetTo1WhenArg2IsNotConstant(){
        $vo = new VOInstruction("MOV","constant",false,700,"AR2",true,null);
        
        $inst = new Instruction($vo);
        
    
        for($j=21;$j<31;$j++){
            $this->assertEquals($inst[$j],1);
        }
    }
        
    public function testConstant1GetsSetCorrectly(){
        $vo = new VOInstruction("NOR",'constant',false,4569,"R4",true,null);
        
        $inst = new Instruction($vo);
        
        //4569 in binary is 1000111011001
        $this->assertEquals($inst[0], 1);
        $this->assertEquals($inst[1], 0);
        $this->assertEquals($inst[2], 0);
        $this->assertEquals($inst[3], 1);
        $this->assertEquals($inst[4], 1);
        $this->assertEquals($inst[5], 0);
        $this->assertEquals($inst[6], 1);
        $this->assertEquals($inst[7], 1);
        $this->assertEquals($inst[8], 1);
        $this->assertEquals($inst[9], 0);
        $this->assertEquals($inst[10], 0);
        $this->assertEquals($inst[11], 0);
        $this->assertEquals($inst[12], 1);
        $this->assertEquals($inst[13], 0);
        $this->assertEquals($inst[14], 0);
        
        //for argument2, the constant field is all 1's
        for($j=21;$j<31;$j++){
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
    
        
        $this->assertEquals($inst[5],  1);        
        $this->assertEquals($inst[6],  1);        
        $this->assertEquals($inst[7],  1);        
        $this->assertEquals($inst[8],  1);        
        $this->assertEquals($inst[9],  1);        
        $this->assertEquals($inst[10], 1);        
        $this->assertEquals($inst[11], 1);        
        $this->assertEquals($inst[12], 1);        
        $this->assertEquals($inst[13], 1);        
        $this->assertEquals($inst[14], 1);        
        
//        $this->assertEquals($inst[15], 0);        
        
        
        //arg2
        //800 in binary is 1100100000
        
        $this->assertEquals($inst[16], 0);            
        $this->assertEquals($inst[17], 0);        
        $this->assertEquals($inst[18], 0);        
        $this->assertEquals($inst[19], 0);        
        $this->assertEquals($inst[20], 0);   
             
        $this->assertEquals($inst[21], 1);        
        $this->assertEquals($inst[22], 0);        
        $this->assertEquals($inst[23], 0);        
        $this->assertEquals($inst[24], 1);        
        $this->assertEquals($inst[25], 1);        
        $this->assertEquals($inst[26], 0);        
        $this->assertEquals($inst[27], 0);        
        $this->assertEquals($inst[28], 0);        
        $this->assertEquals($inst[29], 0);        
        $this->assertEquals($inst[30], 0);        
        
//        $this->assertEquals($inst[31], 0);        
        
//        $this->assertEquals($inst[32], 0);        
//        $this->assertEquals($inst[33], 1);        
//        $this->assertEquals($inst[34], 1);        
//        $this->assertEquals($inst[35], 0);        
//        $this->assertEquals($inst[36], 0);        
        
    }
    public function test_SettingOfIndirectionIsTrueWhenItShould(){
        $vo = new VOInstruction('SUB','R2',true,null,'r1',false,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals($inst[15],1);
        
    }
    public function test_settingOfIndirectionBitIsFalseWhenItShould(){
        $vo = new VOInstruction('AND','r2',false,null,'constant',true,800);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals($inst[31],1);
        
    }
    public function test_IndirectionBitIsGettingSetWhereItMust(){
        $vo = new VOInstruction('MOV','r1',true,null,'r2',false,null);
        
        $inst = new Instruction($vo);
        
        $this->assertEquals(1,$inst[15]);
        $this->assertEquals(0,$inst[31]);
        
    }
    
    
    public function nttest_sizeIsCorrectForVOWithNoIndirection(){
        $vo = new VOInstruction('MOV','r1',false,null,'r2',false,null);
        
        $inst = new Instruction($vo);
        
        $this->AssertEquals(37,count($inst));
    }
    
    
}

?>
