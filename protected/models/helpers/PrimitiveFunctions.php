<?php
/**
 * Adds and arbitrary ($n) number of leading zeros to a number
 * 
 * @param int $number
 * @param int $n
 * @return string the number converted to binary and padded to 32 spaces
 */
function number_pad($number,$n) {
	return str_pad((int) $number,$n,"0",STR_PAD_LEFT);
}
/**
 * 
 * takes an int as input and ,optionally, another int.
 * 
 * @example int2PaddedBinaryString(32) returns "0000000000100000" ->16bit default length
 * @example int2PaddedBinaryString(31,10) returns "0000011111"    ->10-bit custom size
 * 
 * @see reverseIndexes($binaryString);
 * @param int $num
 */
function int2PaddedBinaryString($num) {
	$size            = 32;
	if(func_num_args()==2){
		$size  = func_get_arg(1);

	}
 
	$bin             = decbin($num);
	$bin_as_string   = (String) $bin;
	$padded_bin      = str_pad($bin_as_string,$size,"0",STR_PAD_LEFT);
	return $padded_bin;
}

function handleUncaughtException($e){
	
	echo strtoupper(get_class($e))." : ".strtoupper($e->getMessage());
}

function br(){
	echo "<br />";
}
/**
 * Echoes the $arg and a newline character
 * @param  $arg
 */
function echoln($arg){
	echo $arg."<br />";
}
function dump($method,$class){
	br();
	echo "method: ".$method;
	br();
	echo "class: ".$class;
	br();
}


?>