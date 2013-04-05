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
    
    public function testMovRegToReg(){
        //mov(4,r0)
        //mov(r0,r3) //4 in r3
        
        $p = new Program;
        
        foreach ($lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 4, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach ($lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(4, $p->R3->asInt());
        
    }
    
    public function test_simplest_mov(){
        //mov(50,r0)
        $p = new Program;
        
        foreach ($lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(50, $p->R0->asInt());
        
    }
    
    public function test_simple_mov() {
        $p = new Program;

        foreach ($lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 100, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(100, $p->R1->asInt());

        $this->setExpectedException('InstructionException');

        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r7', false, null, 'r1', false, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }

        $p->runNextInstruction();
    }
    
    public function test_const_to_indirected_const(){
        //mov(50,[10])
        $p = new Program;
        
        foreach ($lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'constant', true, 10)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(50, $p->mainMemory[10]->asInt());
    }
    
    public function test_encompassing_mov() {
        
        //mov(99,r4)
        //mov(r4,[20])
        //deve haver 99 em [20]
        
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 99, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'constant', true, 20)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(
                99, $p->mainMemory[20]->asInt()
        );
    }

    public function test_que_ta_dando_problema() {
        //MOV(10,[50])
        //MOV([50],R0) //there should be 10 in R0


        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 10, 'constant', true, 50)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        $this->assertEquals(10, $p->mainMemory[50]->asInt());
        
        $this->assertEquals(10, $p->R0->asInt());
    }

    public function test_confirmando_que_movs_tao_certos() {
        //MOV(99,[50])
        //MOV(50,R0)
        //MOV([R0],R1)

        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false,99 , 'constant', true, 50)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', true, null, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();


        $this->assertEquals(99, $p->R1->asInt());
    }

    public function test_reg_indirecionado_para_reg_indirecionado() {
        //MOV(30,R1) 1
        //MOV(40,R2) 2
        //MOV(345,[R1]) 3 //[30] tem 345
        //MOV([R1],[R2]) 4 [R2] agora tem 345 tbm
        //MOV([R2],R3) 5
        //devemos ver 345 em R3

        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 30, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 40, 'r2', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false , 345, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', true, null, 'r2', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', true, null, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        $this->assertEquals(30, $p->R1->asInt());
        $this->assertEquals(40, $p->R2->asInt());
        $this->assertEquals(345, $p->mainMemory[30]->asInt());
        $this->assertEquals(345, $p->mainMemory[40]->asInt());
        $this->assertEquals(345, $p->R3->asInt());
    }

    public function test_const_pra_const() {
        //MOV(44567,[50])
        //MOV([50],[40])
        //MOV([40],R3)
        //devemos ver 44567 no R3

        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 44567, 'constant', true, 50)) as $line) {
            $p->appendToMemory($line);
        }


        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 50, 'constant', true, 40)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 40, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(44567, $p->mainMemory[50]->asInt());    
        $this->assertEquals(44567, $p->mainMemory[40]->asInt());    
        
        $this->assertEquals(44567, $p->R3->asInt());
    }

    public function test_move_reg_to_indirected_reg() {
        //MOV(50,R3)
        //MOV(40,R2)
        //MOV(R3,[R2])
        //devemos ver 50 em [40]
        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 40, 'r2', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3',false, null, 'r2', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        $this->assertEquals(50, $p->R3->asInt());
        $this->assertEquals(40, $p->R2->asInt());
        $this->assertEquals(50, $p->mainMemory[40]->asInt());
    }

    public function test_final_mov() {
        //MOV(50,R3) 1
        //MOV(100,[R3]) 2
        //MOV([R3],R2) 3 //R2<-100
        //MOV(R3,[R2]) 4 //[100]<-50
        //MOV([100],R4) 5 //R4<-50
        //devemos ver 50 em R4

        $p = new Program();

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 100, 'r3', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'r2', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'r2', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', true, 100, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(50, $p->R4->asInt());
        $this->assertEquals(50, $p->mainMemory[100]->asInt());
    }

    public function testLastCase() {
        //MOV(100,R1)
        //MOV(50,R3)
        //MOV(R1,[R3])
        //MOV([R3],[200])

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 100, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'r3', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', true, null, 'constant', true, 200)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(50, $p->R3->asInt());
        $this->assertEquals(100, $p->R1->asInt());
        $this->assertEquals(100, $p->mainMemory[50]->asInt());
        $this->assertEquals(100, $p->mainMemory[200]->asInt());
    }

    public function testMovToR0(){
        //mov(40,r1)
        //mov(50,r0)
        $p = new Program;
        
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(50, $p->R0->asInt());
    }
    
    public function testMovToAR2(){
        //mov(50,AR2)
        $p = new Program;
        
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'ar2', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(50, $p->AR2->asInt());
    }
    
//    public function testMovRegToMar() {
//        //mov(50,r4)
//        //mov(r4,mar)
//
//        $p = new Program;
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r4', false, null));
//
//        foreach ($lines as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'mar', false, null));
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
//    
    public function testAddRegistersOnDifferentSide() {
        //mov(80,r0)
        //mov(180,r3)
        //add(r0,r3) //deve haver 260 em R3
        $p = new Program;


        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 80, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 180, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(260, $p->R3->asInt());
    }

    public function testAddRegistersOnSameSideLEFT() {
        //mov(15,r0)
        //mov(3000,r1)
        //add(r0,r1)
        $p = new Program;


        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 15, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 3000, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        
        $this->assertEquals(15, $p->R0->asInt());
        $this->assertEquals(3015, $p->R1->asInt());
        $this->assertEquals(15, $p->AR2->asInt());
    }

    public function testAddRegistersOnSameSideRIGHT() {
        //mov(9999,r3)
        //mov(5000,r4)
        //add(r3,r4)
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 9999, 'r3', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 5000, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r3', false, null, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(9999, $p->AR1->asInt());

        $this->assertEquals(9999, $p->R3->asInt());
        $this->assertEquals(14999, $p->R4->asInt());
    }

    public function testAddConstToRegOnRightSide() {
        //mov(8,r4)
        //add(8892,r4)
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 8, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 8892, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(8900, $p->R4->asInt());
        $this->assertEquals(8892, $p->MDR->asInt());
    }

    public function testAddConstToRegOnLeftSide() {
        //mov(5,r0)
        //add(10,r0)
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 5, 'r0', false, 0)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 10, 'r0', false, 0)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(10, $p->MDR->asInt());

        $this->assertEquals(10, $p->AR2->asInt());

        $this->assertEquals(15, $p->R0->asInt());
    }

    public function testAddIndirectedConstToRegOnLeftSide() {
        //mov(20,r0)
        //mov(40,[100])
        //add([100],r0)
        $p = new Program;
        
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 20, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 40, 'constant', true, 100)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 100, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(60, $p->R0->asInt());

        $this->assertEquals(40, $p->mainMemory[100]->asInt());

        $this->assertEquals(40, $p->MDR->asInt());

        $this->assertEquals(40, $p->AR2->asInt());
    }

    public function testAddIndirectedConstToRegOnRightSide() {
        //mov(400,r0)
        //add(600,r0)
        //mov(1000,[50])
        //add([50],r0)
        //mov(r0,r4)
        //mov(8000,[120])
        //add([120],r4)
        //deve haver 10 000 em R4

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 400, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 600, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 1000, 'constant', true, 50)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 50, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 8000, 'constant', true, 120)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 120, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        $p->run();

        $this->assertEquals(10000, $p->R4->asInt());

        $this->assertEquals(8000, $p->MDR->asInt());

        $this->assertEquals(8000, $p->mainMemory[120]->asInt());

        $this->assertEquals(1000, $p->mainMemory[50]->asInt());

        $this->assertEquals(1000, $p->AR2->asInt());
    }

    public function testAddIndirectedConstantToIndirectedRegister() {
        //mov(800,[800]);
        //mov(400,r1);
        //mov(400,[r1]);
        //add([800],[r1]);
        //
        //posição 400 deve ter 1200

        $p = new Program;


        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 800, 'constant', true, 800)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 400, 'r1', false, false)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 400, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 800, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(1200, $p->mainMemory[400]->asInt());

        $this->assertEquals(1200, $p->MDR->asInt());
    }

    public function testAddDirectConstantToIndirectedRegister() {

        //mov(999,r1);
        //mov(400,[r1]);
        //add(800,[r1]);
        //
        //posição 999 deve ter 1200

        $p = new Program;
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 999, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 400, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 800, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(1200, $p->mainMemory[999]->asInt());

        $this->assertEquals(1200, $p->MDR->asInt());
    }

    public function testADDIndirectedRegToIndirectedReg() {
        //mov(499,r2);
        //mov(r2,[r2]);
        //mov(501,r4);
        //mov(r4,[r4])
        //add([r4],[r2]);
        //
        //posição 499 deve ter 1000

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 499, 'r2', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'r2', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 501, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'r4', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r4', true, null, 'r2', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        $this->assertEquals(501, $p->R4->asInt());
        $this->assertEquals(499, $p->R2->asInt());
        
        $this->assertEquals(501, $p->mainMemory[501]->asInt());
        $this->assertEquals(1000, $p->mainMemory[499]->asInt());
    }

    public function testADDIndirectedRegToIndirectedReg_SecondTest() {
        //mov(300,r2);
        //mov(r2,r0);
        //mov(r2,[r0]);
        //mov(501,r4);
        //mov(r4,[r4])
        //add([r4],[r0]);
        //
        //posição 300 deve ter 801

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 300, 'r2', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r2', false, null, 'r0', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 501, 'r4', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r4', false, null, 'r4', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r4', true, null, 'r0', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(801, $p->mainMemory[300]->asInt());
    }
    
    public function testMovToMDR(){
        //mov(50,r1)
        //mov(r1,mdr)
        
        $p = new Program;

        $p->appendToMemory(new Instruction('mov','constant',false,'r1',false));
        $p->appendToMemory(new BinaryString(32,50));
        $p->appendToMemory(new Instruction('mov','r1',false,'mdr',false));
        
        $p->run();
        
        $this->assertEquals(50, $p->MDR->asInt());
        $this->assertEquals(50, $p->R1->asInt());
        
    }
    
    
    public function testAddRegToIndirectedRegBothOnSameSide() {
        //mov(29,r0);
        //mov(31,r1)
        //mov(r1,[r1]);
        //add(r0,[r1]);
        //na posição 31 deve haver 60

        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 29, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 31, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', false, null, 'r1', true, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        $this->assertEquals(29, $p->R0->asInt());
        $this->assertEquals(31, $p->R1->asInt());
        $this->assertEquals(60, $p->mainMemory[31]->asInt());
    }
  public function testAddIndirectedRegToRegRegsOnSameSide() {
        //mov(29r0,);
        //mov(31r1,)
        //mov(r1[r1],);
        //add([r1]r0,);
        
        //no registrador r0 deve haver 60

        $p = new Program;


        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 29, 'r0', false, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }


        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 31, 'r1', false, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }



        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r1', false, null, 'r1', true, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }


        $lines = Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r1', true, null, 'r0', false, null));

        foreach ($lines as $line) {
            $p->appendToMemory($line);
        }


        $p->run();

        $this->assertEquals(60, $p->R0->asInt());
        $this->assertEquals(31, $p->AR2->asInt());
    }

    public function testAddIndirectedConstantToIndirectedConstant() {
        //mov(100[100],)
        //mov(200[200],)
        //add([200][100],);
        //deve haver 300 na posição de memória 100
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 100, 'constant', true, 100)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 200, 'constant', true, 200)) as $line) {
            $p->appendToMemory($line);
        }

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 200, 'constant', true, 100)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(300, $p->mainMemory[100]->asInt());
    }
    
    public function testAddConstToReg(){
        //mov(50,r1)
        //add(70,r1)
        $p = new Program;
        
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 50, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 70, 'r1', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        
        $p->run();
        
        $this->assertEquals(120, $p->R1->asInt());
        
    }
    
    public function testAddConstantToIndirectedConstant() {
        //mov(100,[100])
        //add(200,[100]);
        //deve haver 300 na posição de memória 100
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 100, 'constant', true, 100)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', false, 200, 'constant', true, 100)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();
        
        $this->assertEquals(300, $p->mainMemory[100]->asInt());
    }

    public function testAddIndirectedRegisterToIndirectedConstant() {
        //mov(500[500],)
        //mov(200r0,)
        //mov(ro[r0],);
        //add([r0][500],);
        //deve haver 700 na posição de memória 500
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 500, 'constant', true, 500)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 200, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'r0', true, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r0', true,null, 'constant', true, 500)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(700, $p->MDR->asInt());
        $this->assertEquals(700, $p->mainMemory[500]->asInt());
    }

    public function testAddRegisterToIndirectedConstant() {
        //mov(500,[444])
        //mov(700,r0)
        //add([444],r0);
        //deve haver 1200 na posição de memória 444
        $p = new Program;

        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 500, 'constant', true, 444)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'constant', false, 700, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }
        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'constant', true, 444, 'r0', false, null)) as $line) {
            $p->appendToMemory($line);
        }

        $p->run();

        $this->assertEquals(500, $p->mainMemory[444]->asInt());
        $this->assertEquals(1200, $p->R0->asInt());
    }

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
//
//    public function testSimpleLoopWorks() {
//        //0:mov(r3,const)
//        //1:5
//        //2:mov(r0,const)//counter
//        //3:6
//        //4:add(r3,const)
//        //5:9
//        //6:sub(r0,const)
//        //7:1
//        //8:brg(-5) //só 10 bits livres aqui
//        //ao final, deve haver 59 em r3
//
//        $p = new Program;
//
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r3', false, null, 'constant', false, 5)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('mov', 'r0', false, null, 'constant', false, 6)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('add', 'r3', false, null, 'constant', false, 9)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('sub', 'r0', false, null, 'constant', false, 1)) as $line) {
//            $p->appendToMemory($line);
//        }
//        foreach (Factory::returnInstructionAndPossibleConstants(new VOInstruction('brg', 'constant', false, -5, null, false, null)) as $line) {
//            $p->appendToMemory($line);
//        }
//
//        $p->run();
//        $this->assertEquals(59, $p->R3->asInt());
//    }
     
     

}

?>
