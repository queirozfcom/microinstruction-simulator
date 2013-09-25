<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Suite
 *
 * @author felipe
 */

include 'ProgramTest.php';

class Suite extends CDbTestCase{
    public function test_all(){
        $p = new ProgramTest;
        $p->run();
    }
}

?>
