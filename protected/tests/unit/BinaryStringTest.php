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

        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct() {
        // TODO Auto-generated constructor
    }

    public function testConstructWithZeroParameter() {
        $this->zeroParameterBinaryString = new BinaryString();

        for ($i = 0; $i < $this->zeroParameterBinaryString->getLength(); $i++) {
            $this->assertEquals(0, $this->zeroParameterBinaryString[$i]);
        }
    }

    public function testConstructWithOneParameter() {
        $this->oneParameterBinaryString = new BinaryString(60);
    }

    public function testConstructWithTwoParameters() {
        $this->twoParameterBinaryString = new BinaryString(13, 49);
    }

    public function testAsIntFunctionWithPositiveValues() {
        $bs = new BinaryString();
        $bs->setIntegerValue(56);

        $this->assertEquals(56, $bs->asInt());
    }

    public function testAsIntFunctionWithTwosComplement() {
        $bs = new BinaryString();

        $bs->setIntegerValue(-178);
        $this->assertEquals(-178, $bs->asInt());
    }

    public function testAsIntFunctionWithZero() {
        $bs = new BinaryString();

        $this->assertEquals(0, $bs->asInt());
    }

    public function testThrowsExceptionWhenNumberLargerThanUpperLimit() {
        $this->setExpectedException('BinaryStringException');

        $bs = new BinaryString();
        $bs->setIntegerValue(438676893493576894);
    }

    public function testThrowsExceptionWhenNumberSmallerThanLowerLimit() {

        $this->setExpectedException('BinaryStringException');

        $bs = new BinaryString();
        $bs->setIntegerValue(pow(-2, 31) - 1);
    }

    public function testFunc2ThrowsExceptionWhenUserTriesToSetALargerNumberThanCanBeRepresentedWithTheGivenNumberOfBits() {
        $this->setExpectedException('BinaryStringException');
        $bs = new BinaryString();

        $bs->setIntValueStartingAt(566, 5, 3);
    }

    public function testFunc2ThrowsExceptionWhenUserWantsALengthThatBiggerThanObjectsLength() {
        $this->setExpectedException('BinaryStringException');

        $bs = new BinaryString();

        $bs->setIntValueStartingAt(546, 10, 28);
    }

    public function testFunc2WritesNumber60() {
        $bs = new BinaryString();
        $bs->setIntValueStartingAt(15, 10, 2);

        $this->assertEquals(60, $bs->asInt());
    }

    public function testFunc2WritesNumberZero() {
        $bs = new BinaryString();

        $bs->setIntegerValue(-67);

        $this->assertEquals(-67, $bs->asInt());

        $bs->setIntValueStartingAt(0, 32, 0);

        $this->assertEquals(0, $bs->asInt());
    }

    public function test_intValStartingAt34_6_5() {
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

    public function testComplexSum() {
        $bs1 = new BinaryString(32, -2);
        $bs2 = new BinaryString(32, 4);

        $sum = $bs1->asInt() + $bs2->asInt();

        $this->assertEquals(2, $sum);
    }

    public function testIncrementFunction() {
        $bs = new BinaryString(32, 8890);

        $bs->increment();

        $this->assertEquals(8891, $bs->asInt());
    }

    public function testIncrementFunctionUsingNegativeNumber() {
        $bs = new BinaryString(32, -87);
        $bs->increment();

        $this->assertEquals(-86, $bs->asInt());
    }

    public function test_negative_number_gets_written_correctly() {
        $bs = new BinaryString(32, -45);

        $this->assertEquals($bs->__toString(), '11111111111111111111111111010011');
    }

    public function test_postivie_number_gets_written_correctly() {
        $bs = new BinaryString(8, 78);

        $this->assertEquals($bs->__toString(), '01001110');
    }

    public function test_intVal_as_numeric_string_works_too() {
        $bs = new BinaryString(32, "678");
    }

    public function test_complex_sum_works_if_both_numeric_strings_and_integers_are_used() {
        $bs1 = new BinaryString(32, "-50");
        $bs2 = new BinaryString(32, 43);

        $this->assertEquals(-7, ($bs2->asInt() + $bs1->asInt()));
    }

    public function test_intVal_as_anything_not_numeric_fails() {
        $this->setExpectedException("BinaryStringException");
        $bs = new BinaryString(8, "fooo");
    }

    public function test_upper_ceiling_is_checked_upon_objects_construction() {
        $this->setExpectedException("BinaryStringException");

        $bs = new BinaryString(8, 9997775675765);
    }

    public function test_get_int_value_starting_at_and_ending_at() {
        $bs = new BinaryString(32, 511);

        $this->assertEquals(511, $bs->asInt());

        $this->assertEquals(31, $bs->getIntValueStartingAt(5, 0));

        $this->assertEquals(3, $bs->getIntValueStartingAt(2, 0));

        $this->assertEquals(1, $bs->getIntValueStartingAt(1, 0));

        $bs = new BinaryString(32);

        $bs->setOne(0, 1, 3, 5, 7);

        $this->assertEquals(11, $bs->getIntValueStartingAt(4, 0));
    }

    public function testAndSimple() {
        $bs1 = new BinaryString(32, 15);

        $bs2 = new BinaryString(32, 3);

        $this->assertEquals(3, $bs1->andWith($bs2)->asInt());
    }

    public function testAndComplex() {
        $bs1 = new BinaryString(32, 1023);

        $bs2 = new BinaryString(32, 511);

        $this->assertEquals(511, $bs1->andWith($bs2)->asInt());
    }

    public function testAndBothWaysIsTheSame() {

        $bs1 = new BinaryString(32, 16383); //2 to the 14 minus 1

        $bs2 = new BinaryString(32, 7);

        $res1 = $bs1->andWith($bs2);

        $res2 = $bs2->andWith($bs1);

        $this->assertTrue($res1->asInt() === $res2->asInt());
    }

    public function testOrSimple() {
        $bs1 = new BinaryString(32, 10);

        $bs2 = new BinaryString(32, 3);

        $this->assertEquals(11, $bs1->orWith($bs2)->asInt());
    }

    public function testOrOfTwoNumbersIsAlwaysLargerThanOrEqualToEach() {
        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $bs1 = new BinaryString(32, $i);
                $bs2 = new BinaryString(32, $j);

                $this->assertTrue($bs1->orWith($bs2)->asInt() >= $i);
                $this->assertTrue($bs1->orWith($bs2)->asInt() >= $j);

                $this->assertTrue($bs2->orWith($bs1)->asInt() >= $i);
                $this->assertTrue($bs2->orWith($bs1)->asInt() >= $j);
            }
        }
    }
    
    public function testSimpleShiftRight(){
        
        $bs1 = new BinaryString(32,10);
        
        $this->assertEquals(5, $bs1->shiftRight()->asInt());
        
        $bs2=new BinaryString(32,0);
        $this->assertEquals(0, $bs2->shiftRight()->asInt());
        
        $bs3 = new BinaryString(20,7);
        $this->assertEquals(3, $bs3->shiftRight()->asInt());
        
        $bs4 = new BinaryString(8,20);
        $this->assertEquals(10, $bs4->shiftRight()->asInt());
        
        $bs5 = new BinaryString(4,15);
        $this->assertEquals(7, $bs5->shiftRight()->asInt());
        
    }
    
    public function testSimpleShiftLeft(){
        
        $bs1= new BinaryString(32,99);
        
        $this->assertEquals(198, $bs1->shiftLeft()->asInt());
        
        $bs2 = new BinaryString(6,7);
        
        $this->assertEquals(14, $bs2->shiftLeft()->asInt());
        
        $bs3 = new BinaryString(32,5000000);
        
        $this->assertEquals(10000000, $bs3->shiftLeft()->asInt());
        
    }
    
    public function testSetThenGet(){
        
        $bs = new BinaryString;
        
        $bs->setIntValueStartingAt(77, 10, 7);
        
        $this->assertEquals(77, $bs->getIntValueStartingAt(10, 7));
        
    }
    
    public function testSetThenGetTwo(){
        $bs = new BinaryString;
        
        $bs->setIntValueStartingAt(28173, 22, 8);
        
        $this->assertEquals(28173, $bs->getIntValueStartingAt(22, 8));
        
    }
    
}

