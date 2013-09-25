<?php

/**
 * Description of ALUTest
 *
 * @author felipe
 */
class ALUTest extends CDbTestCase {

    public $ALU;

    public function setUp() {
        $this->ALU = new ALU;
    }

    public function test_operate_on_method_throws_exception_correctly_for_invalide_left_operand() {
        $this->setExpectedException('ALUException');

        $l = new Program;

        $r = null;

        $this->ALU->operateOn($l, $r, 'S=A+1');
    }

    public function test_operate_on_method_throws_exception_correctly_for_invalide_right_operand() {
        $this->setExpectedException('ALUException');

        $l = new BinaryString;

        $r = new XMLReader;

        $this->ALU->operateOn($l, $r, 'S=A');
    }

    public function test_mov_for_lots_of_cases() {
        $bs1 = new BinaryString(32, 100);
        $bs2 = new BinaryString(32, 20);


        $sequalsa = $this->ALU->operateOn($bs1, $bs2, 1);
        $sequalsb = $this->ALU->operateOn($bs1, $bs2, 2);

        $sequalsaplusb = $this->ALU->operateOn($bs1, $bs2, 5);

        $this->assertEquals($bs1, $sequalsa);
        $this->assertEquals($bs2, $sequalsb);
        $this->assertEquals(120, $sequalsaplusb->asInt());

        $bs3 = new BinaryString(32, -50);
        $bs4 = new BinaryString(32, -60);

        $sequalsaplusb = $this->ALU->operateOn($bs3, $bs4, 5);
        $sequalsbplusa = $this->ALU->operateOn($bs4, $bs3, 5);

        $this->assertEquals(-110, $sequalsaplusb->asInt());
        $this->assertEquals(-110, $sequalsbplusa->asInt());
    }

}

?>
