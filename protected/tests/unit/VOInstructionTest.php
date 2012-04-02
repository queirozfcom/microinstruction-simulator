<?php

require_once '../../models/helpers/VOInstruction.php';


require_once '../../models/helpers/InstructionException.php';

/**
 * VOInstruction test case.
 */
class VOInstructionTest extends PHPUnit_Framework_TestCase {
	
	/**
	 * @var VOInstruction
	 */
	private $simplestPossibleVO;
	
	private $voinst;
        
        private $with_constant1;
        
        private $arg2_is_null;
        
	/**
	 * Prepares the environment before running a test.
	 */
	protected function setUp() {
		$this->simplestPossibleVO = new VOInstruction("", "", false,null,"r", false, null);
                $this->voinst = new VOInstruction("MOV","R2",false,null,"R0",true,null);
                $this->with_constant1 = new VOInstruction("ADD","CONSTANT",true,1000,"r0",false,null);
                $this->arg2_is_null = new VOInstruction('SHL','R1',false,null,null,false,null);
                
        }
	
	/**
	 * Cleans up the environment after running a test.
	 */
	protected function tearDown() {
	
		parent::tearDown ();
	}
	
	/**
	 * Constructs the test case.
	 */
	public function __construct() {
		// TODO Auto-generated constructor
	}
	public function test__construct() {
		// TODO Auto-generated VOInstructionTest->test__construct()
		
	}
        public function test_hasIndirection(){
            $this->assertTrue($this->voinst->hasIndirection());
            $this->assertTrue($this->with_constant1->hasIndirection());
        }
        public function test_hasIndirectionFalse(){
            $this->assertFalse($this->simplestPossibleVO->hasIndirection());

        }
        public function test_hasConstantTrue(){
            $this->assertTrue($this->with_constant1->hasConstant());
        }
        
        public function test_hasConstantFalse(){
            $this->assertFalse($this->voinst->hasConstant());
        }
        public function test_arg2CanBeNull(){
            $this->assertTrue($this->arg2_is_null->hasOnlyOneParameter());
        }
        
	public function test_arg1isconstantworksbothways(){
            $vo1=new VOInstruction("SUB",'constant',false,900,'constant',false,100);
            
            $vo2 = new VOInstruction("SHR",'r1',false,null,'r1',false,null);
            
            $this->assertTrue($vo1->arg1IsConstant());
            $this->assertFalse($vo2->arg1IsConstant());
        }
        
        public function test_arg1IsIndirectionAndArg2IsIndirection(){
            $vo = new VOInstruction('MOV','r1',true,null,'r2',true,null);
            
            $this->assertTrue($vo->arg1IsIndirection());
            $this->assertTrue($vo->arg2IsIndirection());
        }
        

}