<?php

/**
 * BinaryString test case.
 */
class BinaryStringTest extends CDbTestCase {

	private $zeroParameterBinaryString;
	private $oneParameterBinaryString;
	private $twoParameterBinaryString;
	protected function setUp() {
	
	}
	protected function tearDown() {
		$this->BinaryString = null;
		
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	public function testConstructWithZeroParameter() {
		$this->zeroParameterBinaryString = new BinaryString();
                
                for($i=0;$i<$this->zeroParameterBinaryString->getLength();$i++){
                    $this->assertEquals(0, $this->zeroParameterBinaryString[$i]);
                }
	}
	public function testConstructWithOneParameter(){
		$this->oneParameterBinaryString = new BinaryString(60);
		
	}
	public function testConstructWithTwoParameters(){
		$this->twoParameterBinaryString = new BinaryString(13,49);
	}
	public function testAsIntFunctionWithPositiveValues(){
		$bs = new BinaryString();
		$bs->setIntegerValue(56);
		
		$this->assertEquals(56, $bs->asInt());
	}
	public function testAsIntFunctionWithTwosComplement(){
		$bs = new BinaryString();
		
		$bs->setIntegerValue(-178);
		$this->assertEquals(-178,$bs->asInt());
	}
	public function testAsIntFunctionWithZero(){
		$bs = new BinaryString();
		
		$this->assertEquals(0, $bs->asInt());
	}
	public function testThrowsExceptionWhenNumberLargerThanUpperLimit(){
		$this->setExpectedException('BinaryStringException');
		
		$bs = new BinaryString();
		$bs->setIntegerValue(438676893493576894);
	}
	public function testThrowsExceptionWhenNumberSmallerThanLowerLimit(){
		
		$this->setExpectedException('BinaryStringException');
		
		$bs = new BinaryString();
		$bs->setIntegerValue(pow(-2, 31)-1);
	}
	public function testFunc2ThrowsExceptionWhenUserTriesToSetALargerNumberThanCanBeRepresentedWithTheGivenNumberOfBits(){
		$this->setExpectedException('BinaryStringException');
		$bs = new BinaryString();
		
		$bs->setIntValueStartingAt(566, 5, 3);
	}
	public function testFunc2ThrowsExceptionWhenUserWantsALengthThatBiggerThanObjectsLength(){
		$this->setExpectedException('BinaryStringException');
		
		$bs = new BinaryString();
		
		$bs->setIntValueStartingAt(546, 10, 28);
	}
	public function testFunc2WritesNumber60(){
		$bs = new BinaryString();
		$bs->setIntValueStartingAt(15, 10, 2);
		
		$this->assertEquals(60, $bs->asInt());
	}
	public function testFunc2WritesNumberZero(){
		$bs = new BinaryString();
		
		$bs->setIntegerValue(-67);
		
		$this->assertEquals(-67, $bs->asInt());
		
		$bs->setIntValueStartingAt(0, 32, 0);
		
		$this->assertEquals(0, $bs->asInt());
	}
        public function test_intValStartingAt34_6_5(){
            //34 in binary is 100010
            
            $bs = new BinaryString();
            $bs->setIntValueStartingAt(34, 6, 5);
            
            $this->assertEquals(0, $bs[0]);
            $this->assertEquals(0, $bs[1]);
            $this->assertEquals(0, $bs[2]);
            $this->assertEquals(0, $bs[3]);
            $this->assertEquals(0, $bs[4]);
            $this->assertEquals(0, $bs[5]);
            $this->assertEquals(1, $bs[6]);
            $this->assertEquals(0, $bs[7]);
            $this->assertEquals(0, $bs[8]);
            $this->assertEquals(0, $bs[9]);
            $this->assertEquals(1, $bs[10]);
            
            
        }
        
	public function testComplexSum(){
		$bs1 = new BinaryString(32,-2);
		$bs2 = new BinaryString(32,4);
		
		$sum = $bs1->asInt() + $bs2->asInt();
		
		$this->assertEquals(2, $sum);	
	}
	public function testIncrementFunction(){
		$bs = new BinaryString(32,8890);
		
		$bs->increment();
		
		$this->assertEquals(8891, $bs->asInt());
	}
	public function testIncrementFunctionUsingNegativeNumber(){
		$bs = new BinaryString(32,-87);
		$bs->increment();

		$this->assertEquals(-86, $bs->asInt());
	}
        public function test_negative_number_gets_written_correctly(){
            $bs = new BinaryString(32,-45);
            
            $this->assertEquals($bs->__toString(),'11111111111111111111111111010011');
        }
        public function test_postivie_number_gets_written_correctly(){
            $bs = new BinaryString(8,78);
            
            $this->assertEquals($bs->__toString(),'01001110');
        }
        public function test_intVal_as_numeric_string_works_too(){
            $bs = new BinaryString(32,"678");
            
        }
        public function test_complex_sum_works_if_both_numeric_strings_and_integers_are_used(){
            $bs1 = new BinaryString(32,"-50");
            $bs2 = new BinaryString(32,43);
            
            $this->assertEquals(-7,($bs2->asInt() + $bs1->asInt()));
        }
        
        public function test_intVal_as_anything_not_numeric_fails(){
            $this->setExpectedException("BinaryStringException");
            $bs = new BinaryString(8,"fooo");
            
        }
        public function test_upper_ceiling_is_checked_upon_objects_construction(){
            $this->setExpectedException("BinaryStringException");
            
            $bs = new BinaryString(8,9997775675765);
            
            
        }

}

