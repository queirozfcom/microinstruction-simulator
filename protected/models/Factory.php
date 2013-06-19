<?php

/**
 * Given a VO with parameters, this class will, through it's sole method,
 * return the set of Instructions/BinaryStrings that amount to that VO.
 * 
 * For example, if given a VO like this: MOV([R3],900) , this class's method will return
 * one Instruction that is equal to MOV([R3],#CONST) and a BinaryString amounting to the 
 * constant value, in this case, 900, in binary form.
 * 
 * The output of this class' method is an array of Instructions that can, each, be appended to
 * the program's MainMemory.
 * 
 */
class Factory {

    public static function returnInstructionAndPossibleConstants(VOInstruction $vo) {
        $output = [];

        if ($vo->representsABranch())
            $output[] = new Instruction($vo->getMnemonic(), $vo->getConstant1());
        else {
            $mnem = $vo->getMnemonic();

            //source
            $param1 = $vo->getArg1();
            $ind1 = $vo->getIndirection1();

            //target
            $param2 = $vo->getArg2();
            $ind2 = $vo->getIndirection2();

            $inst = new Instruction($mnem, $param1, $ind1, $param2, $ind2);
            $output[] = $inst;

            if ($vo->arg1IsConstant()) {
                if (count($vo->getConstant1()) >= 2 && $vo->getConstant1()[0] === '0' && $vo->getConstant1()[1] === 'x') {
                    //hexadecimal numbers are at least 2 characters long (0x)
                    $numberAsInt = hexdec($vo->getConstant1());
                    $bs = new BinaryString(32, $numberAsInt);
                } elseif (count($vo->getConstant1()) >= 3 && $vo->getConstant1()[0] === '-' && $vo->getConstant1()[1] === '0' && $vo->getConstant1()[2] === 'x') {
                    //and negative hex numbers are at least 3 characters long
                    $positiveNumber = hexdec(ltrim($vo->getConstant1(), '-'));
                    $number = $positiveNumber - 2 * ($positiveNumber);
                    $bs = new BinaryString(32, $number);
                }
                else
                    $bs = new BinaryString(32, $vo->getConstant1());

                $output[] = $bs;
            }
            if ($vo->arg2IsConstant()) {
                $bs2 = new BinaryString(32, $vo->getConstant2());
                $output[] = $bs2;
            }
        }
        return $output;
    }

}
