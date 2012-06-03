<?php

class Factory{
    public static function returnInstructionAndPossibleConstants(VOInstruction $vo){
        $output = array();
        
        $mnem = $vo->getMnemonic();
        $param1 = $vo->getArg1();
        $ind1 = $vo->getIndirection1();
        $param2 = $vo->getArg2();
        $ind2 = $vo->getIndirection2();
        
        $inst = new Instruction($mnem,$param1,$ind1,$param2,$ind2);
        $output[] = $inst;
        
        if($vo->arg1IsConstant()){
            $bs = new BinaryString(32, $vo->getConstant1());
            $output[] =$bs;
        }
        if($vo->arg2IsConstant()){
            $bs2 = new BinaryString(32,$vo->getConstant2());
            $output[] = $bs2; 
        }
        
        return $output;
        
    }
}
