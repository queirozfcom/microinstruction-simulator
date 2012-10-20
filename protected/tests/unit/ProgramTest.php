<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ProgramTest
 *
 * @author felipe
 */
class ProgramTest extends CDbTestCase {

    public function test_create_program() {
        $p = new Program;
    }

    public function test_reset() {
        $p = new Program;
        $p->reset();
        $this->assertEquals(0, $p->PC->asInt());
    }

    public function test_simple_mov() {
        $p = new Program;

        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 100));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();

        $this->assertEquals(100, $p->R1->asInt());

        $this->setExpectedException('InstructionException');

        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r7', false, null, 'r1', false, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
    }

    public function test_encompassing_mov() {
        $p = new Program;

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 99));

        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 20, 'r4', false, null));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }
        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
        $p->runNextInstruction();


        $this->assertEquals(
                99, $p->mainMemory[20]->asInt()
        );
    }

    public function test_que_ta_dando_problema() {
        //MOV[50],10)
        //MOV(R0,[50])


        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 10));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }

        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', true, 50));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();



        $p->runNextInstruction();

        $this->assertEquals(10, $p->R0->asInt());
    }

    public function test_confirmando_que_movs_tao_certos() {
        //MOV[50],99)
        //MOV(R0,50)
        //MOV(R1,[R0])

        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 99));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }

        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 50));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'r0', true, null));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();

        $p->runNextInstruction();


        $p->runNextInstruction();


        $this->assertEquals(99, $p->R1->asInt());
    }

    public function test_reg_indirecionado_para_reg_indirecionado() {
        //MOV(R1,30) 1
        //MOV(R2,40) 2
        //MOV([R1],999) 3
        //MOV([R2],[R1]) 4
        //MOV(R3,[R2]) 5
        //devemos ver 999 em R3

        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 30));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }


        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 40));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'constant', false, 999));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }

        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r1', true, null));

        foreach ($lines4 as $line) {
            $p->appendToMemory($line);
        }

        $lines5 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'r2', true, null));

        foreach ($lines5 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();

        $this->assertEquals(999, $p->R3->asInt());
    }

    public function test_const_pra_const() {
        //MOV([50],44567)
        //MOV([40],[50])
        //MOV(R3,[40])
        //devemos ver 44567 no R3

        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 44567));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }


        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 40, 'constant', true, 50));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', true, 40));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();

        $this->assertEquals(44567, $p->R3->asInt());
    }

    public function test_move_reg_to_indirected_reg() {
        //MOV(R3,50)
        //MOV(R2,40)
        //MOV([R2],R3)
        //devemos ver 50 em [40]
        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }


        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 40));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r3', false, null));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();

        $this->assertEquals(50, $p->mainMemory[40]->asInt());
    }

    public function test_final_mov() {
        //MOV(R3,50) 1
        //MOV([R3],100) 2
        //MOV(R2,[R3]) 3 //R2<-100
        //MOV([R2],R3) 4 //[100]<-50
        //MOV(R4,[100]) 5 //R4<-50
        //devemos ver 50 em R4

        $p = new Program();

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }

        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'constant', false, 100));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }

        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'r3', true, null));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }

        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r3', false, null));

        foreach ($lines4 as $line) {
            $p->appendToMemory($line);
        }

        $lines5 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', true, 100));

        foreach ($lines5 as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();

        $this->assertEquals(50, $p->R4->asInt());
    }
    
    public function testLastCase() {
        //MOV(R1,100)
        //MOV(R3,50)
        //MOV([R3],R1)
        //MOV([200],[R3])

        $p = new Program;

        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 100));

        foreach ($lines1 as $line) {
            $p->appendToMemory($line);
        }
        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));

        foreach ($lines2 as $line) {
            $p->appendToMemory($line);
        }
        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'r1', false, null));

        foreach ($lines3 as $line) {
            $p->appendToMemory($line);
        }
        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 75, 'r3', true, null));

        foreach ($lines4 as $line) {
            $p->appendToMemory($line);
        }
        
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        $p->runNextInstruction();
        
        $this->assertEquals($p->IR->getContent(), new Instruction('mov', 'constant', true, 'r3', true));
        
        //($p->controlUnit->decode($p->IR->getContent()));
        
        $this->assertEquals(50, $p->R3->asInt());
        
        $this->assertEquals(100,$p->mainMemory[50]->asInt());
        
        $this->assertEquals(100,$p->mainMemory[75]->asInt());
        
    }

}

?>
