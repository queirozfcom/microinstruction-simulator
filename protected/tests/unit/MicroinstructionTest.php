<?php

require_once '../../models/Microinstruction.php';

class MicroinstructionTest extends PHPUnit_Framework_TestCase {
	public function testConstruct(){
		$mi = new Microinstruction();
		
		$this->assertEquals(28, $mi->count());
	}
	

}

