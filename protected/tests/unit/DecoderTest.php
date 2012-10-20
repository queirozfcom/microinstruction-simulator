<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DecoderTest
 *
 * @author felipe
 */
class DecoderTest extends CDbTestCase{
    
    public static function getMethod($method_name,$class_name='Decoder') {
      $class = new ReflectionClass($class_name);
      $method = $class->getMethod($method_name);
      $method->setAccessible(true);
      return $method;
    }
    
    public function setUp() {
        parent::setUp();
    }
    public function test_decode_fetch_alias(){
        $obj = new Decoder;
        $method = self::getMethod('getMicroprogramFromAlias');
        
        $mp = $method->invoke($obj,'fetch');
        
        $this->assertEquals(3,count($mp));
        
        $mi0 = $mp[0];
        $mi1 = $mp[1];
        $mi2 = $mp[2];
        
        $this->assertTrue($mi0 instanceof Microinstruction);
        $this->assertTrue($mi1 instanceof Microinstruction);
        $this->assertTrue($mi2 instanceof Microinstruction);
        
        $this->assertEquals(1,$mi0[14]);
        $this->assertEquals(1,$mi0[27]);
        
    }
    public function test_decode_simplest_mov(){
        $obj = new Decoder;
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r1',false,'r4',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertTrue(is_array($mp));
        
        
        $this->assertEquals(1, count($mp));
        
        $inst2 = new Instruction('mov','r1',false,'r2',false);
        
        $mp = $method->invoke($obj,$inst2);
        
        $this->assertEquals(1,$mp[0][1]);
    }
    public function test_decode_simplest_mov2(){
        $obj = new Decoder;
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r3',false,'r0',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertTrue(is_array($mp));
        $this->assertEquals(1,count($mp));
        $this->assertEquals(1,$mp[0][0]);
        $this->assertEquals(1,$mp[0][16]);
        
        
    }
    public function test_mov_registers_on_same_side(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r3',false,'r2',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertTrue(is_array($mp));
        $this->assertEquals(1,count($mp));
   
        
    }
    public function test_mov_regs_only_indirected(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        //move(r2,[r3]);
        $inst = new Instruction('mov','r2',false,'r3',true);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->AssertEquals(3,count($mp));
        
        $this->assertTrue($mp[0] instanceof Microinstruction);
        $this->assertTrue($mp[1] instanceof Microinstruction);
        $this->assertTrue($mp[2] instanceof Microinstruction);
        
        
    }
    public function test_regs_only_indirection_1(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        //move([r3],r2);
        $inst = new Instruction('mov','r3',true,'r2',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->AssertEquals(2,count($mp));
        
        $this->assertTrue($mp[0] instanceof Microinstruction);
        $this->assertTrue($mp[1] instanceof Microinstruction);
        
        $this->AssertEquals(1,$mp[0][24]);
        $this->AssertEquals(1,$mp[0][23]);
        $this->AssertEquals(1,$mp[0][19]);
        $this->AssertEquals(1,$mp[0][1]);
        
        $this->AssertEquals(1,$mp[1][20]);
        $this->AssertEquals(1,$mp[1][1]);
        $this->AssertEquals(1,$mp[1][27]);

    }
    public function test_reg_only_two_indirections1(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        
        //MOV([r3],[r2])
        $inst = new Instruction('mov','r3',true,'r2',true);
        $mp = $method->invoke($obj,$inst);      
        
        $this->assertTrue(is_array($mp));
        $this->assertEquals(3,count($mp));
        $this->assertEquals(1,$mp[0][19]);
        $this->assertEquals(new Microinstruction('data_to_mdr'),$mp[1]);
        
        
    }
    public function test_reg_only_two_indirections2(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        //MOV([r1],[r2])
        $inst2 = new Instruction('mov','r1',true,'r2',true);
        $mp2 = $method->invoke($obj,$inst2); 
        
        $this->assertTrue(is_array($mp2));
        $this->assertEquals(3,count($mp2));
    }
    public function test_reg_only_two_indirections3(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        //MOV([r0],[r0]);
        $inst3 = new Instruction('mov','r0',true,'r0',true);
        $mp3 = $method->invoke($obj,$inst3 );
        
        $this->assertTrue(is_array($mp3));
        $this->assertEquals(3,count($mp3));
        
                
        $this->assertEquals(1,$mp3[0][0]);
        $this->assertEquals(1,$mp3[0][13]);
        $this->assertEquals(1,$mp3[0][25]);
        
        $this->assertEquals(1,$mp3[0][27]);
        
        $this->assertEquals(new Microinstruction('data_to_mdr'),$mp3[1]);
    
        $this->assertEquals(1,$mp3[2][26]);
    }
    public function test_exception_gets_thrown_if_a_direct_constant_is_used_as_param1(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $this->setExpectedException('InstructionException');
        
        $inst = new Instruction('mov','constant',false,'r0',true);
        $mp = $method->invoke($obj,$inst);
    }
    
    public function test_constant_no_indirection(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r0',false,'constant',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertTrue(is_array($mp));
        
        $this->assertEquals(4,count($mp));
        
        $m0 = $mp[0];
        $m1 = $mp[1];
        $m2 = $mp[2];
        $m3 = $mp[3];
        
        $this->assertEquals(1,$m0[14]);
        $this->assertEquals(1,$m0[10]);
        $this->assertEquals(1,$m0[0]);
        $this->assertEquals(1,$m0[1]);
        
        $this->assertEquals(1,$m1[14]);
        $this->assertEquals(1,$m1[27]);
        $this->assertEquals(1,$m1[0]);
        $this->assertEquals(1,$m1[25]);
        
        $this->assertEquals(1,$m2[22]);
        $this->assertEquals(1,$m2[24]);
        
        $this->assertEquals(1,$m3[8]);
        $this->assertEquals(1,$m3[12]);
        $this->assertEquals(1,$m3[0]);
        
    }
    
    public function test_constant_no_indirection2(){
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r0',false,'constant',false);
        
        $mp = $method->invoke($obj,$inst);
        
    }
    
    public function test_get_opposite_ar(){
        $obj = new Decoder;
        
        $method = self::getMethod('getOppositeARFromRegisterName');
        
        $inst = new Instruction('mov','r0',false,'constant',false);
        
        $ar = $method->invoke($obj,'r0');
        
        $this->assertEquals('AR2',$ar);
    }
    
    public function test__REG____CONST__(){
        //[REG],[CONST]
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','r0',true,'constant',true);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertEquals(8,count($mp));
        
        $mi0 = $mp[0];
        $mi1 = $mp[1];
        $mi2 = $mp[2];
        $mi3 = $mp[3];
        $mi4 = $mp[4];
        $mi5 = $mp[5];
        $mi6 = $mp[6];
        $mi7 = $mp[7];
        
        
        $this->assertTrue($mi0 instanceof Microinstruction);
        
        $this->assertTrue($mi1 instanceof Microinstruction);
        
        $this->assertTrue($mi2 instanceof Microinstruction);
        
        $this->assertTrue($mi3 instanceof Microinstruction);
        
        $this->assertTrue($mi4 instanceof Microinstruction);
        
        $this->assertTrue($mi5 instanceof Microinstruction);
        
        $this->assertTrue($mi6 instanceof Microinstruction);
        
        $this->assertTrue($mi7 instanceof Microinstruction);
        
        
        
    }
    public function test_4(){
        //MNEM[const],const
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','constant',true,'constant',false);
        
        $mp = $method->invoke($obj,$inst);
        
        $this->assertEquals(8,count($mp));
        
        $mi0 = $mp[0];
        $mi1 = $mp[1];
        $mi2 = $mp[2];
        $mi3 = $mp[3];
        $mi4 = $mp[4];
        $mi5 = $mp[5];
        $mi6 = $mp[6];
        $mi7 = $mp[7];
        
        
        $this->assertTrue($mi0 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('increment_pc'),$mi0);
        
        $this->assertTrue($mi1 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('pc_to_mar_read'),$mi1);
        
        $this->assertTrue($mi2 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('data_to_mdr'),$mi2);
        
        $this->assertTrue($mi3 instanceof Microinstruction);
        $this->assertEquals(1,$mi3[11]);
        $this->assertEquals(1,$mi3[0]);
        
        $this->assertTrue($mi4 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('increment_pc'),$mi4);
        
        $this->assertTrue($mi5 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('pc_to_mar_read'),$mi5);
        
        $this->assertTrue($mi6 instanceof Microinstruction);
        $this->assertEquals(new Microinstruction('data_to_mdr'),$mi6);
        
        $this->assertTrue($mi7 instanceof Microinstruction);
        $this->assertEquals(1,$mi7[26]);
        $this->assertEquals(1,$mi7[27]);
        $this->assertEquals(1,$mi7[0]);
        
    }
    
    public function testRegToConstBothIndirected(){
        //MOV([CONST],[REG])
        
        $obj = new Decoder;
        
        $method = self::getMethod('decodeMovInstruction');
        
        $inst = new Instruction('mov','constant',true,'r3',true);
        
        $mp = $method->invoke($obj,$inst);
    }
    
}

?>
