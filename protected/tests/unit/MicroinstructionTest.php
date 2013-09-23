<?php

class MicroinstructionTest extends CDbTestCase {

    public function testConstruct() {
        $mi = new Microinstruction();

        $this->assertEquals(0, $mi[23]);
    }

    public function test_parametrized_construct() {
        $mi = new Microinstruction("data_to_mdr");

        $this->assertTrue($mi instanceof Microinstruction);

        $this->assertEquals(1, $mi[22]);
        $this->assertEquals(1, $mi[24]);
    }

    public function test_exception_is_thrown_upon_invalid_alias() {
        $this->setExpectedException('MicroinstructionException');
        $mi = new Microinstruction("foo bar kajfh__");
    }

    public function test_set_mux_and_alu_value() {
        $mi = new Microinstruction;
        $mi->setMuxAndALUValueForMOVFromSourceRegister('MDR');

        $this->assertEquals(1, $mi[0]);
        $this->assertEquals(1, $mi[12]);
        $this->assertEquals(0, $mi[13]);
        $this->assertEquals(0, $mi[14]);

        $mi2 = new Microinstruction;
        $mi2->setMuxAndALUValueForMOVFromSourceRegister('AR2');

        $this->assertEquals(1, $mi2[21]);
        $this->assertEquals(0, $mi2[20]);
        $this->assertEquals(0, $mi2[19]);
        $this->assertEquals(1, $mi2[1]);
    }

    public function test_set_target_register() {
        $mi = new Microinstruction;
        $mi->setTargetIndexFromTargetRegister('r0');
        $this->assertEquals(1, $mi[8]);

        $mi = new Microinstruction;
        $mi->setTargetIndexFromTargetRegister('R2');
        $this->assertEquals(1, $mi[15]);

        $mi = new Microinstruction;
        $mi->setTargetIndexFromTargetRegister('MDR');
        $this->assertEquals(1, $mi[23]);
        $this->assertEquals(1, $mi[24]);
    }

    public function test_get_mux_values() {
        $mi = new Microinstruction('increment_pc');

        $muxaval = $mi->getMUXAValue();

        $this->assertEquals('100', $muxaval);
    }

    public function test_get_alu_operation_code() {
        $mi = new Microinstruction;

        $mi[0] = 1;

        $this->assertEquals(1, $mi->getALUOperationCode());


        unset($mi);

        $mi = new Microinstruction;

        $mi->setOne(1);

        $this->assertEquals(2, $mi->getALUOperationCode());
    }
    
}

