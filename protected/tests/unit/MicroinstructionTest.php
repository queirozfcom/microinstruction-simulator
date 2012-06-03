<?php
class MicroinstructionTest extends CDbTestCase {
	public function testConstruct(){
		$mi = new Microinstruction();
		
		$this->assertEquals(MyConfig::$MICROINSTRUCTIONLENGTH, $mi->count());
		$this->assertEquals(MyConfig::$MICROINSTRUCTIONLENGTH, $mi->getLength());
                
                for ($i=0;$i<$mi->getLength();$i++){
                    $this->assertEquals(0,$mi[$i]);
                }
                
	}
        
	
}

