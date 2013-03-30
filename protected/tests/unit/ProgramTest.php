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

//    public function test_create_program() {
//        $p = new Program;
//    }
//
//    public function test_reset() {
//        $p = new Program;
//        $p->reset();
//        $this->assertEquals(0, $p->PC->asInt());
//    }
//
//    public function test_simple_mov() {
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 100));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//
//        $this->assertEquals(100, $p->R1->asInt());
//
//        $this->setExpectedException('InstructionException');
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r7', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//    }
//
//    public function test_encompassing_mov() {
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 99));
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 20, 'r4', false, null));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//
//        $this->assertEquals(
//                99, $p->mainMemory[20]->asInt()
//        );
//    }
//
//    public function test_que_ta_dando_problema() {
//        //MOV[50],10)
//        //MOV(R0,[50])
//
//
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 10));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', true, 50));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//
//
//
//        $p->runNextInstruction();
//
//        $this->assertEquals(10, $p->R0->asInt());
//    }
//
//    public function test_confirmando_que_movs_tao_certos() {
//        //MOV[50],99)
//        //MOV(R0,50)
//        //MOV(R1,[R0])
//
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 99));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 50));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'r0', true, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//
//        $p->runNextInstruction();
//
//
//        $p->runNextInstruction();
//
//
//        $this->assertEquals(99, $p->R1->asInt());
//    }
//
//    public function test_reg_indirecionado_para_reg_indirecionado() {
//        //MOV(R1,30) 1
//        //MOV(R2,40) 2
//        //MOV([R1],999) 3
//        //MOV([R2],[R1]) 4
//        //MOV(R3,[R2]) 5
//        //devemos ver 999 em R3
//
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 30));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 40));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'constant', false, 999));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r1', true, null));
//
//        foreach ($lines4 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines5 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'r2', true, null));
//
//        foreach ($lines5 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(999, $p->R3->asInt());
//    }
//
//    public function test_const_pra_const() {
//        //MOV([50],44567)
//        //MOV([40],[50])
//        //MOV(R3,[40])
//        //devemos ver 44567 no R3
//
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 44567));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 40, 'constant', true, 50));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', true, 40));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(44567, $p->R3->asInt());
//    }
//
//    public function test_move_reg_to_indirected_reg() {
//        //MOV(R3,50)
//        //MOV(R2,40)
//        //MOV([R2],R3)
//        //devemos ver 50 em [40]
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 40));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r3', false, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(50, $p->mainMemory[40]->asInt());
//    }
//
//    public function test_final_mov() {
//        //MOV(R3,50) 1
//        //MOV([R3],100) 2
//        //MOV(R2,[R3]) 3 //R2<-100
//        //MOV([R2],R3) 4 //[100]<-50
//        //MOV(R4,[100]) 5 //R4<-50
//        //devemos ver 50 em R4
//
//        $p = new Program();
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'constant', false, 100));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'r3', true, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r3', false, null));
//
//        foreach ($lines4 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines5 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', true, 100));
//
//        foreach ($lines5 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(50, $p->R4->asInt());
//    }
//
//    public function testLastCase() {
//        //MOV(R1,100)
//        //MOV(R3,50)
//        //MOV([R3],R1)
//        //MOV([200],[R3])
//
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 100));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'r1', false, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 75, 'r3', true, null));
//
//        foreach ($lines4 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals($p->IR->getContent(), new Instruction('mov', 'constant', true, 'r3', true));
//
//        //($p->controlUnit->decode($p->IR->getContent()));
//
//        $this->assertEquals(50, $p->R3->asInt());
//
//        $this->assertEquals(100, $p->mainMemory[50]->asInt());
//
//        $this->assertEquals(100, $p->mainMemory[75]->asInt());
//    }
//
//    public function testMovRegToMar() {
//        //mov(50,r4)
//        //mov(mar,r4)
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 50));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'mar', false, null, 'r4', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(50, $p->R4->asInt());
//
//        $this->assertEquals(50, $p->MAR->asInt());
//    }
//
//    /**
//     * ADD
//     */
//    public function testAddRegistersOnDifferentSide() {
//        //add(r0,r3)
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 2));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 3));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r3', false, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(5, $p->R0->asInt());
//    }
//
//    public function testAddRegistersOnSameSideLEFT() {
//        //add(r0,r1)
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 15));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 3000));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r1', false, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(3000, $p->AR2->asInt());
//
//        $this->assertEquals(3015, $p->R0->asInt());
//    }
//
//    public function testAddRegistersOnSameSideRIGHT() {
//        //add(r3,r4)
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 9999));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 5000));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r3', false, null, 'r4', false, null));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(5000, $p->AR1->asInt());
//
//        $this->assertEquals(14999, $p->R3->asInt());
//    }
//
//    public function testAddConstToRegOnRightSide() {
//        //mov(r4,8)
//        //add(r4,8892)
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 8));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r4', false, null, 'constant', false, 8892));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(8900, $p->R4->asInt());
//        $this->assertEquals(8892, $p->MDR->asInt());
//    }
//
//    public function testAddConstToRegOnLeftSide() {
//        //mov(r0,5)
//        //add(r0,10)
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 5));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'constant', false, 10));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(10, $p->MDR->asInt());
//
//        $this->assertEquals(10, $p->AR2->asInt());
//
//        $this->assertEquals(15, $p->R0->asInt());
//    }
//
//    public function testAddIndirectedConstToRegOnLeftSide() {
//        //mov(r0,20)
//        //mov([100],40)
//        //add(r0,[100])
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 20));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 100, 'constant', false, 40));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'constant', true, 100));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(60, $p->R0->asInt());
//
//        $this->assertEquals(40, $p->mainMemory[100]->asInt());
//
//        $this->assertEquals(100, $p->MAR->asInt());
//
//        $this->assertEquals(40, $p->MDR->asInt());
//
//        $this->assertEquals(40, $p->AR2->asInt());
//    }
//
//    public function testAddIndirectedConstToRegOnRightSide() {
//        //mov(r0,400)
//        //add(r0,600)
//        //mov([50],1000)
//        //
//        //add(r0,[50])
//        //mov(r4,r0)
//        //mov([120],8000)
//        //add(r4,[120])
//        //deve haver 10 000 em R4
//
//        $p = new Program;
//
//        $lines1 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 400));
//
//        foreach ($lines1 as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines2 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'constant', false, 600));
//
//        foreach ($lines2 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines3 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 1000));
//
//        foreach ($lines3 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines4 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'constant', true, 50));
//
//        foreach ($lines4 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines5 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'r0', false, null));
//
//        foreach ($lines5 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines6 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 120, 'constant', false, 8000));
//
//        foreach ($lines6 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines7 = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r4', false, null, 'constant', true, 120));
//
//        foreach ($lines7 as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(10000, $p->R4->asInt());
//
//        $this->assertEquals(8000, $p->MDR->asInt());
////
//        $this->assertEquals(8000, $p->mainMemory[120]->asInt());
//
//        $this->assertEquals(1000, $p->mainMemory[50]->asInt());
////
//        $this->assertEquals(1000, $p->AR2->asInt());
//    }
//
//    public function testAddIndirectedConstantToIndirectedRegister() {
//        //mov([800],800);
//        //mov(r1,400);
//        //mov([r1],400);
//        //add([r1],[800]);
//        //
//        //posição 400 deve ter 1200
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 800, 'constant', false, 800));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 400));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'constant', false, 400));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r1', true, null, 'constant', true, 800));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1200, $p->mainMemory[400]->asInt());
//
//        $this->assertEquals(1200, $p->MDR->asInt());
//    }
//
//    public function testAddDirectConstantToIndirectedRegister() {
//
//        //mov(r1,999);
//        //mov([r1],400);
//        //add([r1],800);
//        //
//        //posição 999 deve ter 1200
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 999));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'constant', false, 400));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r1', true, null, 'constant', false, 800));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1200, $p->mainMemory[999]->asInt());
//
//        $this->assertEquals(1200, $p->MDR->asInt());
//    }
//
//    public function testADDIndirectedRegToIndirectedReg() {
//        //mov(r2,499);
//        //mov([r2],r2);
//        //
//        //mov(r4,501);
//        //mov([r4],r4)
//        //
//        //add([r2],[r4]);
//        //
//        //posição 499 deve ter 100
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 499));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r2', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 501));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', true, null, 'r4', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r2', true, null, 'r4', true, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1000, $p->mainMemory[499]->asInt());
//    }
//
//    public function testADDIndirectedRegToIndirectedReg_SecondTest() {
//        //mov(r2,499);
//        //
//        //mov(r0,r2)
//        //
//        //mov([r0],r2);
//        //
//        //mov(r4,501);
//        //mov([r4],r4)
//        //
//        //add([r0],[r4]);
//        //
//        //posição 499 deve ter 100
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 499));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'r2', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', true, null, 'r2', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 501));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', true, null, 'r4', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', true, null, 'r4', true, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1000, $p->mainMemory[499]->asInt());
//    }
//
//    public function testAddRegToIndirectedRegBothOnSameSide() {
//        //mov(r0,29);
//        //
//        //mov(r1,31)
//        //
//        //mov([r1],r1);
//        //
//        //
//        //add([r1],r0);
//        //
//        
//        //na posição 31 deve haver 60
//
//        $p = new Program;
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 29));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 31));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r1', true, null, 'r0', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(29, $p->R0->asInt());
//        $this->assertEquals(60, $p->mainMemory[31]->asInt());
//    }
//
//    public function testAddRegToIndirectedRegRegsOnDifferentSides() {
//        //mov(r4,29);
//        //
//        //mov(r1,31)
//        //
//        //mov([r1],r1);
//        //
//        //
//        //add([r1],r4);
//        //
//        
//        //na posição 31 deve haver 60
//
//        $p = new Program;
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 29));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 31));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r1', true, null, 'r4', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(29, $p->R4->asInt());
//        $this->assertEquals(60, $p->mainMemory[31]->asInt());
//    }
//
//    public function testAddIndirectedRegToRegRegsOnSameSide() {
//        //mov(r0,29);
//        //
//        //mov(r1,31)
//        //
//        //mov([r1],r1);
//        //
//        //
//        //add(r0,[r1]);
//        //
//        
//        //no registrador r0 deve haver 60
//
//        $p = new Program;
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 29));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 31));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r1', true, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(60, $p->R0->asInt());
//        $this->assertEquals(31, $p->AR2->asInt());
//    }
//
//    public function testAddIndirectedConstantToIndirectedConstant() {
//        //mov([100],100)
//        //mov([200],200)
//        //add([100],[200]);
//        //deve haver 300 na posição de memória 100
//        $p = new Program;
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 100, 'constant', false, 100));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 200, 'constant', false, 200));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 100, 'constant', true, 200));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(100, $p->MAR->asInt());
//        $this->assertEquals(300, $p->MDR->asInt());
//        $this->assertEquals(300, $p->mainMemory[100]->asInt());
//    }
//
//    public function testAddConstantToIndirectedConstant() {
//        //mov([100],100)
//        //add([100],200);
//        //deve haver 300 na posição de memória 100
//        $p = new Program;
//
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 100, 'constant', false, 100));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 100, 'constant', false, 200));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(100, $p->MAR->asInt());
//        $this->assertEquals(300, $p->MDR->asInt());
//        $this->assertEquals(300, $p->mainMemory[100]->asInt());
//    }
//
//    public function testAddIndirectedRegisterToIndirectedConstant() {
//        //mov([500],500)
//        //mov(r0,200)
//        //mov([r0],r0);
//        //add([500],[r0]);
//        //deve haver 700 na posição de memória 500
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 500, 'constant', false, 500));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 200));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', true, null, 'r0', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 500, 'r0', true, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(200, $p->AR2->asInt());
//        $this->assertEquals(500, $p->MAR->asInt());
//        $this->assertEquals(700, $p->MDR->asInt());
//        $this->assertEquals(700, $p->mainMemory[500]->asInt());
//    }
//
//    public function testAddRegisterToIndirectedConstant() {
//        //mov([500],500)
//        //mov(r0,200)
//        //add([500],r0);
//        //deve haver 700 na posição de memória 500
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 500, 'constant', false, 500));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 200));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 500, 'r0', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(200, $p->AR2->asInt());
//        $this->assertEquals(500, $p->MAR->asInt());
//        $this->assertEquals(700, $p->MDR->asInt());
//        $this->assertEquals(700, $p->mainMemory[500]->asInt());
//    }
//
//    public function testSubSimpleAMinusB() {
//        //MOV(R0,1001)    
//        //MOV(R2,979)    
//        //SUB(R0,R2)
//        //R0 deve ter 22    
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 1001));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 979));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r0', false, null, 'r2', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(22, $p->R0->asInt());
//    }
//
//    public function testSubSimpleBMinusA() {
//        //MOV(R0,28)    
//        //MOV(R2,498)    
//        //SUB(R2,R0)
//        //R2 deve ter 470    
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 28));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 498));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r2', false, null, 'r0', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(470, $p->R2->asInt());
//    }
//
//    public function testSubComplex() {
//        //MOV(R0,100)
//        //MOV([R0],600)    
//        //MOV([756],35)    
//        //SUB([756],[R0])
//        //[756] deve ter 565    
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 100));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', true, null, 'constant', false, 600));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 756, 'constant', false, 635));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'constant', true, 756, 'r0', true, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(35, $p->mainMemory[756]->asInt());
//    }
//
//    public function testMulSimple() {
//        //MOV(R1,15)    
//        //MOV(R3,5)    
//        //MUL(R1,R3)
//        //R1 deve ter 75    
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 15));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 5));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mul', 'r1', false, null, 'r3', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(75, $p->R1->asInt());
//    }
//
//    public function testMulComplex() {
//        //MOV([50],100)   
//        //MOV([51],35)    
//        //MUL([50],[51])
//        //[50] deve ter 3500    
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', false, 100));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 51, 'constant', false, 35));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mul', 'constant', true, 50, 'constant', true, 51));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(3500, $p->mainMemory[50]->asInt());
//    }
//
//    public function testAndSimple() {
//        //mov(r3,3)
//        //mov(r1,17)
//        //and(r3,r1)->deve ter 1 no r3 
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 3));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 17));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('and', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1, $p->R3->asInt());
//    }
//
//    public function testAndComplex() {
//        //mov(r0,30)
//        //mov([r0],511)
//        //mov([378],79)
//        //and([r0],[378])->deve ter 79 em [30] 
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 30));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', true, null, 'constant', false, 511));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 378, 'constant', false, 79));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('and', 'r0', true, null, 'constant', true, 378));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(79, $p->mainMemory[30]->asInt());
//    }
//
//    public function testOrSimple() {
//        //mov(r3,3)
//        //mov(r1,17)
//        //or(r3,r1)-> deve haver 19 no r3
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 3));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 17));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('or', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(19, $p->R3->asInt());
//    }
//
//    public function testSimpleNand() {
//        //mov(r3,3)
//        //mov(r1,17)
//        //nand(r3,r1)-> deve haver um número negativo no r3
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 3));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 17));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('nand', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(-2, $p->R3->asInt());
//    }
//
//    public function testSimpleNor() {
//        //mov(r3,1)
//        //mov(r1,2)
//        //nor(r3,r1)-> deve haver -4 no r3
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 1));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 2));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('nor', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(-4, $p->R3->asInt());
//    }
//
//    public function testSimpleXor() {
//        //mov(r3,53)
//        //mov(r1,22)
//        //xor(r3,r1)-> deve haver 35 no r3
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 53));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 22));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('xor', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(35, $p->R3->asInt());
//    }
//
//    public function testZeroFlagIsSet() {
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 53));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 53));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(true, $p->flags['Z']);
//    }
//
//    public function testCMPSetsFlag() {
//
//        //comparing two equal numbers
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 53));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 53));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('cmp', 'r3', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(true, $p->flags['Z']);
//        $this->assertEquals(false, $p->flags['N']);
//
//        //the contents of register r3 should not be modified
//
//        $this->assertEquals(53, $p->R3->asInt());
//    }
//
//    public function testCMPSetsFlag2() {
//
//        //param 1 < param 2
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 55));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 669));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('cmp', 'r0', false, null, 'r1', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(true, $p->flags['N']);
//        $this->assertEquals(false, $p->flags['Z']);
//
//        //the contents of register r0 should not be modified
//        $this->assertEquals(55, $p->R0->asInt());
//    }
//
//    public function testCMPSetsFlag3() {
//        //param 1 > param 2
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 6456));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'constant', false, 887));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('cmp', 'r4', false, null, 'r2', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(false, $p->flags['N']);
//        $this->assertEquals(false, $p->flags['Z']);
//
//        //the contents of register r4 should not be modified
//        $this->assertEquals(6456, $p->R4->asInt());
//    }
//
//    public function testSHR() {
//        //mov(r4,90)
//        //shr(r4) ->devemos ver 45 no r4
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 90));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('shr', 'r4', false, null, null, false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(45, $p->R4->asInt());
//
//        //mov(r4,0)
//        //shr(r4) ->devemos ver 0 no r4
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', false, 0));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('shr', 'r4', false, null, null, false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//
//        $this->assertEquals(0, $p->R4->asInt());
//
//        //mov([10],#3)
//        //shr([10]) ->devemos ver 1 no [10]
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 10, 'constant', false, 3));
//
//        $this->assertEquals(3, count($lines));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('shr', 'constant', true, 10, null, false, null));
//
//        $this->assertEquals(2, count($lines));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//
//        $this->assertEquals(3, $p->mainMemory[10]->asInt());
//
//        $p->runNextInstruction();
//
//        $this->assertEquals(1, $p->mainMemory[10]->asInt());
//
//
//        //mov(r3,90)
//        //mov([r3],45)
//        //shr([r3]) ->there should be 22 at [90]
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 90));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'constant', false, 45));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('shr', 'r3', true, null, null, false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(22, $p->mainMemory[90]->asInt());
//    }
//
//    public function testSHL() {
//        //mov(r3,90)
//        //mov([r3],45)
//        //shl([r3]) ->there should be 90 at [90]
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 90));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'constant', false, 45));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('shl', 'r3', true, null, null, false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(90, $p->mainMemory[90]->asInt());
//    }
//
//    public function testNOT() {
//        //mov(r3,3)
//        //not(r3) ->should be all 1's and two 0's at the end. what number is that? negative something
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 3)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('not', 'r3', false, null, null, false, null)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        //first 2 bits are zero'd
//        $this->assertEquals(0, $p->R3[0]);
//        $this->assertEquals(0, $p->R3[1]);
//
//        //all others been changed to 1
//        for ($i = 2; $i < 32; $i++) {
//            $this->assertEquals(1, $p->R3[$i]);
//        }
//    }
//
//    public function testNOTLeftSide() {
//        //mov(r1,8)
//        //not(r1)
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'constant', false, 8)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('not', 'r1', false, null, null, false, null)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(1, $p->R1[0]);
//        $this->assertEquals(1, $p->R1[1]);
//        $this->assertEquals(1, $p->R1[2]);
//        $this->assertEquals(0, $p->R1[3]);
//
//        for ($i = 4; $i < 32; $i++) {
//            $this->assertEquals(1, $p->R1[$i]);
//        }
//    }
//
//    public function testNOTInMemory() {
//        //mov([40],31)
//        //not([40]) -> 
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 40, 'constant', false, 31)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('not', 'constant', true, 40, null, false, null)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->runNextInstruction();
//        $p->runNextInstruction();
//
//        $this->assertEquals(0, $p->mainMemory[40][0]);
//        $this->assertEquals(0, $p->mainMemory[40][1]);
//        $this->assertEquals(0, $p->mainMemory[40][2]);
//        $this->assertEquals(0, $p->mainMemory[40][3]);
//        $this->assertEquals(0, $p->mainMemory[40][4]);
//
//        for ($i = 5; $i < 32; $i++) {
//            $this->assertEquals(1, $p->mainMemory[40][$i]);
//        }
//    }
//
//    public function testFlagZeroGetsSet() {
//
//        //mov(r3,50)
//        //mov([40],50)
//        //sub(r3,[40])
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 50)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 40, 'constant', false, 50)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r3', false, null, 'constant', true, 40)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->run();
//
//        $this->assertTrue($p->getFlag('Z'));
//    }
//
//    public function testFlagNegativeGetsSet() {
//        //mov(r3,2)
//        //mov([99],999)
//        //sub(r3,[99])
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 2)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 99, 'constant', false, 999)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r3', false, null, 'constant', true, 99)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->run();
//
//        $this->assertTrue($p->getFlag('N'));
//    }

    public function testSimpleLoopWorks() {
        //0:mov(r3,const)
        //1:5
        //2:mov(r0,const)//counter
        //3:6
        //4:add(r3,const)
        //5:9
        //6:sub(r0,const)
        //7:1
        //8:brg(-5) //só 10 bits livres aqui
        //ao final, deve haver 41 em r3

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 5)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 6)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r3', false, null, 'constant', false, 9)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r0', false, null, 'constant', false, 1)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('brg', 'constant', false, -9, null, false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        $this->assertEquals(59, $p->R3->asInt());
    }

}

?>
