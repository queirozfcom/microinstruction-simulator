<?php
class MicroinstructionTest extends CDbTestCase {
	public function testConstruct(){
		$mi = new Microinstruction();
		
                $this->assertEquals(0,$mi[23]);
                
	}
        public function test_echo_string_thing(){
            
        }
        public function test_parametrized_construct(){
            $mi = new Microinstruction("data_to_mdr");
            
            $this->assertTrue($mi instanceof Microinstruction);
            
            $this->assertEquals(1,$mi[22]);
            $this->assertEquals(1,$mi[24]);
        }
        public function test_exception_is_thrown_upon_invalid_alias(){
            $this->setExpectedException('MicroinstructionException');
            $mi = new Microinstruction("foo bar kajfh__");
        }
        
        
	
}

